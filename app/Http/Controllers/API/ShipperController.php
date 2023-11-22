<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Order\app\Models\Order;

class ShipperController extends Controller
{
    public function ordersRetailShipper()
    {
        $shipper = Auth::user();
        $orders_retail = Order::where('shipper_id', $shipper->id)->where('type', 'retail')->orderBy('created_at', 'desc')->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->get();
        // $orders_gitf = Order::where('shipper_id', $shipper->id)->where('type', 'gift_delivery')->orderBy('created_at', 'desc')->groupBy(DB::raw('Date(created_at)'))->get();
        // $response = [
        //     'orders_retail' => $orders_retail,
        //     'orders_gitf' => $orders_gitf
        // ];
        return response()->json($orders_retail, Response::HTTP_OK);
    }
}
