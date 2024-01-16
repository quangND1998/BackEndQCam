<?php

namespace Modules\Shipper\app\Http\Controllers;

use App\Contracts\OrderContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Order\Repositories\ShipperRepository;

class ShipperController extends Controller
{
    protected $orderRepository, $shipperRepository;
    public function __construct(OrderContract $orderRepository, ShipperRepository $shipperRepository)
    {

        $this->orderRepository = $orderRepository;
        $this->shipperRepository = $shipperRepository;
    }
    public function index(Request $request, $status)
    {
        $array_status = ['pending', 'packing', 'shipping', 'completed', 'wait_refund', 'refund', 'decline'];
        $shippers = $this->shipperRepository->getShipper();
        $statusGroup = $this->orderRepository->groupByOrderStatus();
    }
}
