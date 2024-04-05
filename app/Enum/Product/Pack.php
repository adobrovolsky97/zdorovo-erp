<?php

namespace App\Enum\Product;

enum Pack: string
{
    case VARIANT_150 = '150';
    case VARIANT_250 = '250';
    case VARIANT_500 = '500';
    case VARIANT_1000 = '1000';
    case BAG = 'bag';

    public function title(): string
    {
        return match ($this) {
            self::VARIANT_150 => '150',
            self::VARIANT_250 => '250',
            self::VARIANT_500 => '500',
            self::VARIANT_1000 => '1000',
            self::BAG => 'Паперовий Мішок',
        };
    }
}
