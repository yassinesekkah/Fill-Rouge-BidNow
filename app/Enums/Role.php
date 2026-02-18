<?php

namespace App\Enums;

enum Role: string
{
    case BUYER = 'buyer';
    case SELLER = 'seller';
    case ADMIN = 'admin';
}
