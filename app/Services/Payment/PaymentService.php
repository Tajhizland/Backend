<?php

namespace App\Services\Payment;

use App\Enums\CartStatus;
use App\Enums\OnHoldOrderStatus;
use App\Enums\OrderStatus;
use App\Events\OrderPaidEvent;
use App\Events\OrderPaymentRequestEvent;
use App\Events\OrderRequestEvent;
use App\Exceptions\BreakException;
use App\Models\Order;
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
use App\Services\Checkout\ShippingMethodResolver;
use App\Services\Coupon\CouponServiceInterface;
use App\Services\DigiPay\DigiPayService;
use App\Services\OnHoldOrder\OnHoldOrderServiceInterface;
use App\Services\Payment\Gateways\Strategy\GatewayStrategyServicesInterface;
use App\Services\SnappPay\SnappPayService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Models\Gateway;

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
        private SnappPayService                  $snappPayService,
        private OnHoldOrderServiceInterface      $onHoldOrderService,
        private ShippingMethodResolver           $shippingMethodResolver,
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
        $cartPrices = $this->cartItemService->calculatePrice($cartItems, $gateway == 3);
        $extraPrice = $cartPrices["extraPrice"];
        $totalItemsPrice = $cartPrices["totalItemPrice"];
        $maxDeliveryDelay = $cartPrices["maxDeliveryDelay"];
        $finalPrice = $totalItemsPrice + $shippingPrice;
        $finalExtraPrice = $extraPrice + $shippingPrice;
        $coupon = null;
        $off = 0;
        $extraPriceOff = 0;
        if ($code != null) {
            $coupon = $this->couponService->check($code, $userId);
            if ($coupon) {
                if ($coupon->price) {
                    $off = $coupon->price;
                } elseif ($coupon->percent) {
                    $off = $finalPrice * $coupon->percent / 100;
                    $extraPriceOff = $finalExtraPrice * $coupon->percent / 100;
                }
            }
        }
        $finalPrice = $finalPrice - $off;
        $finalExtraPrice = $finalExtraPrice - $extraPriceOff;
        if (!$useWallet) {

            $orderStatus = $limit ? OrderStatus::OnHold->value : OrderStatus::Unpaid->value;
            $orderInfo = $this->orderInfoRepository->createOrderInfo($user->name, $address->mobile, $address->tell, $address->province_id, $address->city_id, $address->address, $address->zip_code, $user->last_name, $user->national_code);
            $order = $this->orderRepository->createOrder($userId, $orderInfo->id, $totalItemsPrice, $shippingPrice, $finalPrice, $orderStatus, $gateway, $shippingMethod, Carbon::now(), Carbon::now()->addDays($maxDeliveryDelay), "", $finalPrice, 0, $off);
            if ($coupon) {
                $this->couponUserRepository->create(["order_id" => $order->id, "user_id" => $userId, "coupon_id" => $coupon->id]);
            }
            $this->cartRepository->update($cart, ["order_id" => $order->id]);
            $this->cartItemService->convertCartItemToOrderItem($cartItems, $order->id, $gateway == 3);
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
                //    $gatewayObject=Gateway::find(3);
                //    if ($gatewayObject->extra_price > 0) {
                //        $percentage = $gatewayObject->extra_price;
                //        $finalPrice = $finalPrice * (1 + ($percentage / 100));
                //        $finalPrice = round($finalPrice);
                //    }
                $orderItems = $this->orderItemRepository->getByOrderId($order->id);
                $path = $this->digiPayService->request($finalExtraPrice * 10, $address->mobile, $order->id, $orderItems);
            } else if ($gateway == 4) {

                $orderItems = $this->orderItemRepository->getByOrderId($order->id);
                $path = $this->snappPayService->request($order->id, $orderItems, $finalPrice * 10);
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
            $order = $this->orderRepository->createOrder($userId, $orderInfo->id, $totalItemsPrice, $delivery->price, 0, $orderStatus, 2, $cart->delivery_method, Carbon::now(), Carbon::now()->addDays($maxDeliveryDelay), "", $finalPrice, $finalPrice, $off);
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


    /**
     * پرداخت یک سفارش معلقِ تاییدشده از صفحه‌ی چک‌اوت اختصاصی‌اش.
     *
     * برخلاف request() اینجا سفارش از قبل وجود دارد و اقلامش قفل است؛ فقط آدرس،
     * روش ارسال، کد تخفیف، کیف پول و درگاه دوباره اعمال و روی همان سفارش ذخیره می‌شوند.
     * قیمت اقلام از مقادیر فریزشده‌ی order_item خوانده می‌شود تا مبلغِ تاییدشده تغییر نکند.
     */
    public function onHoldOrderCheckoutPayment($id, $userId, $useWallet, $shippingMethod, $code = null, $gateway = 1)
    {
        $onHoldOrder = $this->onHoldOrderRepository->findOrFail($id);
        $this->onHoldOrderService->assertPayable($onHoldOrder, $userId);

        $order = $this->orderRepository->findOrFail($onHoldOrder->order_id);
        $orderItems = $this->orderItemRepository->getByOrderId($order->id);
        if ($orderItems->isEmpty()) {
            throw new BreakException(\Lang::get("exceptions.unavailable_product_in_cart"));
        }
        // همان اعتبارسنجی چک‌اوت (فعال بودن محصول/رنگ و کافی بودن موجودی)، اما روی اقلام سفارش
        $this->cartItemService->checkAllow($orderItems);

        $user = $this->userRepository->findOrFail($userId);
        $address = $this->addressRepository->findActiveByUserId($userId);
        if (!$address || !$address->city_id || !$address->province_id || !$address->mobile || !$address->address) {
            throw new BreakException(\Lang::get("exceptions.address_not_find"));
        }
        // دیجی‌پی و اسنپ‌پی با کیف پول ترکیب نمی‌شوند
        if ($gateway == 3 || $gateway == 4) {
            $useWallet = false;
        }

        $prices = $this->onHoldOrderItemsPrice($orderItems);
        $totalItemsPrice = $prices["totalItemPrice"];

        // هزینه‌ی ارسال سمت سرور دوباره حساب می‌شود؛ مقدار ارسالی کلاینت ملاک نیست
        $deliveries = $this->shippingMethodResolver->resolve($orderItems, $address, $totalItemsPrice);
        $delivery = null;
        foreach ($deliveries as $item) {
            if ($item->id == $shippingMethod) {
                $delivery = $item;
                break;
            }
        }
        if (!$delivery) {
            throw new BreakException(\Lang::get("exceptions.delivery_not_find"));
        }
        $shippingPrice = max(0, (int)$delivery->price);

        $finalPrice = $totalItemsPrice + $shippingPrice;
        $finalExtraPrice = $prices["extraPrice"] + $shippingPrice;

        $coupon = null;
        $off = 0;
        $extraPriceOff = 0;
        if ($code != null) {
            $coupon = $this->couponService->check($code, $userId, $totalItemsPrice);
            if ($coupon) {
                if ($coupon->price) {
                    $off = $coupon->price;
                } elseif ($coupon->percent) {
                    $off = $finalPrice * $coupon->percent / 100;
                    $extraPriceOff = $finalExtraPrice * $coupon->percent / 100;
                }
            }
        }
        $finalPrice = max(0, (int)round($finalPrice - $off));
        $finalExtraPrice = max(0, (int)round($finalExtraPrice - $extraPriceOff));
        $off = (int)round($off);

        // اطلاعات گیرنده با آدرس فعالِ فعلی به‌روز می‌شود
        $orderInfo = $this->orderInfoRepository->findOrFail($order->order_info_id);
        $this->orderInfoRepository->update($orderInfo, [
            "name" => $user->name,
            "last_name" => $user->last_name,
            "national_code" => $user->national_code,
            "mobile" => $address->mobile,
            "tell" => $address->tell,
            "province_id" => $address->province_id,
            "city_id" => $address->city_id,
            "address" => $address->address,
            "zip_code" => $address->zip_code,
        ]);

        // کد تخفیفِ تلاش قبلی (اگر بوده) جای خود را به انتخاب فعلی می‌دهد
        $this->couponUserRepository->deleteByOrderId($order->id);
        if ($coupon) {
            $this->couponUserRepository->create([
                "order_id" => $order->id,
                "user_id" => $userId,
                "coupon_id" => $coupon->id,
            ]);
        }

        // کل مبلغ با موجودی کیف پول پوشش داده می‌شود
        if ($useWallet && $finalPrice <= $user->wallet) {
            $this->orderRepository->update($order, [
                "price" => $totalItemsPrice,
                "delivery_price" => $shippingPrice,
                "delivery_method" => $shippingMethod,
                "payment_method" => 2,
                "off" => $off,
                "total_price" => $finalPrice,
                "use_wallet_price" => $finalPrice,
                "final_price" => 0,
            ]);
            $this->userRepository->update($user, ["wallet" => $user->wallet - $finalPrice]);
            $this->orderRepository->setStatus($order, OrderStatus::Paid->value);
            foreach ($orderItems as $item) {
                $this->stockRepository->decrement($item->product_color_id, $item->count);
            }
            $cart = $this->cartRepository->getCartByOrderId($order->id);
            if ($cart) {
                $this->cartRepository->changeStatus($cart, CartStatus::Completed->value);
            }
            event(new OrderPaidEvent($order));
            return [
                "path" => "/thank_you_page",
                "type" => "paid",
            ];
        }

        // مبلغی که در نهایت از درگاه گرفته می‌شود (دیجی‌پی مبلغ بدون تخفیف + کارمزد را می‌گیرد)
        $chargeAmount = $gateway == 3 ? $finalExtraPrice : $finalPrice;
        $useWalletPrice = $useWallet ? min($user->wallet, $chargeAmount) : 0;
        $payablePrice = $chargeAmount - $useWalletPrice;

        $this->orderRepository->update($order, [
            "price" => $totalItemsPrice,
            "delivery_price" => $shippingPrice,
            "delivery_method" => $shippingMethod,
            "payment_method" => $gateway,
            "off" => $off,
            "total_price" => $chargeAmount,
            "use_wallet_price" => $useWalletPrice,
            "final_price" => $payablePrice,
        ]);
        event(new OrderPaymentRequestEvent($order));

        if ($gateway == 3) {
            $path = $this->digiPayService->request($payablePrice * 10, $address->mobile, $order->id, $orderItems);
        } elseif ($gateway == 4) {
            $path = $this->snappPayService->request($order->id, $orderItems, $payablePrice * 10);
        } else {
            $path = $this->gatewayService->request($payablePrice * 10, $order->id);
        }

        return [
            "path" => $path,
            "type" => "payment",
        ];
    }

    /**
     * مبلغ اقلام یک سفارش از روی قیمت‌های فریزشده‌ی order_item.
     *
     * - totalItemPrice : مبلغ عادی (final_price هر واحد شامل تخفیف و قیمت گارانتی است)
     * - extraPrice     : مبلغ دیجی‌پی؛ تخفیف محصول اعمال نمی‌شود و درصد کارمزد اضافه می‌گردد
     */
    private function onHoldOrderItemsPrice($orderItems): array
    {
        $totalItemPrice = 0;
        $extraPrice = 0;
        foreach ($orderItems as $item) {
            $totalItemPrice += $item->final_price * $item->count;

            $withoutDiscount = ($item->final_price + $item->discount) * $item->count;
            $percent = $item->product?->digipay_extra_price ?? 0;
            $extraPrice += round($withoutDiscount + ($withoutDiscount * $percent / 100));
        }
        return [
            "totalItemPrice" => $totalItemPrice,
            "extraPrice" => $extraPrice,
        ];
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

    public function snappPayEligible($amount)
    {
        // مبلغ از فرانت به تومان می‌آید و اسنپ‌پی مبلغ را به ریال می‌خواهد (× ۱۰)
        return $this->snappPayService->eligible($amount * 10);
    }

    public function verifyPaymentSnapppay($request)
    {
        try {
            DB::beginTransaction();

            $request = $this->snappPayService->callbackParams($request);

            $order = $this->orderRepository->findOrFail($request->orderId);

            $verify = $this->snappPayService->verify($order->payment_token);
            if ($verify["successful"] != true) {
                throw new BreakException("پرداخت ناموفق بود");
            }

            $TransactionReferenceID = $verify["response"]["transactionId"];
            $this->snappPayService->settle($order->payment_token);

            $this->orderRepository->setStatus($order, OrderStatus::Paid->value);

            $orderItems = $this->orderItemRepository->getByOrderId($order->id);
            foreach ($orderItems as $item) {
                $this->stockRepository->decrement($item->product_color_id, $item->count);
            }
            $this->transactionRepository->createTransaction($order->user_id, $order->id, $TransactionReferenceID, $order->final_price);

            $cart = $this->cartRepository->getCartByOrderId($order->orderId);
            $this->cartRepository->changeStatus($cart, CartStatus::Completed->value);

            DB::commit();
            event(new OrderPaidEvent($order));

            return $request->orderId;

        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }
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
