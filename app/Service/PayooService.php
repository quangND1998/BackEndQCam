<?php

namespace App\Service;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Service\Traits\PayooServiceTraits;
use Modules\Order\app\Models\Order;

class PayooService
{
    use PayooServiceTraits;

    public function createPreorder($order, $method = "", $bank = "", $payment_group = "")
    {
        $orderXml = $this->createOrderXml($order, 1);
        $checksum = $this->checkSumConvert($orderXml);
        $response = Http::withHeaders([
            'Charset' => 'UTF-8'
        ])->post(config('payoo.payoo_api') . 'create-preorder', [
            "checksum" => $checksum,
            "refer" => request()->getSchemeAndHttpHost(),
            "data" => $orderXml,
            "payment_group" => "Bank-account,QRCode,CC,Qr-pay",

        ]);
      
        return $response;
    }


    public function savePaymentOrder($response)
    {

        $order = Order::where('order_number', $response['OrderNo'])->first();

        if ($order) {
            $payment = Payment::updateOrCreate([
                'OrderNo' => $response['OrderNo'],
                'OrderCash' => $response['OrderCash'],
                'PaymentStatus' => $response['PaymentStatus'],
                'PaymentMethod' => $response['PaymentMethod'],
                'PaymentMethodName' => $response['PaymentMethodName'],
                'PurchaseDate' => $response['PurchaseDate'],
                'MerchantUsername' => $response['MerchantUsername'],
                'ShopId' => $response['ShopId'],
                'BankName' => isset($response['BankName']) ? $response['BankName'] : null,
                'CardNumber' => isset($response['CardNumber']) ? $response['CardNumber'] : null,
                'BillingCode' => isset($response['BillingCode']) ? $response['BillingCode'] : null,
                'CardIssuanceType' => isset($response['CardIssuanceType']) ? $response['CardIssuanceType'] : null,
                'Customer_identifier' => isset($response['Customer_identifier']) ? $response['Customer_identifier'] : null,
                'MDD1' => isset($response['MDD1']) ? $response['MDD1'] : null,
                'MDD2' => isset($response['MDD2']) ? $response['MDD2'] : null,
                'Token' => isset($response['Token']) ? $response['Token'] : null,
                'VoucherTotalAmount' => isset($response['VoucherTotalAmount']) ? $response['VoucherTotalAmount'] : null,
                'VoucherDescription' => isset($response['VoucherDescription']) ? $response['VoucherDescription'] : null,
                'IsQRStatic' => isset($response['IsQRStatic']) ? $response['IsQRStatic'] : null,
                'order_id' => $order->id,

            ]);
            if ($payment->PaymentStatus == 1) {
                $order->update([
                    'payment_method' => 'payoo',
                    'payment_status' => 1
                ]);
            }
        }
    }

    public function GetOrderInfo($data)
    {

        $request = $this->createDataSecureHash($data);

        $response = Http::withHeaders([
            'APIUsername' =>  config('payoo.APIUsername'),
            'APIPassword' =>  config('payoo.APIPassword'),
            'APISignature' =>  config('payoo.APISignature'),
            'Content-Type' => 'application/json'
        ])->post(config('payoo.BACKEND_ENDPOINT') . '/GetOrderInfo', $request);
        return $response;
    }


    public function getListBank()
    {
        $response = Http::get(config('payoo.payoo_api') . 'api/paynow/get-banks-partner', [
            'code' => 'Ecommerce',
            'url' => request()->getSchemeAndHttpHost(),
            'id' =>  config('payoo.shopID'),
            'seller' =>  config('payoo.BusinessUsername'),
            'jsonCallback' => 0,
        ]);
        return $response;
    }
}
