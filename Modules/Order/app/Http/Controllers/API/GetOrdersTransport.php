<?php

namespace Modules\Order\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Contracts\OrderContract;
use App\Enums\OrderTransportState;
use App\Http\Resources\OrderCollection;
use Modules\Order\Repositories\ShipperRepository;
use App\Http\Resources\OrderResource;
use Carbon\Carbon;
use Modules\Order\app\Models\Order;
use Modules\Order\Repositories\OrderTransportRepository;
class GetOrdersTransport extends Controller
{
    protected $orderTransportRepository;


    public function __construct(OrderTransportRepository $orderTransportRepository)
    {

        $this->orderTransportRepository = $orderTransportRepository;
     
    }
    public function __invoke(Request $request)
    {
        return $this->orderTransportRepository->getOrdersTransport($request);
    }
}
