<?php

namespace App\Enums;

enum FilterType: int
{
    case CheckBox = 1;
    case DropDown = 2;

    public function label(): string
    {
        return match ($this) {
            static::CheckBox => 'CheckBox',
            static::DropDown => 'DropDown',
        };
    }
}
