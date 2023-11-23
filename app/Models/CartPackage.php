<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Session\Session;

class CartPackage extends Model
{
    use HasFactory;
    public $items = null;
    public $totalPrice = 0;
    public function __construct($oldCart){
		if($oldCart){
			$this->items = $oldCart->items;
			$this->totalPrice = $oldCart->totalPrice;
		}
	}
    public function add($product_service,$vat,$shipping_fee,$discount_deal,$payment_method,$time_approve){
		$giohang = ['time_approve'=>$time_approve, 'product_service' => $product_service, 'vat' => $vat, 'shipping_fee' => $shipping_fee, 'discount_deal'=>$discount_deal, 'payment_method' => $payment_method];

        $this->items[] = $giohang;
	}
}
