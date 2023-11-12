<?php

namespace App\SaleService;

use Modules\Order\app\Models\ProductVoucher;
use App\SaleService\SaleInterface;

class PercentDiscount implements SaleInterface
{

    public static function price_sale($price, $discount)
    {
        return $price - ($price * $discount) * 100;
    }

    public static function updateItem($voucher, $item, $product, $discount)
    {

        $item->update([
            'price' => $product->price,
            'discount' => $discount,
            'unit' => $voucher->unit,
            'price_sale' => $product->price - ($product->price * $discount) / 100
        ]);
    }

    public static function createItem($voucher, $product, $discount)
    {
        ProductVoucher::create([
            'voucher_id' => $voucher->id,
            'product_retail_id' => $product->id,
            'price' => $product->price,
            'unit' => $voucher->unit,
            'discount' => $discount,
            'price_sale' => $product->price - ($product->price * $discount) / 100
        ]);
    }
}
