<?php

namespace Modules\Order\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Contracts\OrderContract;
use App\Enums\OrderStatusEnum;
use App\Enums\OrderTransportState;
use App\Http\Resources\OrderCollection;
use Modules\Order\Repositories\ShipperRepository;
use App\Http\Resources\OrderResource;
use Carbon\Carbon;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderTransport;
use Modules\Order\Repositories\OrderTransportRepository;

class GetOrdersTransportStatus extends Controller
{
    protected $orderTransportRepository;


    public function __construct(OrderTransportRepository $orderTransportRepository)
    {

        $this->orderTransportRepository = $orderTransportRepository;
    }
    public function __invoke(Request $request)
    {
        $count_orders = OrderTransport::count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');

        $statusGroup = $this->orderTransportRepository->groupByCount(OrderTransportState::cases(), 'state');
        $response = [

            'statusGroup' => $statusGroup,
            'from' => $from,
            'to' => $to,
            'count_orders' => $count_orders

        ];

        return response()->json($response, 200);
    }
}
