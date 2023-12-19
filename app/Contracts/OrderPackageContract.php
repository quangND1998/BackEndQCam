<?php

namespace App\Contracts;

interface OrderPackageContract
{
    public function storeOrderDetails($params, $customer);

    public function listOrders(string $order = 'id', string $sort = 'desc', array $columns = ['*']);

    public function findOrderByNumber($orderNumber);
}

