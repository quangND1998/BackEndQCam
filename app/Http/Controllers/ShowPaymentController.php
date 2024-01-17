<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentReource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Order\app\Models\Order;
use App\Service\PayooService;
use App\Models\Payment;
class ShowPaymentController extends Controller
{

    public $payooService;
    public function __construct(PayooService $payooService) {
        $this->payooService = $payooService;
    }
    public function listPayment(Request $request){
        $payments =  PaymentReource::collection(Payment::with('order')->paginate(15));
        return Inertia::render('Payment/ListPayment',compact('payments')); 
    }

    public function paymentOption(Order $order){
        if($order->payment_status ==0){
            $list_bank= $this->payooService->getListBank()->json();
          
            return Inertia::render('Payment/PaymentOption', compact('order','list_bank'));
        }
        else{
            return back()->with('warning', 'Đơn hàng đã được thành toán');
        }
      
    }
}
