<?php

namespace App\SaleService;

interface SaleInterface
{
    public static function price_sale($price, $discount);

    public static function updateItem($voucher, $item, $product, $discount);

    public static function createItem($voucher, $product, $discount);
}
