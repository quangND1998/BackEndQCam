<?php

namespace Modules\Order\app\Http\Controllers\CSKH;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Modules\Order\Repositories\ShipperRepository;
use App\Contracts\OrderContract;
use Inertia\Inertia;
class CSKHOrderController extends Controller
{
    protected $orderRepository, $shipperRepository;
    public function __construct(OrderContract $orderRepository, ShipperRepository $shipperRepository)
    {

        $this->orderRepository = $orderRepository;
        $this->shipperRepository = $shipperRepository;
        // $this->middleware('permission:users-manager', ['only' => ['pending', 'packing', 'shipping', 'completed', 'refund', 'decline']]);
        $this->middleware('permission:order-pending|order-packing|order-shipping|order-completed|order-refund|order-decline', ['only' => [ 'index']]);
        $this->middleware('permission:add-new-package', ['only' => [ 'create','update']]);
        $this->middleware('permission:order-pending', ['only' => [ 'pending']]);
        $this->middleware('permission:order-packing', ['only' => [ 'packing']]);
        $this->middleware('permission:order-shipping', ['only' => [ 'shipping']]);
        $this->middleware('permission:order-completed', ['only' => [ 'completed']]);
        $this->middleware('permission:order-refund', ['only' => ['refund']]);
        $this->middleware('permission:order-decline', ['only' => [ 'decline']]);
    }
    public function index(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'pending';
        $orders =  $this->orderRepository->getOrderGift($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderStatus();
        $shippers = $this->shipperRepository->getShipper();

        // return $orders;
        // dd($statusGroup);
        return Inertia::render('Modules/CSKH/Index', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('order::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('order::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('order::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
