<?php

namespace App\Traits;

trait Fa2En
{
    public function convertFa2En(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->convertFa2En($value);
            }
            elseif (is_string($value)) {
                $persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
                $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                $data[$key] = str_replace($persianDigits, $englishDigits, $value);
            }
        }
        return $data;
    }
}
