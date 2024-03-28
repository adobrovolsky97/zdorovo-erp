<?php

namespace App\Enum\Package;

enum Status: string
{
    case PENDING = 'pending';
    case SENT = 'sent';
}
