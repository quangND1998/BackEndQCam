<?php

namespace App\Enums;

enum OrderTransportStatus: string
{
    case pending = 'pending';
    case processing = 'processing';
    case packing = 'packing';
    case packed = 'packed';
    case not_shipper_receive = 'not_shipper_receive';
        // case not_delivery = 'not_delivery';
    case delivering = 'delivering';
    case delivered = 'delivered';
    case refunding = 'refunding';
    case refund = 'refund';
    case decline = 'decline';
}
