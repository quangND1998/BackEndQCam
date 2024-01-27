<?php

namespace App\Enums;

enum OrderTransportStatus: string
{
    case pending = 'pending';
    case packed = 'packed';
    case not_shipper_receive = 'not_shipper_receive';
    case delivering = 'delivering';
    case delivered = 'delivered';
    case refunding = 'refunding';
    case refund = 'refund';
    case decline = 'decline';


    //     case pending = 'pending';
    //     case packed = 'packed';
    //     case not_shipper_receive = 'not_shipper_receive';
    //     case delivering = 'delivering';
    //     case delivered = 'delivered';
    //     case refunding = 'refunding';
    //     case refund = 'refund';
    //     case decline = 'decline';
}
