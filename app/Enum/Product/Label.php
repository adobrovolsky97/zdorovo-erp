<?php

namespace App\Enum\Product;

enum Label: string
{
    case BIG_RESERVE_100 = 'big_reserve_100';
    case SMALL_RESERVE_10 = 'small_reserve_10';
    case NO_RESERVE = 'no_reserve';

    public function title(): string
    {
        return match ($this) {
            self::BIG_RESERVE_100 => 'Великий резерв 100',
            self::SMALL_RESERVE_10 => 'Малий резерв 10',
            self::NO_RESERVE => 'Без резерву (ФК)'
        };
    }

    public function getAmount(): int
    {
        return match ($this) {
            self::BIG_RESERVE_100 => 100,
            self::SMALL_RESERVE_10 => 10,
            self::NO_RESERVE => 0
        };
    }
}
