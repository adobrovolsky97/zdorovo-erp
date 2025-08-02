<?php

namespace App\Enum\ImportExport;

enum Status: string
{
    case FAILED = 'failed';
    case FINISHED = 'finished';
    case NEW = 'new';
    case PENDING = 'pending';
}
