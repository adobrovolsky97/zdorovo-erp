<?php

namespace App\Enum\Package;

enum Status: string
{
    case PENDING = 'pending';
    case SENT = 'sent';

    public function title(): string
    {
        return match ($this) {
            self::PENDING => 'У роботі',
            self::SENT => 'Відправлено до Bimpsoft',
        };
    }
}
