<?php

namespace App\Service\Traits;

use Carbon\Carbon;

trait PayooServiceTraits
{

    public function createOrderXml($order, $time_expried)
    {
        $username = config('payoo.BusinessUsername');

        $shop_id =  config('payoo.shopID');
        $shop_title =  config('payoo.ShopTitle');
        $shop_domain = request()->getSchemeAndHttpHost();
        $order_ship_date = Carbon::now()->format('d/m/Y');

        $notify_url = $shop_domain . '/api/payoo/ipn';

        $time = Carbon::parse(Carbon::now()->addDay($time_expried))->format('Ymdhms');

        // $order_xml= "<shops><shop><username>{$username}</username><shop_id>{$shop_id}</shop_id><shop_title>{$shop_title}</shop_title><shop_domain>{$shop_domain}</shop_domain><shop_back_url></shop_back_url><order_no>{$order->order_number}</order_no><order_cash_amount>{$order->last_price}</order_cash_amount><order_ship_date>{$order_ship_date}</order_ship_date><order_ship_days>{$time_expried}</order_ship_days><order_description></order_description><notify_url>{$notify_url}</notify_url><validity_time>20231216051231</validity_time><customer><name>{$order->customer->name}</name><phone>{$order->customer->phone_number}</phone><address>{$order->address} ,{$order->district}, {$order->wards}, {$order->city}</address><email>{$order->customer->email}</email></customer><installment><tenors>3,6</tenors></installment><recurring_info><contract_no></contract_no><init_date></init_date><billing_amount></billing_amount><billing_cycle></billing_cycle></recurring_info><customer_identifier></customer_identifier><JsonResponse>TRUE</JsonResponse><count_down></count_down><direct_return_time></direct_return_time></shop></shops>";
        $order_xml = "<shops><shop><username>{$username}</username><shop_id>{$shop_id}</shop_id><shop_title>{$shop_title}</shop_title><shop_domain>{$shop_domain}</shop_domain><shop_back_url></shop_back_url><order_no>{$order->order_number}</order_no><order_cash_amount>{$order->last_price}</order_cash_amount><order_ship_date>{$order_ship_date}</order_ship_date><order_ship_days>{$time_expried}</order_ship_days><order_description></order_description><notify_url>{$notify_url}</notify_url><validity_time>{$time}</validity_time><customer><name>{$order->customer->name}</name><phone>{$order->customer->phone_number}</phone><address>{$order->address} ,{$order->district}, {$order->wards}, {$order->city}</address><email>{$order->customer->email}</email></customer><recurring_info><contract_no></contract_no><init_date></init_date><billing_amount></billing_amount><billing_cycle></billing_cycle></recurring_info><customer_identifier></customer_identifier><JsonResponse>TRUE</JsonResponse><count_down></count_down><direct_return_time></direct_return_time></shop></shops>";
        return $order_xml;
    }

    public function checkSumConvert($orderXml)
    {
        $checksum =  hash('sha512', config('payoo.ChecksumKey') . $orderXml);
        return $checksum;
    }

    public function createDataSecureHash($payment)
    {

        $data = [
            'OrderID' => $payment->OrderNo,
            'ShopID' => config('payoo.shopID'),
            'PurchaseDate' => Carbon::parse($payment->PurchaseDate)->format('Ymd')
        ];
        $RequestData = json_encode($data);

        return [
            "RequestData" => $RequestData,
            "SecureHash" => hash('sha512', config('payoo.ChecksumKey') . $RequestData)
        ];
    }

    public function createSecureHashIPN($ResponseData)
    {

        return  hash('sha512', config('payoo.ChecksumKey') . $ResponseData . config('payoo.payoo_ip_sandbox'));
    }

    public function createOrderXmlSKD($order, $time_expried)
    {
        $username = config('payoo.BusinessUsername');

        $shop_id =  config('payoo.shopID');
        $shop_title =  config('payoo.ShopTitle');
        $shop_domain = request()->getSchemeAndHttpHost();
        $order_ship_date = Carbon::now()->format('d/m/Y');

        $notify_url = $shop_domain . '/api/payoo/ipn';

        $time = Carbon::parse(Carbon::now()->addDay($time_expried))->format('Ymdhms');
        $shop_back_url = 'payoosdk' . $shop_id . '://postbacksdkurl';
        // $order_xml= "<shops><shop><username>{$username}</username><shop_id>{$shop_id}</shop_id><shop_title>{$shop_title}</shop_title><shop_domain>{$shop_domain}</shop_domain><shop_back_url></shop_back_url><order_no>{$order->order_number}</order_no><order_cash_amount>{$order->last_price}</order_cash_amount><order_ship_date>{$order_ship_date}</order_ship_date><order_ship_days>{$time_expried}</order_ship_days><order_description></order_description><notify_url>{$notify_url}</notify_url><validity_time>20231216051231</validity_time><customer><name>{$order->customer->name}</name><phone>{$order->customer->phone_number}</phone><address>{$order->address} ,{$order->district}, {$order->wards}, {$order->city}</address><email>{$order->customer->email}</email></customer><installment><tenors>3,6</tenors></installment><recurring_info><contract_no></contract_no><init_date></init_date><billing_amount></billing_amount><billing_cycle></billing_cycle></recurring_info><customer_identifier></customer_identifier><JsonResponse>TRUE</JsonResponse><count_down></count_down><direct_return_time></direct_return_time></shop></shops>";
        $order_xml = "<shops><shop><username>{$username}</username><shop_id>{$shop_id}</shop_id><shop_title>{$shop_title}</shop_title><shop_domain>{$shop_domain}</shop_domain><shop_back_url>{$shop_back_url}</shop_back_url><order_no>{$order->order_number}</order_no><order_cash_amount>{$order->last_price}</order_cash_amount><order_ship_date>{$order_ship_date}</order_ship_date><order_ship_days>0</order_ship_days><order_description></order_description><notify_url>{$notify_url}</notify_url><validity_time>{$time}</validity_time><customer><name>{$order->customer->name}</name><phone>{$order->customer->phone_number}</phone><address>{$order->address} ,{$order->district}, {$order->wards}, {$order->city}</address><email>{$order->customer->email}</email></customer><recurring_info><contract_no></contract_no><init_date></init_date><billing_amount></billing_amount><billing_cycle></billing_cycle></recurring_info><customer_identifier></customer_identifier><JsonResponse>TRUE</JsonResponse><count_down></count_down><direct_return_time></direct_return_time></shop></shops>";
        return $order_xml;
    }
}
