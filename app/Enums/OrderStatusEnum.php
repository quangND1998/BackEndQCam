<?php

namespace App\Enums;

enum OrderStatusEnum: string
{

        //status trong table orders
    case create = 'create'; //Tạo mới
    case pending = 'pending'; //Pending
    case processing = 'processing'; //Đang xử lý
    case completed = 'completed'; //Thành công
}
