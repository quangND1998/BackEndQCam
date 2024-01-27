<?php

namespace App\Enums;

enum ShipperStatusEnum: string
{
    case pending = 'pending';
    case shipping = 'shipping';
    case delivered = 'delivered';
    case refunding = 'refunding';
    case refund = 'refund';
    case decline = 'decline';
}
