<?php

namespace Modules\Order\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Contracts\OrderContract;
use App\Enums\OrderTransportStatus;
use App\Http\Resources\OrderCollection;
use Modules\Order\Repositories\ShipperRepository;
use App\Http\Resources\OrderResource;
use Carbon\Carbon;
use Modules\Order\app\Models\Order;

class GetOrders extends Controller
{
    protected $orderRepository, $shipperRepository;


    public function __construct(OrderContract $orderRepository, ShipperRepository $shipperRepository)
    {

        $this->orderRepository = $orderRepository;
        $this->shipperRepository = $shipperRepository;
    }
    public function __invoke(Request $request)
    {
        return  new OrderCollection($this->orderRepository->getAllOrderGift($request));
    }
}
