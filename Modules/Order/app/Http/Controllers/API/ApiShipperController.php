<?php

namespace Modules\Order\app\Http\Controllers\API;

use App\Contracts\OrderContract;
use App\Enums\OrderDocument;
use App\Enums\ShipperStatusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Order\app\Models\Order;

class ApiShipperController extends Controller
{
    public function getOrderStatus(Request $request)
    {
        $user = Auth::user();
        $statusGroup = Order::whereHas('orderItems')->fillterTime($request->only('date', 'day'))->where('shipper_id', $user->id)
            ->select('shipper_status', DB::raw('count(*) as total'))
            ->groupBy('shipper_status')
            ->get();
        $orderNotHaveImages = Order::fillterTime($request->only('date', 'day'))->where('state_document', OrderDocument::not_push)->where('shipper_status', ShipperStatusEnum::delivered)->where('shipper_id', $user->id)
            ->count();
        foreach (ShipperStatusEnum::cases() as $status) {

            $filtered = $statusGroup->where('shipper_status', $status->value)->first();
            if ($filtered == null) {

                $newCollections[] = array(
                    'shipper_status' => $status,
                    'total' => 0,


                );
            } else {

                $newCollections[] = array(
                    'shipper_status' => $status,
                    'total' => $filtered->total,

                );
            }
        }
        $newCollections[]    = [
            'shipper_status' => 'addition_document',
            'total' => $orderNotHaveImages
        ];
        return response()->json($newCollections, 200);
    }

    public function fetchOrders(Request $request)
    {
        $user = Auth::user();
        $orders = Order::with('customer')->where('shipper_id', $user->id)->fillterApi($request->only('search', 'date', 'day', 'shipper_status'))->paginate(1);
        return response()->json($orders, 200);
    }
}
