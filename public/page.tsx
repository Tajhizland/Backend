"use client";
import Breadcrump from "@/components/Breadcrumb/Breadcrump";
import Panel from "@/shared/Panel/Panel";
import {
    groupChangeDigipay,
    groupChangePrice,
    groupChangeStatus,
    groupChangeStock,
    searchProductList
} from "@/services/api/admin/product";
import React, {useState} from "react";
import {useRouter} from "next/navigation";
import {QueryClient, useMutation, useQuery} from "react-query";
import {categoryList} from "@/services/api/admin/category";
import {brandList} from "@/services/api/admin/brand";
import Select from "@/shared/Select/Select";
import ButtonPrimary from "@/shared/Button/ButtonPrimary";
import {ProductResponse} from "@/services/types/product";
import Prices from "@/components/Price/Prices";
import Image from "next/image";
import Input from "@/shared/Input/Input";
import Label from "@/shared/Label/Label";
import toast from "react-hot-toast";
import MultiSelect from "@/shared/Select/MultiSelect";
import SearchableSelect from "@/shared/Select/SearchableSelect";

export default function Page() {
    const queryClient = new QueryClient();
    const [category, setCategory] = useState<number>();
    const [brand, setBrand] = useState<number>();
    const [action, setAction] = useState<string>("inc");
    const [stock, setStock] = useState<number>();
    const [status, setStatus] = useState<number>(1);
    const [digipay, setDigipay] = useState<number>(1);
    const [response, setResponse] = useState<ProductResponse[]>([]);
    const [selectedProducts, setSelectedProducts] = useState<Record<number, boolean>>({});

    const router = useRouter();

    // Mutation برای جستجو
    const searchMutation = useMutation({
        mutationKey: [`search-product-list`],
        mutationFn: async () => {
            return searchProductList({
                brandId: Number(brand),
                categoryId: Number(category)
            });
        },
        onSuccess: (res) => {
            setResponse(res);
            // ✅ وقتی داده‌ها لود شدن، همه رو تیک بزن
            const initialSelection: Record<number, boolean> = {};
            res.forEach((item: ProductResponse) => {
                initialSelection[item.id] = true;
            });
            setSelectedProducts(initialSelection);
        },
    });
    // Mutation برای جستجو
    const actionStatusMutation = useMutation({
        mutationKey: [`product-group-status`],
        mutationFn: async () => {
            const ids = Object.keys(selectedProducts)
                .filter((id) => selectedProducts[Number(id)])
                .map((id) => Number(id));
            return groupChangeStatus({
                ids: ids,
                status: Number(status)
            });
        },
        onSuccess: async (res) => {
            await searchMutation.mutateAsync();
            toast.success(res.message as string);
        },
    });
    const actionDigipayMutation = useMutation({
        mutationKey: [`product-group-digipay`],
        mutationFn: async () => {
            const ids = Object.keys(selectedProducts)
                .filter((id) => selectedProducts[Number(id)])
                .map((id) => Number(id));
            return groupChangeDigipay({
                ids: ids,
                digipay: Number(digipay)
            });
        },
        onSuccess: async (res) => {
            await searchMutation.mutateAsync();
            toast.success(res.message as string);
        },
    });
    const actionStockMutation = useMutation({
        mutationKey: [`product-group-stock`],
        mutationFn: async () => {
            const ids = Object.keys(selectedProducts)
                .filter((id) => selectedProducts[Number(id)])
                .map((id) => Number(id));
            return groupChangeStock({
                ids: ids,
                stock: Number(stock)
            });
        },
        onSuccess: async (res) => {
            await searchMutation.mutateAsync();
            toast.success(res.message as string);
        },
    });

    // لیست دسته‌ها
    const {data: categoryLists} = useQuery({
        queryKey: [`category-list`],
        queryFn: () => categoryList(),
        staleTime: 5000,
    });

    // لیست برندها
    const {data: brandLists} = useQuery({
        queryKey: [`brand-list`],
        queryFn: () => brandList(),
        staleTime: 5000,
    });

    // ✅ تابع تغییر وضعیت چک‌باکس
    const handleCheckboxChange = (id: number) => {
        setSelectedProducts((prev) => ({
            ...prev,
            [id]: !prev[id],
        }));
    };
    const options = categoryLists?.data.map((item) => ({
        value: item.id,
        label: item.name,
    }));

    return (
        <>
            <Breadcrump
                breadcrumb={[
                    {title: "محصولات", href: "product"},
                    {title: "تغییر گروهی وضعیت", href: "product/group-stock"},
                ]}
            />
            <Panel>
                <div className={"flex flex-col w-full gap-5"}>
                    <SearchableSelect
                        options={[
                            {
                                label: 'همه',
                                value: ''
                            },
                            ...(categoryLists?.data?.map((item) => ({
                                label: item.name,
                                value: item.id.toString(),
                            })) ?? []),
                        ]}
                        //@ts-ignore
                        value={category}
                        onChange={(e) => setCategory(Number(e))}
                    />
                    <SearchableSelect
                        options={[
                            {
                                label: 'همه',
                                value: ''
                            },
                            ...(brandLists?.data?.map((item) => ({
                                label: item.name,
                                value: item.id.toString(),
                            })) ?? []),
                        ]}
                        //@ts-ignore
                        value={brand}
                        onChange={(e) => setBrand(Number(e))}
                    />

                    <ButtonPrimary loading={searchMutation.isLoading} onClick={searchMutation.mutateAsync}>
                        جستجو
                    </ButtonPrimary>
                </div>
            </Panel>

            <Panel>
                <div className={"flex flex-col gap-5"}>
                    <div>
                        <Label>
                            نوع عملیات
                        </Label>
                        <Select
                            onChange={(e) => {
                                setAction(e.target.value)
                            }}
                        >
                            <option value={""}>انتخاب کنید</option>
                            <option value={"stock"}>ویرایش موجودی</option>
                            <option value={"status"}>ویرایش وضعیت</option>
                            <option value={"digipay"}>ویرایش دیجی پی</option>
                        </Select>
                    </div>
                    <hr/>
                    {
                        action == "stock"
                        &&
                        <div className={"flex flex-col gap-2"}>
                            <Label>
                                موجودی
                            </Label>
                            <Input
                                value={stock}
                                onChange={(e) => {
                                    setStock(Number(e.target.value))
                                }}
                            />

                            <ButtonPrimary loading={actionStockMutation.isLoading} onClick={actionStockMutation.mutateAsync}>
                                اعمال
                            </ButtonPrimary>
                        </div>
                    } {
                    action == "status"
                    &&
                    <div className={"flex flex-col gap-2"}>
                        <Label>
                            وضعیت
                        </Label>
                        <Select onChange={(e) => {
                            setStatus(Number(e.target.value))
                        }}>
                            <option value={1} selected={status == 1}>فعال</option>
                            <option value={0} selected={status == 0}>غیر فعال</option>
                            <option value={2} selected={status == 2}>محدودیت</option>
                        </Select>

                        <ButtonPrimary loading={actionStatusMutation.isLoading} onClick={actionStatusMutation.mutateAsync}>
                            اعمال
                        </ButtonPrimary>
                    </div>
                }{
                    action == "digipay"
                    &&
                    <div className={"flex flex-col gap-2"}>
                        <Label>
                           پرداخت دیجی پی
                        </Label>
                        <Select onChange={(e) => {
                            setDigipay(Number(e.target.value))
                        }}>
                            <option value={1} selected={digipay == 1}>فعال</option>
                            <option value={0} selected={digipay == 0}>غیر فعال</option>
                        </Select>

                        <ButtonPrimary loading={actionDigipayMutation.isLoading} onClick={actionDigipayMutation.mutateAsync}>
                            اعمال
                        </ButtonPrimary>
                    </div>
                }

                </div>
            </Panel>
            <Panel>
                <div className={"flex flex-col w-full text-sm"}>
                    {response?.map((item) => (
                        <div
                            className={"border-b py-5 flex items-center gap-4"}
                            key={item.id}
                        >
                            {/* ✅ چک‌باکس انتخاب */}
                            <input
                                type="checkbox"
                                checked={!!selectedProducts[item.id]}
                                onChange={() => handleCheckboxChange(item.id)}
                                className="w-5 h-5 accent-blue-600"
                            />

                            <div className="relative h-24 w-24 flex-shrink-0 overflow-hidden rounded-xl">
                                <Image
                                    fill
                                    src={`${process.env.NEXT_PUBLIC_IMAGE_BASE_URL}/product/${item?.images?.data?.[0]?.url}`}
                                    alt={item.name}
                                    sizes="300px"
                                    className="h-full w-full object-contain object-center"
                                />
                            </div>

                            <div className={"py-5 flex flex-col gap-2"}>
                                <div className="font-medium">{item.name}</div>
                                {item.colors.data.map((color) => (
                                    <div
                                        className={"flex items-center gap-4"}
                                        key={color.id}
                                    >
                                        {color.color_name}
                                        |
                                        <div className={"flex items-center gap-1"}>
                                            <span>وضعیت</span>
                                            <span>
                                                {color.status==1?"فعال" : color.status==0?"غیر فعال" : color.status==2?"محدود" : "ناشناخته"}
                                            </span>
                                        </div>
                                        |
                                        <div className={"flex items-center gap-1"}>
                                            <span>موجودی</span>
                                            <span>
                                                {color.stock}
                                            </span>
                                        </div>
                                        |
                                        <Prices price={color.price ?? 0}/>
                                    </div>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>
            </Panel>
        </>
    );
}
