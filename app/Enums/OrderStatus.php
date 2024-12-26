<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Processing = 'processing';
    case Delivering = 'delivering';
    case Shipped = 'shipped';
}