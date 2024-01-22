<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case pending = 'pending';
    case processing = 'processing';
    case packing = 'packing';
    case shipping = 'shipping';
    case completed = 'completed';
    case refunding = 'refunding';
    case refunded = 'refunded';
    case decline = 'decline';
}
