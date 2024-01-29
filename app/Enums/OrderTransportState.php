<?php

namespace App\Enums;

enum OrderTransportState: string
{

        // là state trong bảng order_transports
    case pending = 'pending'; //Đơn chờ
    case packing = 'packing'; //Đóng gói
    case shipping = 'shipping'; //Đang vận chuyển
    case delivered = 'delivered'; //Đã giao
    case refunding = 'refunding'; //chờ Hoàn
    case refund = 'refund'; //Đơn hoàn 
    case decline = 'decline'; //Đơn Hủy
}
