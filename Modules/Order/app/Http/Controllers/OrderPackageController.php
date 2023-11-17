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
use Modules\Tree\app\Models\Tree;
use Modules\Order\app\Models\OrderPackage;
use App\Models\User;
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
        $orders =  $this->orderRepository->getOrder($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderStatus();
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
        //     $oldCart = Session('cartPackage') ? $request->session()->get('cartPackage') : null;
        //     $cart = new CartPackage($oldCart);
        //     $cart->add($product_service,$request->vat,$request->shipping_fee,$request->discount_deal,$request->payment_method,$request->time_approve );
        //     $request->session()->put('cartPackage', $cart);
        // }
            $time_life = (int)$this->checkDay($product_service->life_time,$product_service->unit);
            $total_price = $product_service->price + (($request->vat * $product_service->price) / 100) - (($request->discount_deal *$product_service->price) / 100);
            $customer = User::where('phone_number',$request->phone_number)->first();
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
}
