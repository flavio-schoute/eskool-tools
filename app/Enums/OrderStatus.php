<?php

namespace App\Enums;

enum OrderStatus: string
{
    case CLAIMED = 'claimed';
    case NOT_CLAIMED = 'not-claimed';
}
