<?php

namespace App\Enums;

enum ShipperStatusEnum: string
{
    case pending = 'pending';
    case received  = 'received';
    case delivery = 'delivery';
    case delivered = 'delivered';
}
