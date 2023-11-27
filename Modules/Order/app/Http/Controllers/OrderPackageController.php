<?php

namespace Modules\Order\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CartPackage;
use Modules\Tree\app\Models\ProductService;
use Carbon\Carbon;
use App\Contracts\OrderContract;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Tree\app\Models\Tree;
use Modules\Order\app\Models\OrderPackage;
use App\Models\User;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Customer\app\Models\HistoryExtend;
use Illuminate\Support\Facades\DB;
use Modules\Order\app\Models\HistoryPayment;

class OrderPackageController extends Controller
{
    protected $orderRepository;
    public function __construct(OrderContract $orderRepository)
    {

        $this->orderRepository = $orderRepository;

        // $this->middleware('permission:users-manager', ['only' => ['pending', 'packing', 'shipping', 'completed', 'refund', 'decline']]);
        $this->middleware('permission:order-pending', ['only' => ['index', 'pending', 'create']]);
        $this->middleware('permission:order-packing', ['only' => ['index', 'packing']]);
        $this->middleware('permission:order-shipping', ['only' => ['index', 'shipping']]);
        $this->middleware('permission:order-completed', ['only' => ['index', 'completed']]);
        $this->middleware('permission:order-refund', ['only' => ['index', 'refund']]);
        $this->middleware('permission:order-decline', ['only' => ['index', 'decline']]);
    }
    public function orderChangeStatus(Request $request, OrderPackage $order)
    {
        $order->update(['payment_status' => $request->payment_status]);
        return back()->with('success', 'Đơn hàng đã được chuyển sang trạng thái');
    }
    public function index(Request $request)
    {
        // $order = Order::with('discount')->find(1);
        // return $order;
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'pending';
        // $orders =  OrderPackage::with('customer','product_service')->where('status','pending')->get();
        $orders  = $this->getOrder($request,$status);
        //  return $orders;
        $statusGroup = $this->groupByOrderStatus();
        return Inertia::render('Modules/Order/Package/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }
    public function listOrderCancel(Request $request)
    {
        // $order = Order::with('discount')->find(1);
        // return $order;
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'decline';
        $orders  = $this->getOrder($request,$status);
        // $orders =  OrderPackage::with('customer','product_service')->where('status','decline')->get();
        //return $orders;
        $statusGroup = $this->groupByOrderStatus();
        return Inertia::render('Modules/Order/Package/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }
    public function listOrderComplete(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'complete';
        $orders  = $this->getOrder($request,$status);
        // return $orders;
        // $orders =  OrderPackage::with('customer','product_service')->where('status','complete')->orderBy('created_at','desc')->get();
        $statusGroup = $this->groupByOrderStatus();
        return Inertia::render('Modules/Order/Package/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }
    public function partiallyPaid(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'partiallyPaid';
        $orders  =  OrderPackage::with(['customer', 'product_service','historyPayment','saler'])->whereHas(
            'customer',
            function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->customer . '%');
            }
        )
        ->fillter($request->only('search', 'from', 'to', 'payment_status', 'payment_method', 'type'))->orderBy('created_at', 'desc')->paginate(15);
        $statusGroup = $this->groupByOrderStatus();
        return Inertia::render('Modules/Order/Package/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }
    public function orderPackage(Request $request){
        $user = Auth::user();
        $product_services = ProductService::where("status", 1)->get();
        $trees = Tree::where('state','public')->where('product_service_owner_id',null)->get();

        return Inertia::render('Modules/Order/Package/CreateOrderPackage', compact('product_services','trees'));
    }
    public function addToCart(Request $request){
        $product_service = ProductService::findOrFail($request->product_selected);
        if($product_service){
            $time_life = (int)$this->checkDay($product_service->life_time,$product_service->unit);
            $total_price = $product_service->price + (($request->vat * $product_service->price) / 100) - (($request->discount_deal *$product_service->price) / 100);
            $customer = User::where('phone_number',$request->phone_number)->first();
            if(!$customer){
                $customer = $this->createCustomerDefault($request);
            }
            $order = OrderPackage::create([
                'order_number'      =>  'ORD-' . strtoupper(uniqid()),
                'user_id'           => $customer->id,
                'status'            =>  'pending',
                'payment_status'    =>  0,
                'payment_method' => $request->payment_method,
                'address' => $request->address,
                'city' => $request->city,
                'district' => $request->district,
                'wards' => $request->wards,
                'phone_number'        =>  $request->phone_number,
                'notes'         =>  $request->notes,
                'vat' =>$request->vat,
                'discount_deal' =>$request->discount_deal,
                'grand_total' => $total_price,
                'type' =>$request->type,
                'product_selected' => $request->product_selected,
                'time_approve' => $request->time_approve,
                'time_end' => Carbon::parse($request->time_approve)->addDays($time_life),
                'price_percent' => $request->price_percent,
                'sale_id' => Auth::user()->id,

            ]);

            foreach ($request->images as $image) {
                $order->addMedia($image)->toMediaCollection('order_package_images');
            }
            $payment_date = Carbon::now();
            $historypayment = $this->storeHistoryPayment($order->id,$request->payment_method,$request->price_percent,$payment_date);
            return redirect()->route('admin.orders.package.pending',[$order->id]);
        }
    }
    public function saveHistoryPaymentOrder(Request $request,$id){
        $this->validate($request, [
            'payment_method' => 'required',
            'amount_received' => 'required|numeric|gt:0',
            'payment_date' => 'required'
        ]);
        $order = OrderPackage::find($id);
        if($order->totalPayment() < $order->grand_total){
            $historypayment = $this->storeHistoryPayment($order->id,$request->payment_method,$request->amount_received,$request->payment_date);
            $order->price_percent = $order->totalPayment() + $request->amount_received;
        }else{
            $order->price_percent = $order->grand_total;
        }
        $order->save();
        $order = OrderPackage::find($id);
        $order->price_percent = $order->totalPayment();
        if($order->totalPayment() >= $order->grand_total){
            $order->payment_status = 1;
            $order->save();
        }else{

            $order->payment_status = 0;
            $order->save();
        }

        return back()->with('success', 'Lưu payment thành công');
    }
    public function storeHistoryPayment($order_package_id,$payment_method,$amount_received,$payment_date){
        $history_payment = new HistoryPayment;
        $history_payment->order_package_id = $order_package_id;
        $history_payment->payment_method = $payment_method;
        $history_payment->amount_received = $amount_received;
        $history_payment->payment_date = $payment_date;
        $history_payment->save();
        return $history_payment;
    }
    public function deleteHistoryPayment($orderpackage,$id){
        $history = HistoryPayment::find($id);
        $history->delete();
        $order = OrderPackage::find($orderpackage);
        if($order->totalPayment() >= $order->grand_total){
            $order->payment_status = 1;
            $order->save();
        }else{
            $order->price_percent = $order->totalPayment();
            $order->payment_status = 0;
            $order->save();
        }
        return redirect()->back()->with('success', "Xóa tài khoản  thành công");
    }
    public function saveOrderPackage(Request $request){
        $order = OrderPackage::create($request->all());
    }
    public function OrderPending(Request $request,$id){
            $order = OrderPackage::with('customer','product_service','historyPayment')->findOrFail($id);
            return Inertia::render('Modules/Order/Package/CashPaymentPackage', compact('order'));
    }
    public function checkDay($lif_time, $unit)
    {
        switch ($unit) {
            case "day":
                return $lif_time;
                break;
            case "month":
                return $lif_time*30;
                break;
            case "year":
                return $lif_time*365;
                break;
        }
    }
    public function orderCancel(Request $request, OrderPackage $order)
    {
        $request->validate([
            'reason' => 'required',

        ], [
            'reason.required' => 'Điền lý do hủy đơn'
        ]);
        $order->update([
            'status' => 'decline',
            'reason' => $request->reason
        ]);

        $product_service_owner = ProductServiceOwner::where('user_id',$order->user_id)->whereHas('trees')->where('product_service_id',$order->product_service)->first();

        if($product_service_owner){
            foreach($product_service_owner->trees as $tree){
                $tree->product_service_owner_id = null;
                $tree->save();
            }
        }
       // $product_service_owner->delete();
        return back()->with('success', 'Hủy đơn thành công');
    }
    public function orderComplete(Request $request, OrderPackage $order)
    {

        $order->update([
            'status' => $request->status,
        ]);
        $this->storeOrderPackage($order);
        return back()->with('success', 'Đơn hàng đã được chuyển sang trạng thái');
    }
    public function storeOrderPackage($order){

        $customer = $order->customer;
        $product_service = $order->product_service;
        if ($product_service) {

            $new_product_owner = new ProductServiceOwner;
            $new_product_owner->time_approve = $order->time_approve;
            $new_product_owner->time_end = $order->time_end;

            $new_product_owner->description = $customer->name . " sử dụng gói " . $product_service->name;
            $new_product_owner->state = "active"; //active, expired, stop
            $new_product_owner->user_id = $customer->id;
            $new_product_owner->product_service_id = $product_service->id;
            $new_product_owner->save();

            $trees = Tree::where('state','public')->where('product_service_owner_id',null)->first();
            if($trees){
                $new_product_owner->trees()->save($trees);
                $customer->save();
            }


            //history

            $history_extend = new HistoryExtend;
            $history_extend->price = $product_service->price;
            $history_extend->product_name = $product_service->name;
            $history_extend->date_from = $order->time_approve;
            $history_extend->date_to = $order->time_end;
            $history_extend->description = "tạo mới";
            $new_product_owner->history_extend()->save($history_extend);
        }


    }
    public function createCustomerDefault($request){
        $customer = new User;
        $customer->name = $request->name;
        $customer->username = $request->name;
        $customer->phone_number = $request->phone_number;
        $roles = 'customer';
        $customer->assignRole($roles);
        $customer->password = Hash::make('cammattroi');
        $customer->save();
        return $customer;
    }
    public function groupByOrderStatus()
    {
        $array_status = ['pending','complete', 'decline'];
        $statusGroup = OrderPackage::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        foreach ($array_status as $status) {

            $filtered = $statusGroup->where('status', $status)->first();

            if ($filtered == null) {

                $newCollections[] = array(
                    'status' => $status,
                    'total' => 0,
                );
            } else {

                $newCollections[] = array(
                    'status' => $status,
                    'total' => $filtered->total,

                );

            }
        }
        $newCollections[] = array(
            'status' => 'partiallyPaid',
            'total' => $this->groupByPayment(),

        );
        return $newCollections;
    }
    public function groupByPayment(){
        $paymentGroup = OrderPackage::whereColumn('price_percent', '<', 'grand_total')->count();
        return $paymentGroup;
    }
    public function getOrder($request, $status)
    {
        return OrderPackage::with(['customer', 'product_service','historyPayment','saler'])->whereHas(
            'customer',
            function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->customer . '%');
            }
        )->where('status', $status)->fillter($request->only('search', 'from', 'to', 'payment_status', 'payment_method', 'type'))->orderBy('created_at', 'desc')->paginate(20);
    }
}
