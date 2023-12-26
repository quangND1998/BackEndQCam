<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Service\PayooService;
use Illuminate\Support\Facades\File;
use Modules\Order\app\Models\Order;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Service\Traits\PayooServiceTraits;

class PaymentController extends Controller
{
    use PayooServiceTraits;
    //Create perorder
    public $payooService;
    public function __construct(PayooService $payooService)
    {
        $this->payooService = $payooService;
    }
    public function payment(Request $requet, Order $order)
    {

        $response = $this->payooService->createPreorder($order);
        $data = $response->json();
      
        if ($data['result'] == 'fail') {
            return $data;
            return back()->with('warning', $data['message']);
        } else {

            return redirect()->to($data['order']['payment_url']);
        }
        // $time = Carbon::parse(Carbon::now()->addDay(10))->format('Ymdhms');
        // $checksum =  hash('sha512',config('payoo.ChecksumKey')."<shops><shop><username>SB_CamMatTroi</username><shop_id>11931</shop_id><shop_title>CamMatTroi</shop_title><shop_domain>https://qly.cammattroi.com</shop_domain><shop_back_url></shop_back_url><order_no>ORD_38155</order_no><order_cash_amount>1000</order_cash_amount><order_ship_date>06/12/2023</order_ship_date><order_ship_days>1</order_ship_days><order_description></order_description> <notify_url></notify_url><validity_time>20231216051231</validity_time><customer><name>Nguyen Van Hieu</name><phone>0905775888</phone><address>35 Nguyễn Huệ, p. Bến Nghé, Hồ Chí Minh</address><email>hieu@gmail.com</email></customer><installment><tenors>3,6</tenors></installment><recurring_info><contract_no></contract_no><init_date></init_date><billing_amount></billing_amount><billing_cycle></billing_cycle></recurring_info><customer_identifier></customer_identifier><JsonResponse>TRUE</JsonResponse><count_down></count_down><direct_return_time></direct_return_time></shop></shops>");
        // return  $checksum;
    }

    public function payooIPN(Request $request)
    {
        $secureHash = $this->createSecureHashIPN($request->ResponseData);
        $response = json_decode($request->ResponseData, true);

        if (strtoupper($secureHash) == $request->SecureHash && $response["PaymentStatus"] == 1) {
            $this->payooService->savePaymentOrder($response);
            $response = [
                "ReturnCode" => 0,
                "Description" => ""
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                "ReturnCode" => 1,
                "Description" => ""
            ];
            return response()->json($response, 200);
        }
        // File::append(public_path('/attempt.txt'),  $request->RequestData);
    }



    public function GetOrderInfo(Order $order)
    {
        if ($order->payment_status == 1 && $order->last_payment) {
            $data = $this->payooService->GetOrderInfo($order->last_payment);
            return $data;
        } else {
            return back()->with('warning', 'Đơn hàng chưa thanh toán');
        }
    }

    public function getOrderPaymentForApp($id){
      
        $order = Order::find($id);
        if($order){
            if($order->payment_status ==0){
                $orderXml = $this->createOrderXmlSKD($order, 1);
                $checksum = $this->checkSumConvert($orderXml);
                $response =[
                    "ShopID" =>  config('payoo.shopID'),
                    "OrderInfo" =>  $orderXml,
                    "CheckSum" => $checksum,
                ];
                return response()->json($response, 200);
            }
            return response()->json('Đơn hàng đã thanh toán', 400);
          }
         return response()->json('Không tìm thấy đơn hàng', 400);
       
    }
}
