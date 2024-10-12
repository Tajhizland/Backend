<?php

namespace App\Services\Payment;

use App\Enums\CartStatus;
use App\Enums\OnHoldOrderStatus;
use App\Enums\OrderStatus;
use App\Events\OrderPaidEvent;
use App\Exceptions\BreakException;
use App\Repositories\Address\AddressRepositoryInterface;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\Delivery\DeliveryRepositoryInterface;
use App\Repositories\OnHoldOrder\OnHoldOrderRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderInfo\OrderInfoRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\Stock\StockRepositoryInterface;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\CartItem\CartItemServiceInterface;
use App\Services\Checkout\CheckoutServiceInterface;
use App\Services\Payment\Gateways\Strategy\GatewayStrategyServicesInterface;
use Carbon\Carbon;

class PaymentService implements PaymentServicesInterface
{
    private $gatewayService;

    public function __construct(
        private GatewayStrategyServicesInterface $gatewayStrategyServices,
        private CartRepositoryInterface          $cartRepository,
        private CartItemRepositoryInterface      $cartItemRepository,
        private UserRepositoryInterface          $userRepository,
        private DeliveryRepositoryInterface      $deliveryRepository,
        private OrderRepositoryInterface         $orderRepository,
        private OrderItemRepositoryInterface     $orderItemRepository,
        private OrderInfoRepositoryInterface     $orderInfoRepository,
        private AddressRepositoryInterface       $addressRepository,
        private StockRepositoryInterface         $stockRepository,
        private TransactionRepositoryInterface   $transactionRepository,
        private CartItemServiceInterface         $cartItemService,
        private OnHoldOrderRepositoryInterface   $onHoldOrderRepository,
        private CheckoutServiceInterface         $checkoutService,
    )
    {
        $this->gatewayService = $this->gatewayStrategyServices->strategy();
    }

    public function request($userId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        $cartItems = $this->cartItemRepository->getItemsByCartId($cart->id);
        $this->checkoutService->finalCheckout($cart, $cartItems);
        $limit = $this->cartItemService->checkLimit($cartItems);
        $user = $this->userRepository->findOrFail($userId);
        $address = $this->addressRepository->findUserAddress($userId);
        $delivery = $this->deliveryRepository->findOrFail($cart->delivery_method);
        $cartPrices = $this->cartItemService->calculatePrice($cartItems);
        $totalItemsPrice = $cartPrices["totalItemPrice"];
        $finalPrice = $totalItemsPrice + $delivery->price;
        $orderStatus = $limit ? OrderStatus::OnHold->value : OrderStatus::Unpaid->value;
        $orderInfo = $this->orderInfoRepository->createOrderInfo($user->name, $address->mobile, $address->tell, $address->province_id, $address->city_id, $address->address, $address->zip_code);
        $order = $this->orderRepository->createOrder($userId, $orderInfo->id, $totalItemsPrice, $delivery->price, $finalPrice, $orderStatus, $cart->payment_method, $cart->delivery_method, Carbon::now(), Carbon::now(), "");
        $this->cartRepository->update($cart,["order_id"=>$order->id]);
        $this->cartItemService->convertCartItemToOrderItem($cartItems, $order->id);
        if ($limit) {
            $this->onHoldOrderRepository->createOnHoldOrder($order->id);
            return true;
        }
        return $this->gatewayService->request($finalPrice, $order->id);
    }

    public function onHoldOrderRequest($id, $userId)
    {
        $onHoldOrder = $this->onHoldOrderRepository->findOrFail($id);
        if($onHoldOrder->expire_date < Carbon::now()){
            throw new BreakException(\Lang::get("exceptions.expired_order"));
        }
        if($onHoldOrder->status!=OnHoldOrderStatus::Accept->value){
            throw new BreakException(\Lang::get("exceptions.reject_order"));
        }
        $orderId = $onHoldOrder->order_id;
        $cart = $this->cartRepository->getCartByOrderId($orderId);
        if ($cart->user_id != $userId) {
            throw new BreakException(\Lang::get("exceptions.not_your_order"));
        }
        $cartItems = $this->cartItemRepository->getItemsByCartId($cart->id);
        $this->checkoutService->finalCheckout($cart, $cartItems);
        $order = $this->orderRepository->findOrFail($orderId);
        return $this->gatewayService->request($order->final_price, $orderId);
    }

    public function verifyPayment($request)
    {
        $request = $this->gatewayService->callbackParams($request);
//          $this->gatewayService->verify($request->trackId);
        $order = $this->orderRepository->findOrFail($request->orderId);
        $this->orderRepository->setStatus($order, OrderStatus::Paid->value);
        $orderItems = $this->orderItemRepository->getByOrderId($order->id);
        foreach ($orderItems as $item) {
            $this->stockRepository->decrement($item->product_color_id, $item->count);
        }
        $this->transactionRepository->createTransaction($order->user_id, $order->id, $request->trackId, $order->final_price);
        $cart = $this->cartRepository->getCartByOrderId($order->orderId);
        $this->cartRepository->changeStatus($cart, CartStatus::Completed->value);

        event(new OrderPaidEvent($order));

        return 1;
    }
}
