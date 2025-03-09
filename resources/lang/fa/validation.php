<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute باید پذیرفته شده باشد.',
    'active_url'           => 'آدرس :attribute معتبر نیست.',
    'after'                => ':attribute باید تاریخی بعد از :date باشد.',
    'after_or_equal'       => ':attribute باید تاریخی بعد از :date، یا مطابق با آن باشد.',
    'alpha'                => ':attribute باید فقط حروف الفبا باشد.',
    'alpha_dash'           => ':attribute باید فقط حروف الفبا، اعداد، خط تیره و زیرخط باشد.',
    'alpha_num'            => ':attribute باید فقط حروف الفبا و اعداد باشد.',
    'array'                => ':attribute باید آرایه باشد.',
    'before'               => ':attribute باید تاریخی قبل از :date باشد.',
    'before_or_equal'      => ':attribute باید تاریخی قبل از :date، یا مطابق با آن باشد.',
    'between'              => [
        'numeric' => ':attribute باید بین :min و :max باشد.',
        'file'    => ':attribute باید بین :min و :max کیلوبایت باشد.',
        'string'  => ':attribute باید بین :min و :max کاراکتر باشد.',
        'array'   => ':attribute باید بین :min و :max آیتم باشد.',
    ],
    'boolean'              => 'فیلد :attribute فقط می‌تواند true و یا false باشد.',
    'confirmed'            => ':attribute با فیلد تکرار مطابقت ندارد.',
    'date'                 => ':attribute یک تاریخ معتبر نیست.',
    'date_equals'          => ':attribute باید یک تاریخ برابر با تاریخ :date باشد.',
    'date_format'          => ':attribute با الگوی :format مطابقت ندارد.',
    'different'            => ':attribute و :other باید از یکدیگر متفاوت باشند.',
    'digits'               => ':attribute باید :digits رقم باشد.',
    'digits_between'       => ':attribute باید بین :min و :max رقم باشد.',
    'dimensions'           => 'ابعاد تصویر :attribute قابل قبول نیست.',
    'distinct'             => 'فیلد :attribute مقدار تکراری دارد.',
    'email'                => ':attribute باید یک ایمیل معتبر باشد.',
    'ends_with'            => 'فیلد :attribute باید با یکی از مقادیر زیر خاتمه یابد: :values',
    'exists'               => ':attribute انتخاب شده، معتبر نیست.',
    'file'                 => ':attribute باید یک فایل معتبر باشد.',
    'filled'               => 'فیلد :attribute باید مقدار داشته باشد.',
    'gt'                   => [
        'numeric' => ':attribute باید بزرگتر از :value باشد.',
        'file'    => ':attribute باید بزرگتر از :value کیلوبایت باشد.',
        'string'  => ':attribute باید بیشتر از :value کاراکتر داشته باشد.',
        'array'   => ':attribute باید بیشتر از :value آیتم داشته باشد.',
    ],
    'gte'                  => [
        'numeric' => ':attribute باید بزرگتر یا مساوی :value باشد.',
        'file'    => ':attribute باید بزرگتر یا مساوی :value کیلوبایت باشد.',
        'string'  => ':attribute باید بیشتر یا مساوی :value کاراکتر داشته باشد.',
        'array'   => ':attribute باید بیشتر یا مساوی :value آیتم داشته باشد.',
    ],
    'image'                => ':attribute باید یک تصویر معتبر باشد.',
    'in'                   => ':attribute انتخاب شده، معتبر نیست.',
    'in_array'             => 'فیلد :attribute در لیست :other وجود ندارد.',
    'integer'              => ':attribute باید عدد صحیح باشد.',
    'ip'                   => ':attribute باید آدرس IP معتبر باشد.',
    'ipv4'                 => ':attribute باید یک آدرس معتبر از نوع IPv4 باشد.',
    'ipv6'                 => ':attribute باید یک آدرس معتبر از نوع IPv6 باشد.',
    'json'                 => 'فیلد :attribute باید یک رشته از نوع JSON باشد.',
    'lt'                   => [
        'numeric' => ':attribute باید کوچکتر از :value باشد.',
        'file'    => ':attribute باید کوچکتر از :value کیلوبایت باشد.',
        'string'  => ':attribute باید کمتر از :value کاراکتر داشته باشد.',
        'array'   => ':attribute باید کمتر از :value آیتم داشته باشد.',
    ],
    'lte'                  => [
        'numeric' => ':attribute باید کوچکتر یا مساوی :value باشد.',
        'file'    => ':attribute باید کوچکتر یا مساوی :value کیلوبایت باشد.',
        'string'  => ':attribute باید کمتر یا مساوی :value کاراکتر داشته باشد.',
        'array'   => ':attribute باید کمتر یا مساوی :value آیتم داشته باشد.',
    ],
    'max'                  => [
        'numeric' => ':attribute نباید بزرگتر از :max باشد.',
        'file'    => ':attribute نباید بزرگتر از :max کیلوبایت باشد.',
        'string'  => ':attribute نباید بیشتر از :max کاراکتر داشته باشد.',
        'array'   => ':attribute نباید بیشتر از :max آیتم داشته باشد.',
    ],
    'mimes'                => 'فرمت‌های معتبر فایل عبارتند از: :values.',
    'mimetypes'            => 'فرمت‌های معتبر فایل عبارتند از: :values.',
    'min'                  => [
        'numeric' => ':attribute نباید کوچکتر از :min باشد.',
        'file'    => ':attribute نباید کوچکتر از :min کیلوبایت باشد.',
        'string'  => ':attribute نباید کمتر از :min کاراکتر داشته باشد.',
        'array'   => ':attribute نباید کمتر از :min آیتم داشته باشد.',
    ],
    'not_in'               => ':attribute انتخاب شده، معتبر نیست.',
    'not_regex'            => 'فرمت :attribute معتبر نیست.',
    'numeric'              => ':attribute باید عدد یا رشته‌ای از اعداد باشد.',
    'present'              => 'فیلد :attribute باید در پارامترهای ارسالی وجود داشته باشد.',
    'regex'                => 'فرمت :attribute معتبر نیست.',
    'required'             => 'فیلد :attribute الزامی است.',
    'required_if'          => 'هنگامی که :other برابر با :value است، فیلد :attribute الزامی است.',
    'required_unless'      => 'فیلد :attribute الزامی است، مگر آنکه :other در :values موجود باشد.',
    'required_with'        => 'در صورت وجود فیلد :values، فیلد :attribute نیز الزامی است.',
    'required_with_all'    => 'در صورت وجود فیلدهای :values، فیلد :attribute نیز الزامی است.',
    'required_without'     => 'در صورت عدم وجود فیلد :values، فیلد :attribute الزامی است.',
    'required_without_all' => 'در صورت عدم وجود هر یک از فیلدهای :values، فیلد :attribute الزامی است.',
    'same'                 => ':attribute و :other باید همانند هم باشند.',
    'size'                 => [
        'numeric' => ':attribute باید برابر با :size باشد.',
        'file'    => ':attribute باید برابر با :size کیلوبایت باشد.',
        'string'  => ':attribute باید برابر با :size کاراکتر باشد.',
        'array'   => ':attribute باید شامل :size آیتم باشد.',
    ],
    'starts_with'          => ':attribute باید با یکی از این ها شروع شود: :values',
    'string'               => 'فیلد :attribute باید رشته باشد.',
    'timezone'             => 'فیلد :attribute باید یک منطقه زمانی معتبر باشد.',
    'unique'               => ':attribute قبلا انتخاب شده است.',
    'uploaded'             => 'بارگذاری فایل :attribute موفقیت آمیز نبود.',
    'url'                  => ':attribute معتبر نمی‌باشد.',
    'hex_color'                  => ':attribute باید معتبر باشد.',
    'uuid'                 => ':attribute باید یک UUID معتبر باشد.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    'captcha' => ':attribute اشتباه است.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'mobile'                => 'شماره همراه',
        'username'                => 'شماره همراه',
        'code'                  => 'کد امنیتی',
        'password'              => 'گذرواژه',
        'password_confirmation' => 'تکرار گذرواژه',
        'name' => 'نام',
        'tell' => 'تلفن',
        'url' => 'آدرس',
        'address' => 'آدرس',
        'city_id' => 'شهر',
        'province_id' => 'استان',
        'zip_code' => 'کد پستی',
        'query' => 'جستجو',
        'productId' => 'شناسه محصول',
        'rating' => 'امتیاز',
        'text' => 'متن',
        'description' => 'توضیحات',
        'study' => 'بررسی',
        'categoryId' => 'شناسه دسته بندی',
        'color.*.name' => 'نام رنگ',
        'color.*.code' => 'کد رنگ',
        'color.*.status' => 'وضعیت رنگ',
        'color.*.price' => 'قیمت رنگ',
        'color.*.stock' => 'موجودی رنگ',
        'color.*.discount' => 'تخفیف رنگ',
        'color.*.id' => 'شناسه رنگ',
        'id'=>'شناسه'
    ],
    'attributes2' => [
        'name'                  => 'نام',
        'username'              => 'نام کاربری',
        'email'                 => 'ایمیل',
        'first_name'            => 'نام',
        'last_name'             => 'نام خانوادگی',
        'password'              => 'گذرواژه',
        'password_confirmation' => 'تکرار گذرواژه',
        'city'                  => 'شهر',
        'country'               => 'کشور',
        'address'               => 'نشانی',
        'phone'                 => 'شماره ثابت',
        'mobile'                => 'شماره همراه',
        'age'                   => 'سن',
        'sex'                   => 'جنسیت',
        'gender'                => 'جنسیت',
        'day'                   => 'روز',
        'month'                 => 'ماه',
        'year'                  => 'سال',
        'hour'                  => 'ساعت',
        'minute'                => 'دقیقه',
        'second'                => 'ثانیه',
        'title'                 => 'عنوان',
        'text'                  => 'متن',
        'content'               => 'محتوا',
        'description'           => 'توضیحات',
        'excerpt'               => 'گزیده مطلب',
        'date'                  => 'تاریخ',
        'time'                  => 'زمان',
        'available'             => 'موجود',
        'size'                  => 'اندازه',
        'terms'                 => 'شرایط',
        'province'              => 'استان',
        'tel'                   => 'تلفن',
        'equipment'             => 'تجهیزات',
        'activity'              => 'فعالیت',
        'groups'                => 'دسته بندی',
        'website'               => 'وبسایت',
        'info_icon'             => 'آیکون',
        'info_logo'             => 'لوگوی شرکت',
        'image'                 => 'تصویر',
        'link'                  => 'لینک',
        'category_id'           => 'دسته بندی',
        'postcat'               => 'دسته بندی وبلاگ',
        'productcat'            => 'دسته بندی محصول',
        'province_id'           => 'استان',
        'city_id'               => 'شهر',
        'postal_code'           => 'کد پستی',
        'file'                  => "فایل",
        'subject'               => "موضوع",
        'message'               => "پیام",
        'gateway_payir_api'     => 'api کد pay.ir',


        'gateway_mellat_username'     => 'نام کاربری درگاه ملت',
        'gateway_mellat_password'     => 'رمز عبور درگاه ملت',
        'gateway_mellat_terminalId'   => 'کد پذیرنده درگاه ملت',

        'gateway'               => 'درگاه پرداخت',
        'price'                 => "قیمت",
        'prices'                => "قیمت",
        'stock'                 => "موجودی انبار",
        'color'                 => "رنگ",
        'warranty'              => "گارانتی",
        'cart_max'              => "تعداد مجاز در هر سفارش",
        'slug'                  => 'url متا',
        'captcha'               => 'کد امنیتی',
        'specification_group'   => 'گروه مشخصات',
        'spec_type'             => 'نوع مشخصات',
        'weight'                => 'وزن',
        'type'                  => 'نوع',
        'percent'               => 'درصد',
        'referral_code'         => 'کد معرف'
    ],
];
