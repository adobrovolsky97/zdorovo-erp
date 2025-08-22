<?php

namespace App\Enum\Product;

enum Pack: string
{
    case VARIANT_150 = '150';
    case VARIANT_250 = '250';
    case VARIANT_500 = '500';
    case VARIANT_1000 = '1000';
    case BAG = 'bag';
    case POLYPROPYLENE_BAG = 'polipropylen_bag';
    case SH_250 = '250-sh';
    case B_250 = '250-b';

    public function title(): string
    {
        return match ($this) {
            self::VARIANT_150 => '150',
            self::VARIANT_250 => '250',
            self::VARIANT_500 => '500',
            self::VARIANT_1000 => '1000',
            self::BAG => 'Паперовий Мішок',
            self::POLYPROPYLENE_BAG => 'Поліпропіленовий Мішок',
            self::SH_250 => '250-Ш',
            self::B_250 => '250-В',
        };
    }

    public static function getByName(string $name): ?self
    {
        return match ($name) {
            '150' => self::VARIANT_150,
            '250' => self::VARIANT_250,
            '500' => self::VARIANT_500,
            '1000' => self::VARIANT_1000,
            'Паперовий Мішок' => self::BAG,
            'Поліпропіленовий Мішок' => self::POLYPROPYLENE_BAG,
            '250-Ш' => self::SH_250,
            '250-В' => self::B_250,
            default => null,
        };
    }
}
