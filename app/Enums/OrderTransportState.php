<?php

namespace App\Enums;

enum OrderTransportState: string
{
    case pending = 'pending';
    case packing = 'packed';
    case shipping = 'shipping';
    case delivered = 'delivered';
    case refunding = 'refunding';
    case refund = 'refund';
    case decline = 'decline';
}
