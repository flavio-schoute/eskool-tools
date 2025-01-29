<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case CLOSER = 'closer';
    CASE SETTER = 'setter';
}
