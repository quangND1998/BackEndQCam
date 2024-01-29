<?php

namespace App\Enums;

enum OrderTransportStatus: string
{


        // là status trong bảng order_transports
    case wait_package = 'wait_package'; //Chờ đóng gói
    case not_shipper_owner = 'not_shipper_owner'; //Chưa giao shipper
    case not_shipping = 'not_shipping'; //Chưa giao
    case not_delivered = 'not_delivered'; //Chưa vận chuyển
    case delivered = 'delivered'; //Đã giao hàng
    case wait_refund = 'wait_refund'; //Chờ hoàn
    case refund = 'refund'; //Đã hoàn
    case wait_decline = 'wait_decline'; //Chờ hủy
    case decline = 'decline'; //Đã hủy
}
