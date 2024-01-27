<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case pending = 'pending';
    case packing = 'packing';
    case shipping = 'shipping';
    case completed = 'completed';
    case refunding = 'refunding';
    case refund = 'refund';
    case decline = 'decline';
}
