"use client";


import React, {useMemo, useState} from "react";
import ButtonPrimary from "@/shared/Button/ButtonPrimary";
import ShippingAddress from "../../../components/Checkout/ShippingAddress";
import Image from "next/image";
import Link from "next/link";
import {useQuery} from "react-query";
import {decreaseCartItem, getCart, increaseCartItem, removeCartItem} from "@/services/api/shop/cart";
import {CartResponse} from "@/services/types/cart";
import {
    reduxDecrementQuantity,
    reduxIncrementQuantity, reduxRemoveFromCart,
    setCart,
    useCart,
    useUser
} from "@/services/globalState/GlobalState";
import {useRouter} from "next/navigation";
import {paymentByWallet, paymentRequest} from "@/services/api/shop/payment";
import {CheckIcon, NoSymbolIcon} from "@heroicons/react/24/outline";
import {Alert} from "@/shared/Alert/Alert";
import {GuarantyPrice} from "@/hooks/GuarantyPrice";
import {IoMdDownload} from "react-icons/io";
import Checkbox from "@/shared/Checkbox/Checkbox";
import {findActive} from "@/services/api/shop/address";
import CartController from "@/components/CartController/CartController";
import Prices from "@/components/Price/Prices";
import Badge from "@/shared/Badge/Badge";
import ContactInfo from "@/components/Checkout/ContactInfo";
import MySwitch from "@/shared/Switch/MySwitch";
import ShippingMethod from "@/components/Checkout/ShippingMethod";
import Input from "@/shared/Input/Input";
import Label from "@/shared/Label/Label";
import {check} from "@/services/api/shop/coupon";
import {CouponResponse} from "@/services/types/coupon";

const CheckoutPage = () => {
    const router = useRouter();
    const [cart] = useCart();
    const [user] = useUser();

    const [acceptRule, setAcceptRule] = useState(false);
    const [useWallet, setUseWallet] = useState(false);
    const [coupon, setCoupon] = useState<CouponResponse>();
    const [code, setCode] = useState("");
    const [shippingMethod, setShippingMethod] = useState(1);
    const [shippingPrice, setShippingPrice] = useState(0);

    // if (!user) {
    //     router.push("/login");
    // }
    const {data, isSuccess} = useQuery({
        queryKey: ['cart'],
        queryFn: () => getCart(),
        staleTime: 5000,
        enabled: !!user,
        onSuccess: (cartData) => {
            setCart(cartData);
        }
    });

    const {data: address} = useQuery({
        queryKey: ['address'],
        queryFn: () => findActive(),
        staleTime: 5000,

    });

    async function checkCode() {
        let response = await check(code);
        if (response) {
            setCoupon(response)
        }
    }


    async function payment() {
        let response = await paymentRequest(useWallet, shippingMethod, code);
        if (response.type == "payment")
            window.location.href = response.path;
        else if (response.type == "paid")
            router.push("/thank_you_page")
        else
            router.push("/thank_you_page/limit")
    }

    async function paymentWallet() {
        let response = await paymentByWallet();
        if (response.type == "paid")
            router.push("/thank_you_page")
        else
            router.push("/thank_you_page/limit")
    }

    async function increaseHandle(selectedColorId: number, guarantyId: number | undefined) {
        let response = await increaseCartItem({productColorId: selectedColorId, guaranty_id: guarantyId});
        if (response.success) {
            reduxIncrementQuantity(selectedColorId, guarantyId)
        }
    }

    async function decreaseHandle(selectedColorId: number, guarantyId: number | undefined) {
        let response = await decreaseCartItem({productColorId: selectedColorId, guaranty_id: guarantyId});
        if (response.success) {
            reduxDecrementQuantity(selectedColorId, guarantyId)
        }

    }

    async function removeHandle(selectedColorId: number, guarantyId: number | undefined) {
        let response = await removeCartItem({productColorId: selectedColorId, guaranty_id: guarantyId});
        if (response.success) {
            reduxRemoveFromCart(selectedColorId, guarantyId)
        }
    }

    const [tabActive, setTabActive] = useState<
        "ContactInfo" | "ShippingAddress" | "ShippingMethod" | "PaymentMethod"
    >("ContactInfo");

    const handleScrollToEl = (id: string) => {
        const element = document.getElementById(id);
        setTimeout(() => {
            element?.scrollIntoView({behavior: "smooth"});
        }, 80);
    };

    const renderStatusSoldout = () => {
        return (
            <div
                className="rounded-full flex items-center justify-center px-2.5 py-1.5 text-xs text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                <NoSymbolIcon className="w-3.5 h-3.5"/>
                <span className="mr-1 leading-none">ناموجود</span>
            </div>
        );
    };

    const renderStatusInstock = () => {
        return (
            <div
                className="rounded-full flex items-center justify-center px-2.5 py-1.5 text-xs text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                <CheckIcon className="w-3.5 h-3.5"/>
                <span className="mr-1 leading-none">  موجود</span>
            </div>
        );
    };

    const renderProduct = (item: CartResponse, index: number) => {
        let guarantyPrice = 0;
        if (item.guaranty && item.guaranty.free == 0) {
            guarantyPrice = GuarantyPrice(item.color.price) ?? 0;
        }
        return (
            <div key={index} className="relative flex py-7 first:pt-0 last:pb-0">
                <div className="relative h-24 w-24  flex-shrink-0 overflow-hidden rounded-xl bg-slate-100">
                    <Image
                        src={`${process.env.NEXT_PUBLIC_IMAGE_BASE_URL}/product/${item.product.image}`}
                        fill
                        alt={item.product.name}
                        className="h-full w-full object-contain object-center"
                        sizes="150px"
                    />
                    <Link href={{pathname: "product/" + item.product.url}} className="absolute inset-0"></Link>
                </div>

                <div className="mr-3 sm:ml-6 flex flex-1 flex-col">
                    <div>
                        <div className="flex justify-between gap-1 flex-col ">
                            <div className="flex-[1.5] ">
                                <h3 className="text-xs md:text-sm font-semibold">
                                    <Link href={{pathname: "product/" + item.product.url}}>{item.product.name}</Link>
                                </h3>
                                <div className="mt-1.5 sm:mt-2.5 flex text-sm text-slate-600 dark:text-slate-300">
                                    <div className="flex items-center gap-x-1.5">
                                        <span>{item.color.title}</span>
                                    </div>
                                    <span className="mx-4 border-l border-slate-200 dark:border-slate-700 "></span>

                                </div>

                            </div>
                            <div className="flex items-center gap-x-1">
                                <span className="text-xs text-slate-500 dark:text-slate-400">
                                    {item.guaranty.name}
                                </span>
                                {
                                    (item.guaranty.free == null || item.guaranty.free) ?
                                        <span className="text-xs text-slate-500 dark:text-slate-400">
                                            (رایگان)
                                        </span>
                                        :
                                        <span
                                            className={`text-xs text-slate-500 dark:text-slate-400`}>{new Intl.NumberFormat('en-US').format(GuarantyPrice(item.color.price))} تومان </span>
                                }
                            </div>
                            <div className="flex flex-1 sm:flex justify-end">
                                <Prices price={item.color.discountedPrice ?? item.color.price} className="mt-0.5"/>
                            </div>
                        </div>

                    </div>

                    <div className="flex mt-auto pt-4 items-start justify-between text-sm flex-col sm:flex-row gap-1">
                        <div className=" sm:block text-center relative">
                            {/*<CartController className="relative z-10"*/}
                            {/*                defaultValue={item.count}*/}
                            {/*                increaseHandle={() => {*/}
                            {/*                    increaseHandle(item.color.id as number, item.guaranty.id as number)*/}
                            {/*                }}*/}
                            {/*                decreaseHandel={() => {*/}
                            {/*                    decreaseHandle(item.color.id as number, item.guaranty.id as number)*/}
                            {/*                }}*/}
                            {/*                removeHandle={() => {*/}
                            {/*                    removeHandle(item.color.id as number, item.guaranty.id as number)*/}
                            {/*                }}*/}
                            {/*/>*/}
                        </div>
                        {!item.hasStock
                            ? renderStatusSoldout()
                            : renderStatusInstock()}
                    </div>
                </div>
            </div>
        );
    };

    const renderLeft = () => {
        return (
            <div className="space-y-8">
                <div id="ShippingAddress" className="scroll-mt-24">
                    <ShippingAddress
                        isActive={tabActive === "ShippingAddress"}
                        onOpenActive={() => {
                            setTabActive("ShippingAddress");
                            handleScrollToEl("ShippingAddress");
                        }}
                        onCloseActive={() => {
                            setTabActive("ShippingMethod");
                            handleScrollToEl("ShippingMethod");
                        }}
                    />
                    <ShippingMethod
                        setShippingMethod={setShippingMethod}
                        setShippingPrice={setShippingPrice}
                        shippingMethod={shippingMethod}
                        isActive={tabActive === "ShippingMethod"}
                        onOpenActive={() => {
                            setTabActive("ShippingMethod");
                            handleScrollToEl("ShippingMethod");
                        }}
                        onCloseActive={() => {
                            setTabActive("ShippingAddress");
                            handleScrollToEl("ShippingAddress");
                        }}
                    />
                </div>
                {user && (user.name == null || user.last_name == null || user.national_code == null) &&
                    <div id="ContactInfo" className="scroll-mt-24">
                        <ContactInfo
                            isActive={tabActive === "ContactInfo"}
                            onOpenActive={() => {
                                setTabActive("ContactInfo");
                                handleScrollToEl("ContactInfo");
                            }}
                            onCloseActive={() => {
                                setTabActive("PaymentMethod");
                                handleScrollToEl("PaymentMethod");
                            }}
                        />
                    </div>}
                <div className={"border rounded-2xl flex flex-col w-full gap-5 p-5 bg-slate-100 dark:bg-black/20"}>
                    <div>
                        <strong className={"text-sm sm:text-base"}>
                            شرایط ارسال کالا
                        </strong>
                    </div>
                    <div>
                        <p className={"text-xs sm:text-sm  text-slate-800 dark:text-white"}>
                            ۱ . نحوه ارسال کالا پس از پرداخت صورتحساب با هماهنگی و بنا به درخواست خریدار از طریق باربری،
                            تیپاکس، پست، اسنپ و یا تحویل حضوری درب انبار تجهیزلند امکان‌پذیر می‌باشد
                        </p>
                    </div>
                    <div>
                        <p className={"text-xs sm:text-sm text-slate-800 dark:text-white"}>
                            ۲ . هزینه ارسال کالا در استان تهران و حومه توسط کارشناسان تجهیزلند پس از پرداخت صورت حساب
                            کالا به خریدار اعلام می‌گردد و در کلیه شهرستان‌ها به صورت پس کرایه می‌باشد.
                        </p>
                    </div>
                </div>
                {address &&
                    <div className={"border rounded-2xl flex flex-col w-full gap-5 p-5 bg-slate-100 dark:bg-black/20"}>
                        <div>
                            <p className={"text-xs sm:text-sm  text-slate-800 dark:text-white"}>
                                سفارش شما پس از پرداخت در بازه زمانی
                                {" "}
                                {renderMaxDeliveryDelay()}
                                {" "}
                                روز کاری ، با توجه به شرایط ارسال اعلامی به ادرس
                                {" "}
                                {address?.province?.name} ,
                                {" "}
                                {address?.city?.name} ,
                                {" "}
                                {address?.address}
                                {" "}
                                به کد پستی
                                {" "}
                                {address?.zip_code}
                                {" "}
                                ارسال خواد شد.
                            </p>
                        </div>
                    </div>}
            </div>
        );
    };

    const renderSumPrice = () => {
        let sumPrice: number = 0;
        cart?.map((item) => {
            sumPrice += Number(item.color.price * item.count);
        })
        return sumPrice;
    }
    const renderDiscount = () => {
        let sumDiscount: number = 0;
        cart.map((item) => {
            if (item.color.discountedPrice > 0)
                sumDiscount += Number((item.color.price - item.color.discountedPrice) * item.count);
        })
        return sumDiscount;
    }
    const renderDiscountedPrice = () => {

        return sumPrice - sumDiscount + sumGuarantyPrice;
    }
    const renderCouponDiscount = () => {
        if (!coupon)
            return 0;
        let discountAmount = 0;

        if (coupon.price > 0) {
            // تخفیف ثابت: مستقیماً مبلغ price کم می‌شه
            discountAmount = coupon.price;
        } else if (coupon.percent > 0) {
            // تخفیف درصدی: درصد از مجموع سبد خرید
            discountAmount = (coupon.percent / 100) * sumDiscountedPrice;
        }

        return discountAmount;
    }
    const renderAllow = () => {
        let allow: boolean = true;
        cart.map((item) => {
            if (item.color.status == 0 || !item.hasStock) {
                allow = false
            }
        })
        return allow;
    }
    const renderLimit = () => {
        let limit: boolean = false;
        cart.map((item) => {
            if (item.color.status == 2) {
                limit = true
            }
        })
        return limit;
    }
    const renderMaxDeliveryDelay = () => {
        let maxDeliveryDelay: number = 0;
        cart.map((item) => {
            if (item.color.delivery_delay > maxDeliveryDelay)
                maxDeliveryDelay = item.color.delivery_delay
        })
        return maxDeliveryDelay;
    }

    const renderSumGuarantyPrice = () => {
        let sumPrice: number = 0;
        cart.map((item) => {
            if (item.guaranty && item.guaranty.free == 0) {
                sumPrice +=((GuarantyPrice(item.color.price) ?? 0 )* item.count);
            }
        })
        return sumPrice;
    }

    const sumPrice = useMemo(() => renderSumPrice(), [cart]);
    const allow = useMemo(() => renderAllow(), [cart]);
    const limit = useMemo(() => renderLimit(), [cart]);
    const sumGuarantyPrice = useMemo(() => renderSumGuarantyPrice(), [cart, renderSumGuarantyPrice]);
    const sumDiscount = useMemo(() => renderDiscount(), [cart]);
    const sumDiscountedPrice = useMemo(() => renderDiscountedPrice(), [cart]);
    const couponDiscount = useMemo(() => renderCouponDiscount(), [coupon, cart]);
    const maxDeliveryDelay = useMemo(() => renderMaxDeliveryDelay(), [cart]);

    return (
        <div className="nc-CheckoutPage  dark:text-white dark:bg-slate-900">
            {/*<head>*/}
            {/*    <title>پرداخت</title>*/}
            {/*</head>*/}

            <main className="container py-16 lg:pb-28 lg:pt-20 ">
                <div className="mb-16">
                    <h2 className="block text-2xl sm:text-3xl lg:text-4xl font-semibold ">
                        پرداخت
                    </h2>
                    <div
                        className="block mt-3 sm:mt-5 text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-400">
                        <Link href={"/"} className="">
                            صفحه اصلی
                        </Link>
                        <span className="text-xs mx-1 sm:mx-1.5">/</span>
                        <Link href={"/cart"} className="">
                            سبد خرید
                        </Link>
                        <span className="text-xs mx-1 sm:mx-1.5">/</span>
                        <span className="underline">پرداخت</span>
                    </div>
                </div>

                <div className="flex flex-col lg:flex-row">
                    <div className="flex-1">{renderLeft()}</div>

                    <div
                        className="flex-shrink-0 border-t lg:border-t-0 lg:border-l border-slate-200 dark:border-slate-700 my-10 lg:my-0 lg:mx-10 xl:lg:mx-14 2xl:mx-16 "></div>

                    <div className="w-full lg:w-[36%] ">
                        <h3 className="text-lg font-semibold">مشخصات سفارش</h3>
                        <div className="mt-8 divide-y divide-slate-200/70 dark:divide-slate-700 ">
                            {cart && cart.map(renderProduct)}
                        </div>

                        <div
                            className="mt-10 pt-6 text-sm text-slate-500 dark:text-slate-400 border-t border-slate-200/70 dark:border-slate-700 ">
    <div>
                                <Label className="text-sm">کد تخفیف</Label>
                                <div className="flex mt-1.5">
                                    <Input sizeClass="h-10 px-4 py-3" className="flex-1" value={code} onChange={(e) => {
                                        setCode(e.target.value)
                                    }}/>
                                    <button
                                        onClick={checkCode}
                                        className="text-neutral-700 dark:text-neutral-200 border border-neutral-200 dark:border-neutral-700 hover:bg-neutral-100 rounded-2xl px-4 ml-3 font-medium text-sm bg-neutral-200/70 dark:bg-neutral-700 dark:hover:bg-neutral-800 w-24 flex justify-center items-center transition-colors">
                                        اعمال
                                    </button>
                                </div>
                                {(coupon && couponDiscount > 0) && <div className={" mt-1.5"}>
                                    <Alert type={"success"}>
                                        کد تخفیف اعمال شد و مبلغ {couponDiscount} از سفارش شما کم شد
                                    </Alert>
                                </div>}

                            </div>
                            
                            <div className="mt-4  flex justify-end py-2.5">
                                {
                                    allow &&
                                    <Link href={"/checkout/prefactor"}
                                          className={"flex gap-2 items-center  "}>
                                        <IoMdDownload/>
                                        <span>
                                        دریافت پیش فاکتور
                                        </span>
                                    </Link>
                                }
                            </div>
                            <div className="mt-4  flex justify-between py-2.5">
                                <span>نام  </span>
                                <span className="font-semibold text-slate-900 dark:text-slate-200">
                                    {user?.name}
                                </span>
                            </div>
                            <div className="flex justify-between py-2.5">
                                <span>نام خانوادگی</span>
                                <span className="font-semibold text-slate-900 dark:text-slate-200">
                                    {user?.last_name}
                                </span>
                            </div>

                            <div className="flex justify-between py-2.5">
                                <span>زمان آماده سازی</span>
                                <span className="font-semibold text-slate-900 dark:text-slate-200">
                                    {maxDeliveryDelay} روز
                                </span>
                            </div>

                            <div className="flex justify-between py-2.5">
                                <span>محصولات</span>
                                <span className="font-semibold text-slate-900 dark:text-slate-200">
                                    {sumPrice.toLocaleString()} تومان
                                </span>
                            </div>
    <div className="flex justify-between py-2.5">
                                <span> هزینه ارسال  </span>
                                <span className="font-semibold text-slate-900 dark:text-slate-200">
                                    {shippingPrice == 0 ? "(هزینه ارسال با مشتری)" : shippingPrice.toLocaleString() + " تومان " }
                                </span>
                            </div>

                            <div className="flex justify-between py-2.5">
                                <span> تخفیف  </span>
                                <span className="font-semibold text-slate-900 dark:text-slate-200">
                                    {sumDiscount.toLocaleString()} تومان
                                </span>
                            </div>
                            <div className="flex justify-between py-4">
                                <span>مجموع قیمت گارانتی</span>
                                <span className="font-semibold text-slate-900 dark:text-slate-200">
                                        {sumGuarantyPrice.toLocaleString()} تومان
                                    </span>
                            </div>
                            <div
                                className="flex justify-between font-semibold text-slate-900 dark:text-slate-200 text-base pt-4">
                                <span> مجموع  </span>
                                <span>
                                    {sumDiscountedPrice.toLocaleString()} تومان
                                </span>
                            </div>
                            <div
                                className="flex justify-between font-semibold text-slate-900 dark:text-slate-200 text-base pt-4">
                                <div className={"flex items-center gap-1"}>
                                    موجودی کیف پول
                                    <Link className={"text-green-600 text-xs"} href={"/account-wallet"}>
                                        (
                                        افزایش موجودی
                                        )
                                    </Link>
                                </div>
                                <span>
                                    {(user?.wallet ?? 0).toLocaleString()} تومان
                                </span>
                            </div>
                            <hr className={"mt-4"}/>


                            <div
                                className="flex justify-between font-semibold text-slate-900 dark:text-slate-200 text-base pt-4">
                                <div className={"flex items-center gap-1"}>
                                    استفاده از موجودی کیف پول
                                </div>
                                <span>
                                      <MySwitch
                                          label=" "
                                          desc=" "
                                          enabled={!useWallet}
                                          onChange={() => {
                                              setUseWallet(!useWallet)
                                          }}
                                      />
                                </span>
                            </div>
                            {
                                useWallet ?
                                    <>
                                        <div>
                                            مبلغ
                                            {" "}
                                            {(user?.wallet ?? 0).toLocaleString()} تومان
                                            از سفارش شما کسر میگردد
                                        </div>
                                        <div>
                                            مبلغ قابل پرداخت :
                                            {
                                                (
                                                    (sumDiscountedPrice - couponDiscount - (user?.wallet ?? 0)) > 0 ?
                                                        (sumDiscountedPrice - couponDiscount - (user?.wallet ?? 0))
                                                        :
                                                        0
                                                ).toLocaleString()} تومان
                                        </div>
                                    </>
                                    :
                                    couponDiscount > 0 ?
                                        <div >
                                            مبلغ قابل پرداخت :
                                            {
                                                (
                                                    (sumDiscountedPrice - couponDiscount) > 0 ?
                                                        (sumDiscountedPrice - couponDiscount)
                                                        :
                                                        0
                                                ).toLocaleString()} تومان
                                        </div>
                                        : null
                            }
                        </div>
                        <ButtonPrimary className="mt-8 w-full" onClick={payment}
                                       disabled={!allow || !acceptRule || sumDiscountedPrice <= 0 ||
                                           (sumDiscountedPrice > 200000000 && !useWallet)
                                       }>پرداخت</ButtonPrimary>

                        {/*<ButtonPrimary className="mt-4 w-full" onClick={paymentWallet}*/}
                        {/*               disabled={sumDiscountedPrice > (user?.wallet ?? 0) || !allow || !acceptRule || sumDiscountedPrice <= 0}>*/}
                        {/*پرداخت با کیف پول*/}
                        {/*</ButtonPrimary>*/}

                        {
                            (sumDiscountedPrice > 200000000)
                                ? <strong className={"text-green-600 font-bold text-sm"}>
                                    برای سفارش‌های با مبلغ بیش از ۲۰۰ میلیون تومان، به دلیل محدودیت سقف درگاه بانکی، امکان
                                    پرداخت مستقیم از طریق بانک وجود ندارد. لطفاً کیف پول خود را در هر نوبت تا سقف ۲۰۰ میلیون
                                    تومان شارژ کنید و سپس پرداخت را با استفاده از موجودی کیف پول انجام دهید.
                                </strong>
                                :
                                ""
                        }
                        <div className={"flex items-center gap-2 mt-5 justify-center"}>
                            <Checkbox name={"rule"} onChange={() => {
                                setAcceptRule(!acceptRule)
                            }}/>
                            <p>با
                                {" "}
                                <Link className={"text-[#fcb415] font-bold"} href={"/page/rule"}>
                                    قوانین
                                </Link>
                                {" "}
                                سایت موافقم</p>
                        </div>
                        <div className={"flex justify-center mt-5"}>
                            {!acceptRule &&
                                <Badge color={"red"} name={" برای ثبت سفارش ابتدا باید با قوانین سایت موافقت کنید"}>
                                </Badge>}
                        </div>
                        {
                            !allow &&
                            <Alert containerClassName={"justify-center mt-4"} type={"error"}>محصول غیرفعال یا ناموجود در
                                سبد خرید موجود میباشد </Alert>
                        } {
                        limit &&
                        <Alert containerClassName={" justify-center mt-4"} type={"warning"}>محصول محدود کننده در سبد
                            خرید موجود میباشد . پس از تایید مدیریت امکان پرداخت وجود دارد </Alert>
                    }

                    </div>
                </div>
            </main>
        </div>
    );
};

export default CheckoutPage;
