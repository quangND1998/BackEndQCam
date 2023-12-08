<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Service\PayooService;
use Modules\Order\app\Models\Order;
class PaymentController extends Controller
{
    public function payment(PayooService $payooService, Order $order){
        
        // return $payooService->createOderXml($order);
        $time = Carbon::parse(Carbon::now()->addDay(10))->format('Ymdhms');
        $checksum =  hash('sha512',config('payoo.ChecksumKey')."<shops><shop><username>SB_CamMatTroi</username><shop_id>11931</shop_id><shop_title>CamMatTroi</shop_title><shop_domain>https://qly.cammattroi.com</shop_domain><shop_back_url></shop_back_url><order_no>ORD_38155</order_no><order_cash_amount>1000</order_cash_amount><order_ship_date>06/12/2023</order_ship_date><order_ship_days>1</order_ship_days><order_description></order_description> <notify_url></notify_url><validity_time>20231216051231</validity_time><customer><name>Nguyen Van Hieu</name><phone>0905775888</phone><address>35 Nguyễn Huệ, p. Bến Nghé, Hồ Chí Minh</address><email>hieu@gmail.com</email></customer><installment><tenors>3,6</tenors></installment><recurring_info><contract_no></contract_no><init_date></init_date><billing_amount></billing_amount><billing_cycle></billing_cycle></recurring_info><customer_identifier></customer_identifier><JsonResponse>TRUE</JsonResponse><count_down></count_down><direct_return_time></direct_return_time></shop></shops>");
        return  $checksum;
    }
}
