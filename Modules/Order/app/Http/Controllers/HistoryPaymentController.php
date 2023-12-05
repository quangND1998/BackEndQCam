<?php

namespace Modules\Order\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Order\app\Models\HistoryPayment;
use Modules\Order\app\Models\OrderPackage;
class HistoryPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function deleteHistoryPayment($orderpackage,$id){
        $history = HistoryPayment::find($id);
        $history->delete();
        $order = OrderPackage::find($orderpackage);
        if($order->totalPayment() >= $order->grand_total){
            $order->payment_status = 1;
            $order->save();
        }else{
            $order->price_percent = $order->totalPayment();
            $order->payment_status = 0;
            $order->save();
        }
        return redirect()->back()->with('success', "Xóa tài khoản  thành công");
    }

    public function setPaymentComplete($orderpackage,$id){
        $history = HistoryPayment::find($id);
        if($history){
            $history->status = "complete";
            $history->save();
        }
        return redirect()->back()->with('success', "duyệt  thành công");
    }
}
