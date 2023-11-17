<?php

namespace Modules\Order\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Order\app\Models\Order;

class PaymentController extends Controller
{

    public function orderCashBankingPayment(Request $request, Order $order)
    {
        $order->load('orderItems.product', 'customer');
        return Inertia::render('Payment/CashBanking', compact('order'));
    }
}
