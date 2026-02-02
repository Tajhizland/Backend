<?php

namespace App\Service\Lang;

class LangService
{
    public static function convertToEnglishNumbers($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($persian, $english, $string);
    }

    public static function convertArabicToPersian($input)
    {
        $arabicToPersianMap = [
            'ك' => 'ک',
            'ي' => 'ی',
            'ة' => 'ه',
            'ى' => 'ی',
            'ؤ' => 'و',
            'إ' => 'ا',
            'أ' => 'ا',
            '٠' => '۰',
            '١' => '۱',
            '٢' => '۲',
            '٣' => '۳',
            '٤' => '۴',
            '٥' => '۵',
            '٦' => '۶',
            '٧' => '۷',
            '٨' => '۸',
            '٩' => '۹',
        ];

        return strtr($input, $arabicToPersianMap);
    }
}
