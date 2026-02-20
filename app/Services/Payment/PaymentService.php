<?php

namespace App\Services\Payment;

use App\Enums\CartStatus;
use App\Enums\OnHoldOrderStatus;
use App\Enums\OrderStatus;
use App\Events\OrderPaidEvent;
use App\Events\OrderPaymentRequestEvent;
use App\Events\OrderRequestEvent;
use App\Exceptions\BreakException;
use App\Repositories\Address\AddressRepositoryInterface;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\CouponUser\CouponUserRepositoryInterface;
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
use App\Services\Coupon\CouponServiceInterface;
use App\Services\DigiPay\DigiPayService;
use App\Services\Payment\Gateways\Strategy\GatewayStrategyServicesInterface;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
        private CouponServiceInterface           $couponService,
        private CouponUserRepositoryInterface    $couponUserRepository,
        private DigiPayService                   $digiPayService,
    )
    {
        $this->gatewayService = $this->gatewayStrategyServices->strategy();
    }

    public function request($userId, $useWallet, $shippingMethod, $code = null, $shippingPrice = 0, $gateway = 1)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        $cartItems = $this->cartItemRepository->getItemsByCartId($cart->id);
        $this->checkoutService->finalCheckout($cart, $cartItems);
        $limit = $this->cartItemService->checkLimit($cartItems);
        $user = $this->userRepository->findOrFail($userId);
        $address = $this->addressRepository->findActiveByUserId($userId);
        $delivery = $this->deliveryRepository->findOrFail($shippingMethod);
        $cartPrices = $this->cartItemService->calculatePrice($cartItems);
        $totalItemsPrice = $cartPrices["totalItemPrice"];
        $maxDeliveryDelay = $cartPrices["maxDeliveryDelay"];
        $finalPrice = $totalItemsPrice + $shippingPrice;
        $coupon = null;
        $off = 0;
        if ($code != null) {
            $coupon = $this->couponService->check($code, $userId);
            if ($coupon) {
                if ($coupon->price) {
                    $off = $coupon->price;
                } elseif ($coupon->percent) {
                    $off = $finalPrice * $coupon->percent / 100;
                }
            }
        }
        $finalPrice = $finalPrice - $off;
        if (!$useWallet) {

            $orderStatus = $limit ? OrderStatus::OnHold->value : OrderStatus::Unpaid->value;
            $orderInfo = $this->orderInfoRepository->createOrderInfo($user->name, $address->mobile, $address->tell, $address->province_id, $address->city_id, $address->address, $address->zip_code, $user->last_name, $user->national_code);
            $order = $this->orderRepository->createOrder($userId, $orderInfo->id, $totalItemsPrice, $shippingPrice, $finalPrice, $orderStatus, $gateway, $shippingMethod, Carbon::now(), Carbon::now()->addDays($maxDeliveryDelay), "", $finalPrice, 0, $off);
            if ($coupon) {
                $this->couponUserRepository->create(["order_id" => $order->id, "user_id" => $userId, "coupon_id" => $coupon->id]);
            }
            $this->cartRepository->update($cart, ["order_id" => $order->id]);
            $this->cartItemService->convertCartItemToOrderItem($cartItems, $order->id);
            if ($limit) {
                $onHoldOrder = $this->onHoldOrderRepository->createOnHoldOrder($order->id);
                event(new OrderRequestEvent($onHoldOrder));
                return [
                    "path" => "/thank_you_page",
                    "type" => "limit"
                ];
            }
            event(new OrderPaymentRequestEvent($order));
            if ($gateway == 3) {
                $orderItems = $this->orderItemRepository->getByOrderId($order->id);
                $path = $this->digiPayService->request($finalPrice * 10, $address->mobile, $order->id, $orderItems);
            } else {
                $path = $this->gatewayService->request($finalPrice * 10, $order->id);
            }
            return [
                "path" => $path,
                "type" => "payment"
            ];
        }
        if ($finalPrice <= $user->wallet) {
            $orderStatus = $limit ? OrderStatus::OnHold->value : OrderStatus::Unpaid->value;
            $orderInfo = $this->orderInfoRepository->createOrderInfo($user->name, $address->mobile, $address->tell, $address->province_id, $address->city_id, $address->address, $address->zip_code, $user->last_name, $user->national_code);
            $order = $this->orderRepository->createOrder($userId, $orderInfo->id, $totalItemsPrice, $delivery->price, $finalPrice, $orderStatus, 2, $cart->delivery_method, Carbon::now(), Carbon::now()->addDays($maxDeliveryDelay), "", $finalPrice, $finalPrice, $off);
            if ($coupon) {
                $this->couponUserRepository->create(["order_id" => $order->id, "user_id" => $userId, "coupon_id" => $coupon->id]);
            }
            $this->cartRepository->update($cart, ["order_id" => $order->id]);
            $this->cartItemService->convertCartItemToOrderItem($cartItems, $order->id);
            if ($limit) {
                $onHoldOrder = $this->onHoldOrderRepository->createOnHoldOrder($order->id);
                event(new OrderRequestEvent($onHoldOrder));
                return [
                    "path" => "/thank_you_page",
                    "type" => "limit"
                ];
            }
            $this->userRepository->update($user, ["wallet" => $user->wallet - $finalPrice]);

            $this->orderRepository->setStatus($order, OrderStatus::Paid->value);
            $orderItems = $this->orderItemRepository->getByOrderId($order->id);
            foreach ($orderItems as $item) {
                $this->stockRepository->decrement($item->product_color_id, $item->count);
            }
            $this->cartRepository->changeStatus($cart, CartStatus::Completed->value);
            event(new OrderPaidEvent($order));
            return [
                "path" => "/thank_you_page",
                "type" => "paid"
            ];
        } else {
            $totalPrice = $finalPrice;
            $finalPrice -= $user->wallet;
            $orderStatus = $limit ? OrderStatus::OnHold->value : OrderStatus::Unpaid->value;
            $orderInfo = $this->orderInfoRepository->createOrderInfo($user->name, $address->mobile, $address->tell, $address->province_id, $address->city_id, $address->address, $address->zip_code, $user->last_name, $user->national_code);
            $order = $this->orderRepository->createOrder($userId, $orderInfo->id, $totalItemsPrice, $delivery->price, $finalPrice, $orderStatus, $cart->payment_method, $cart->delivery_method, Carbon::now(), Carbon::now()->addDays($maxDeliveryDelay), "", $totalPrice, $user->wallet, $off);
            if ($coupon) {
                $this->couponUserRepository->create(["order_id" => $order->id, "user_id" => $userId, "coupon_id" => $coupon->id]);
            }
            $this->cartRepository->update($cart, ["order_id" => $order->id]);
            $this->cartItemService->convertCartItemToOrderItem($cartItems, $order->id);
            if ($limit) {
                $onHoldOrder = $this->onHoldOrderRepository->createOnHoldOrder($order->id);
                event(new OrderRequestEvent($onHoldOrder));
                return [
                    "path" => "/thank_you_page",
                    "type" => "limit"
                ];
            }
            event(new OrderPaymentRequestEvent($order));

            return [
                "path" => $this->gatewayService->request($finalPrice * 10, $order->id),
                "type" => "payment"
            ];
        }

    }

    public function request2($userId, $useWallet)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        $cartItems = $this->cartItemRepository->getItemsByCartId($cart->id);
        $this->checkoutService->finalCheckout($cart, $cartItems);
        $limit = $this->cartItemService->checkLimit($cartItems);
        $user = $this->userRepository->findOrFail($userId);
        $address = $this->addressRepository->findActiveByUserId($userId);
        $delivery = $this->deliveryRepository->findOrFail($cart->delivery_method);
        $cartPrices = $this->cartItemService->calculatePrice($cartItems);
        $totalItemsPrice = $cartPrices["totalItemPrice"];
        $maxDeliveryDelay = $cartPrices["maxDeliveryDelay"];
        $finalPrice = $totalItemsPrice + $delivery->price;
        $orderStatus = $limit ? OrderStatus::OnHold->value : OrderStatus::Unpaid->value;
        $orderInfo = $this->orderInfoRepository->createOrderInfo($user->name, $address->mobile, $address->tell, $address->province_id, $address->city_id, $address->address, $address->zip_code, $user->last_name, $user->national_code);
        $order = $this->orderRepository->createOrder($userId, $orderInfo->id, $totalItemsPrice, $delivery->price, $finalPrice, $orderStatus, $cart->payment_method, $cart->delivery_method, Carbon::now(), Carbon::now()->addDays($maxDeliveryDelay), "");
        $this->cartRepository->update($cart, ["order_id" => $order->id]);
        $this->cartItemService->convertCartItemToOrderItem($cartItems, $order->id);
        if ($limit) {
            $onHoldOrder = $this->onHoldOrderRepository->createOnHoldOrder($order->id);
            event(new OrderRequestEvent($onHoldOrder));
            return [
                "path" => "/thank_you_page",
                "type" => "limit"
            ];
        }
        event(new OrderPaymentRequestEvent($order));

        return [
            "path" => $this->gatewayService->request($finalPrice * 10, $order->id),
            "type" => "payment"
        ];
    }

    public function onHoldOrderRequest($id, $userId)
    {
        $onHoldOrder = $this->onHoldOrderRepository->findOrFail($id);
        if (Carbon::parse($onHoldOrder->expire_date) < Carbon::now()) {
            throw new BreakException(\Lang::get("exceptions.expired_order"));
        }
        if ($onHoldOrder->status != OnHoldOrderStatus::Accept->value) {
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
        if ($order->payment_method == 3) {
            $request = $this->digiPayService->request($order->final_price * 10, $order->orderInfo->mobile, $orderId, $this->orderItemRepository->getByOrderId($orderId));
        } else
            $request = $this->gatewayService->request($order->final_price * 10, $orderId);
        return $request;
    }

    public function verifyPayment($request)
    {
        $request = $this->gatewayService->callbackParams($request);
        $this->gatewayService->verify($request->trackId);
        $order = $this->orderRepository->findOrFail($request->orderId);
        $this->orderRepository->setStatus($order, OrderStatus::Paid->value);
        $orderItems = $this->orderItemRepository->getByOrderId($order->id);
        foreach ($orderItems as $item) {
            $this->stockRepository->decrement($item->product_color_id, $item->count);
        }
        $this->transactionRepository->createTransaction($order->user_id, $order->id, $request->trackId, $order->final_price);
        $user = $this->userRepository->findOrFail($order->user_id);
        $this->userRepository->update($user, ["wallet" => $user->wallet - $order->use_wallet_price]);

        $cart = $this->cartRepository->getCartByOrderId($order->orderId);
        $this->cartRepository->changeStatus($cart, CartStatus::Completed->value);

        event(new OrderPaidEvent($order));

        return 1;
    }

    public function verifyPayment2($request)
    {
        $request = $this->digiPayService->callbackParams($request);
        $this->digiPayService->verify($request->trackId, $request->orderId);
        $order = $this->orderRepository->findOrFail($request->orderId);
        $this->orderRepository->setStatus($order, OrderStatus::Paid->value);
        $orderItems = $this->orderItemRepository->getByOrderId($order->id);
        foreach ($orderItems as $item) {
            $this->stockRepository->decrement($item->product_color_id, $item->count);
        }
        $this->transactionRepository->createTransaction($order->user_id, $order->id, $request->trackId, $order->final_price);
        $user = $this->userRepository->findOrFail($order->user_id);
        $this->userRepository->update($user, ["wallet" => $user->wallet - $order->use_wallet_price]);

        $cart = $this->cartRepository->getCartByOrderId($order->orderId);
        $this->cartRepository->changeStatus($cart, CartStatus::Completed->value);

        event(new OrderPaidEvent($order));

        return 1;
    }

    public function verifyOrderByWallet($userId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        $cartItems = $this->cartItemRepository->getItemsByCartId($cart->id);
        $this->checkoutService->finalCheckout($cart, $cartItems);
        $limit = $this->cartItemService->checkLimit($cartItems);
        $user = $this->userRepository->findOrFail($userId);
        $address = $this->addressRepository->findActiveByUserId($userId);
        $delivery = $this->deliveryRepository->findOrFail($cart->delivery_method);
        $cartPrices = $this->cartItemService->calculatePrice($cartItems);
        $totalItemsPrice = $cartPrices["totalItemPrice"];
        $maxDeliveryDelay = $cartPrices["maxDeliveryDelay"];
        $finalPrice = $totalItemsPrice + $delivery->price;
        if ($finalPrice > $user->wallet) {
            throw  new BadRequestHttpException("موجودی کیف پول شما برای ثبت این سفارش کافی نیست !");
        }
        $orderStatus = $limit ? OrderStatus::OnHold->value : OrderStatus::Unpaid->value;
        $orderInfo = $this->orderInfoRepository->createOrderInfo($user->name, $address->mobile, $address->tell, $address->province_id, $address->city_id, $address->address, $address->zip_code, $user->last_name, $user->national_code);
        $order = $this->orderRepository->createOrder($userId, $orderInfo->id, $totalItemsPrice, $delivery->price, $finalPrice, $orderStatus, 2, $cart->delivery_method, Carbon::now(), Carbon::now()->addDays($maxDeliveryDelay), "");
        $this->cartRepository->update($cart, ["order_id" => $order->id]);
        $this->cartItemService->convertCartItemToOrderItem($cartItems, $order->id);
        if ($limit) {
            $onHoldOrder = $this->onHoldOrderRepository->createOnHoldOrder($order->id);
            event(new OrderRequestEvent($onHoldOrder));
            return [
                "path" => "/thank_you_page",
                "type" => "limit"
            ];
        }
        $this->userRepository->update($user, ["wallet" => $user->wallet - $finalPrice]);

        $this->orderRepository->setStatus($order, OrderStatus::Paid->value);
        $orderItems = $this->orderItemRepository->getByOrderId($order->id);
        foreach ($orderItems as $item) {
            $this->stockRepository->decrement($item->product_color_id, $item->count);
        }
        $cart = $this->cartRepository->getCartByOrderId($order->orderId);
        $this->cartRepository->changeStatus($cart, CartStatus::Completed->value);
        event(new OrderPaidEvent($order));
        return [
            "path" => "/thank_you_page",
            "type" => "paid"
        ];
    }

    public function onHoldOrderVerifyByWallet($id, $userId)
    {
        $onHoldOrder = $this->onHoldOrderRepository->findOrFail($id);
        if (Carbon::parse($onHoldOrder->expire_date) < Carbon::now()) {
            throw new BreakException(\Lang::get("exceptions.expired_order"));
        }
        if ($onHoldOrder->status != OnHoldOrderStatus::Accept->value) {
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
        $finalPrice = $order->final_price;
        $user = $this->userRepository->findOrFail($userId);
        if ($finalPrice > $user->wallet) {
            throw  new BadRequestHttpException("موجودی کیف پول شما برای ثبت این سفارش کافی نیست !");
        }

        $this->userRepository->update($user, ["wallet" => $user->wallet - $finalPrice]);

        $this->orderRepository->setStatus($order, OrderStatus::Paid->value);
        $orderItems = $this->orderItemRepository->getByOrderId($order->id);
        foreach ($orderItems as $item) {
            $this->stockRepository->decrement($item->product_color_id, $item->count);
        }
        $cart = $this->cartRepository->getCartByOrderId($order->orderId);
        $this->cartRepository->changeStatus($cart, CartStatus::Completed->value);
        event(new OrderPaidEvent($order));
        return [
            "path" => "/thank_you_page",
            "type" => "paid"
        ];
    }
}
