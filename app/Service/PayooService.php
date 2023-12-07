<?php
namespace App\Service;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
class PayooService {
    

    public function createPreorder($method, $bank,$order){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Charset' => 'UTF-8'
        ])->post(config('payoo.payoo_api').'create-preorder', [
            'checksum' => config('payoo.ChecksumKey'),
            'method' => $method,
            'bank' => $bank,
            'refer”' => request()->getSchemeAndHttpHost(),
            "data"=> $order
        
        ]);
    }
    public function createOderXml($order){
        $username= config('payoo.BusinessUsername');
        $shop_id =  config('payoo.shopID');
        $shop_title =  config('payoo.ShopTitle');
        $shop_domain = 'http://127.0.0.1:8000';
        $order_ship_date = Carbon::now()->format('d/m/Y');
      
       
        $order_xml= "<shops>
                        <shop>
                            <username>{$username}</username>
                            <shop_id>{$shop_id}</shop_id>
                            <shop_title>{$shop_title}</shop_title>
                            <shop_domain>{$shop_domain}</shop_domain>
                            <shop_back_url></shop_back_url>
                            <order_no>{$order->order_number}</order_no>
                            <order_cash_amount>{$order->last_price}</order_cash_amount>
                            <order_ship_date>{$order_ship_date}</order_ship_date>
                            <order_ship_days>1</order_ship_days>
                            <order_description></order_description>
                            <notify_url></notify_url>
                            <validity_time>20231216051231</validity_time>
                            <customer>
                                <name>{$order->customer->name}</name>
                                <phone>{$order->customer->phone_number}</phone>
                                <address>{$order->address} ,{$order->district}, {$order->wards}, {$order->city}</address>
                                <email>{$order->customer->email}</email>
                            </customer>
                            <installment>
                                <tenors>3,6</tenors>
                                </installment>
                                <recurring_info>
                                <contract_no></contract_no>
                                <init_date></init_date>
                                <billing_amount></billing_amount>
                                <billing_cycle></billing_cycle>
                                </recurring_info>
                                <customer_identifier>
                                </customer_identifier>
                                <JsonResponse>TRUE</JsonResponse>
                                <count_down>
                                </count_down>
                                <direct_return_time></direct_return_time>
                        </shop>
                </shops>";
        return $order_xml;

        // return "<shops><shop><username>SB_CamMatTroi</username><shop_id>11931</shop_id><shop_title>CamMatTroi</shop_title><shop_domain>https://qly.cammattroi.com</shop_domain><shop_back_url></shop_back_url><order_no>ORD_38155</order_no><order_cash_amount>10000</order_cash_amount><order_ship_date>06/12/2023</order_ship_date><order_ship_days>1</order_ship_days><order_description></order_description> <notify_url></notify_url><validity_time>20231216051231</validity_time><customer><name>Nguyen Van Hieu</name><phone>0905775888</phone><address>35 Nguyễn Huệ, p. Bến Nghé, Hồ Chí minh</address><email>hieu@gmail.com</email></customer><installment><tenors>3,6</tenors></installment><recurring_info><contract_no></contract_no><init_date></init_date><billing_amount></billing_amount><billing_cycle></billing_cycle></recurring_info><customer_identifier></customer_identifier><JsonResponse>TRUE</JsonResponse><count_down></count_down><direct_return_time></direct_return_time></shop></shops>";
    }
}