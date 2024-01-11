<?php

namespace Modules\Order\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Order\app\Models\HistoryPayment;
use Modules\Order\app\Models\OrderPackage;
use Illuminate\Support\Facades\Auth;

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
            // $order->payment_status = 1;
            // $order->save();
        }else{
            $order->price_percent = $order->totalPayment();
           // $order->payment_status = 0;
            $order->save();
        }
        return redirect()->back()->with('success', "Xóa tài khoản  thành công");
    }
    public function updateImageHistoryPayment(Request $request, $id){
        // dd($request->images);
        // dd($request->file('images'));
        $history = HistoryPayment::find($id);
        foreach ($request->images as $image) {
            // dd($image);
            $history->addMedia($image)->toMediaCollection('order_package_payment');
        }
        return $history;
    }
    public function setPaymentComplete($orderpackage,$id){

        $history = HistoryPayment::find($id);
        if($history){
            $history->status = "complete";
            $history->user_id = Auth::user()->id;
            $history->save();
        }
        $order = OrderPackage::with('historyPayment')->find($orderpackage);
        $allHistory = $order->historyPayment->every(function ($history) {
            return $history->status == "complete";
        });
        if ($allHistory) {
            $order->payment_status = 1;
            $order->save();
        } else {
            $order->payment_status = 0;
            $order->save();
        }

        $this->updateStateDocument($order);
        return redirect()->back()->with('success', "duyệt  thành công");
    }
    public function setPaymentCompleteDocument(OrderPackage $order,$id){
        $history = HistoryPayment::find($id);
        if($history){
            $history->state_document = 1;
            $history->save();
        }
        $this->updateStateDocument($order);
        return redirect()->back()->with('success', "duyệt  thành công");
    }
    public function updateStateDocument($order){
        if($order->document_add && $order->document_check){
            $order->state_document = "OK";
        }
        elseif($order->document_add && $order->document_check == false){
            $order->state_document = "Check";
        }
        else{
            $order->state_document = "BS";
        }
        $order->save();
    }
}
