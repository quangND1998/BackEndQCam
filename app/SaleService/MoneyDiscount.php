<?php

namespace App\SaleService;

use Modules\Order\app\Models\ProductVoucher;
use App\SaleService\SaleInterface;
use Modules\Order\Entities\OrderItem;

class MoneyDiscount implements SaleInterface
{

    public static function price_sale($price, $discount)
    {
        return $price - $discount;
    }

    public static function updateItem($voucher, $item, $product, $discount)
    {
        $item->update([
            'price' => $product->price,
            'unit' => $voucher->unit,
            'discount' => $discount,
            'price_sale' => ($discount >= $product->price) ? $product->price : $product->price - $discount
        ]);
    }

    public static function createItem($sale, $product, $discount)
    {
        ProductVoucher::create([
            'voucher_id' => $sale->id,
            'product_retail_id' => $product->id,
            'price' => $product->price,
            'unit' => $sale->unit,
            'discount' => $discount,
            'price_sale' => ($discount >= $product->price) ? $product->price : $product->price - $discount
        ]);
    }
}
