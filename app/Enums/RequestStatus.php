<?php

namespace App\Enums;

enum RequestStatus: string
{
    case IN_OPEN = 'in_open';
    case IN_PROGRESS = 'in_progress';
    case CANCELLED = 'canceled';
    case COMPLETED = 'sold';
}


