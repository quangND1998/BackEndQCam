<?php

namespace Modules\Order\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Contracts\OrderContract;
use App\Enums\OrderStatusEnum;
use App\Enums\OrderTransportStatus;
use App\Http\Resources\OrderCollection;
use Modules\Order\Repositories\ShipperRepository;
use App\Http\Resources\OrderResource;
use Carbon\Carbon;
use Modules\Order\app\Models\Order;

class GetStausOrders extends Controller
{
    protected $orderRepository, $shipperRepository;


    public function __construct(OrderContract $orderRepository, ShipperRepository $shipperRepository)
    {

        $this->orderRepository = $orderRepository;
        $this->shipperRepository = $shipperRepository;
    }
    public function __invoke(Request $request)
    {
        $count_orders = Order::where('state', true)->count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');

        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderStatusEnum::cases(), 'status');
        $response = [

            'statusGroup' => $statusGroup,
            'from' => $from,
            'to' => $to,
            'count_orders' => $count_orders

        ];

        return response()->json($response, 200);
    }
}
