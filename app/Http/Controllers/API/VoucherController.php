<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\VoucherResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Order\app\Models\Voucher;
use App\Http\Controllers\API\BaseController;

class VoucherController extends BaseController
{
    public function findVoucher($code)
    {
        $voucher = Voucher::where('code', $code)->first();
        if (!$voucher) {
            return $this->sendError('Không tìm thấy mã giảm giá.');
        }
        if ($voucher->is_fixed == 0) {
            return $this->sendError('Mã giảm giá này đã không còn đc sử dụng.');
        }
        if ($voucher->starts_at > Carbon::now()) {
            return $this->sendError('Mã giảm giá này chưa công khai');
        }
        if ($voucher->expires_at <= Carbon::now()) {
            return $this->sendError('Mã giảm giá này đã hết hạn');
        }
        return new VoucherResource($voucher);
    }


    public function getVouchers(Request $request)
    {
        $voucher = Voucher::where('starts_at', "<", Carbon::now())->where('expires_at', ">", Carbon::now())->where('is_fixed', 1)->orderBy('discount_value', 'desc')->orderBy('discount_max_value', 'desc')->fillter($request->only('total'))->get();

        return VoucherResource::collection($voucher);
    }
}
