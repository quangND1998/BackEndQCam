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
    public function index(Request $request)
    {
        // $order = Order::with('discount')->find(1);
        // return $order;
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'pending';
        $orders =  OrderPackage::with('customer','product_service')->where('status','pending')->get();
        //return $orders;
        $statusGroup = $this->groupByOrderStatus();
        return Inertia::render('Modules/Order/Package/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }
    public function listOrderCancel(Request $request)
    {
        // $order = Order::with('discount')->find(1);
        // return $order;
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'pending';
        $orders =  OrderPackage::with('customer','product_service')->where('status','decline')->get();
        //return $orders;
        $statusGroup = $this->groupByOrderStatus();
        return Inertia::render('Modules/Order/Package/OrderCancel', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }
    public function listOrderComplete(Request $request)
    {
        // $order = Order::with('discount')->find(1);
        // return $order;
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'pending';
        $orders =  OrderPackage::with('customer','product_service')->where('status','complete')->orderBy('created_at','desc')->get();
        //return $orders;
        $statusGroup = $this->groupByOrderStatus();
        return Inertia::render('Modules/Order/Package/OrderComplete', compact('orders', 'status', 'from', 'to', 'statusGroup'));
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
        //     $oldCart = Session('cartPackage') ? $request->session()->get('cartPackage') : null;
        //     $cart = new CartPackage($oldCart);
        //     $cart->add($product_service,$request->vat,$request->shipping_fee,$request->discount_deal,$request->payment_method,$request->time_approve );
        //     $request->session()->put('cartPackage', $cart);
        // }
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
                'price_percent' => $request->price_percent

            ]);
            // $oldCart = $request->session()->get('cartPackage');
            // dd($oldCart);

            return redirect()->route('admin.orders.package.pending',[$order->id]);
        }
    }
    public function saveOrderPackage(Request $request){
        $order = OrderPackage::create($request->all());
    }
    public function OrderPending(Request $request,$id){
            $order = OrderPackage::with('customer','product_service')->findOrFail($id);
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
        return $newCollections;
    }
}
