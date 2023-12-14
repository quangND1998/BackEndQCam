<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\app\Models\Order;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $fillable = [
        'OrderNo',
        'OrderCash',
        'PaymentStatus',
        'PaymentMethod',
        'PaymentMethodName',
        'PurchaseDate',
        'MerchantUsername',
        'ShopId',
        'BankName',
        'CardNumber',
        'BillingCode',
        'CardIssuanceType',
        'Customer_identifier',
        'MDD1',
        'MDD2',
        'Token',
        'VoucherTotalAmount',
        'VoucherDescription',
        'IsQRStatic',
        'order_id',
    ];

    public function order(){
        return $this->belongsTo(Order::class,  'user_id');
    }

}
