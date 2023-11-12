<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\VoucherResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Order\app\Models\Voucher;

class VoucherController extends BaseController
{
    public function findVoucher($code){
        $voucher = Voucher::where('code', $code)->first();
        if(!$voucher){
            return $this->sendError('Không tìm thấy mã giảm giá.');
        }
        if($voucher->is_fixed == 0){
            return $this->sendError('Mã giảm giá này đã không còn đc sử dụng.');
        }
        if($voucher->starts_at > Carbon::now() ){
            return $this->sendError('Mã giảm giá này chưa công khai');
        }
        if($voucher->expires_at <= Carbon::now() ){
            return $this->sendError('Mã giảm giá này đã hết hạn');
        }
        return new VoucherResource($voucher);
    }
}
