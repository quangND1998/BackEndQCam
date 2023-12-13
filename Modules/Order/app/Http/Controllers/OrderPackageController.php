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
use App\Jobs\OrderPackageCreatedJob;
use App\Jobs\OrderPackageEndTimeJob;
use Modules\Order\app\Http\Requests\SaveOrderpackageRequest;
class OrderPackageController extends Controller
{
    protected $orderRepository;
    public function __construct(OrderContract $orderRepository)
    {

        $this->orderRepository = $orderRepository;

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
    public function orderChangeStatus(Request $request, OrderPackage $order)
    {
        // 'payment_status' => $request->payment_status]
        $order->update(
            [
            'status' => 'pending',
            'reason' => $request->reason,
            'package_reviewer' => Auth::user()->id
            ]
        );
        return back()->with('success', 'Đơn hàng đã được chuyển sang trạng thái');
    }
    public function index(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'pending';
        $orders  = $this->getOrder($request,$status);
        $statusGroup = $this->groupByOrderStatus();
        return Inertia::render('Modules/Order/Package/index', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }

    public function listPending(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'pending';
        $orders  = $this->getOrder($request,$status);
        $statusGroup = $this->groupByOrderStatus();
        return Inertia::render('Modules/Order/Package/index', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }
    public function listOrderCancel(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'decline';
        $orders  = $this->getOrder($request,$status);
        $statusGroup = $this->groupByOrderStatus();
        return Inertia::render('Modules/Order/Package/index', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }
    public function listOrderComplete(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'complete';
        $orders  = $this->getOrder($request,$status);
        $statusGroup = $this->groupByOrderStatus();
        return Inertia::render('Modules/Order/Package/index', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }
    public function partiallyPaid(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'partiallyPaid';
        $orders  =  OrderPackage::with(['customer', 'product_service','historyPayment','saler','product_service_owner'])->role()->whereHas(
            'customer',
            function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->customer . '%');
            }
        )->whereColumn('price_percent', '<', 'grand_total')
        ->fillter($request->only('search', 'from', 'to', 'payment_status', 'payment_method', 'type'))->orderBy('created_at', 'desc')->paginate($request->per_page ? $request->per_page : 10);
        $statusGroup = $this->groupByOrderStatus();
        return Inertia::render('Modules/Order/Package/index', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }
    public function drafPaid(Request $request){
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'draf';
        $orders  =  OrderPackage::with(['customer', 'product_service','historyPayment','saler','product_service_owner'])->role()->whereHas(
            'customer',
            function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->customer . '%');
            }
        )->where('price_percent', '=', 0)
        ->fillter($request->only('search', 'from', 'to', 'payment_status', 'payment_method', 'type'))->orderBy('created_at', 'desc')->paginate($request->per_page ? $request->per_page : 10);
        $statusGroup = $this->groupByOrderStatus();
        return Inertia::render('Modules/Order/Package/index', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }
    public function orderPackage(Request $request){
        $user = Auth::user();
        $sales = User::whereHas('team')->with('team')->role('saler')->get();
        $leaders = User::role('leader-sale')->get();
        $telesale = User::role('telesale')->get();
        $ctv = User::role('ctv')->get();

        $product_services = ProductService::where("status", 1)->get();
        $trees = Tree::where('state','public')->where('product_service_owner_id',null)->get();

        return Inertia::render('Modules/Order/Package/CreateOrderPackage', compact('product_services','trees','sales','leaders','telesale','ctv'));
    }
    public function editOrderPackage(Request $request,$id){
        $order = OrderPackage::with('customer','product_service','saler','leader','resources','order_package_images','historyPayment.order_package_payment','historyPayment.user')->findOrFail($id);
        if($order->status == "pending"){
        //   return $order->historyPayment;
        $user = Auth::user();
        $sales = User::whereHas('team')->with('team')->role('saler')->get();
        $leaders = User::role('leader-sale')->get();
        $product_services = ProductService::where("status", 1)->get();
        $trees = Tree::where('state','public')->where('product_service_owner_id',null)->get();
        $telesale = User::role('telesale')->get();
        $ctv = User::role('ctv')->get();
        return Inertia::render('Modules/Order/Package/editOrderPackage', compact('order','product_services','trees','sales','leaders','telesale','ctv'));
        }else{
            return redirect()->route('admin.orders.package.pending')->with('warning', 'Đơn hàng Đã thanh toán không thể cập nhật!');
        }
    }
    public function addToCart(SaveOrderpackageRequest $request){
        $product_service = ProductService::findOrFail($request->product_selected);
        $trees = Tree::with('land')->whereHas('land', function ($query) {
            $query->where('state', 'public');
        })->where('state','public')->where('product_service_owner_id',null)->first();
        if($trees == null){
            return redirect()->route('admin.orders.package.pending')->with('warning', 'Cây đã bán hết!');
        }

        if($product_service){
            $time_life = (int)$this->checkDay($product_service->life_time,$product_service->unit);
            $total_price = $product_service->price;
            if ($request->discount_deal > 0) {
                $total_price  = $total_price - (( $total_price  * $request->discount_deal) / 100);
            }
            if ($request->vat > 0) {
                $total_price +=  (( $total_price * $request->vat) / 100);
            }
            // $total_price = ($product_service->price - (($request->discount_deal *$product_service->price) / 100)) - ($request->vat * $product_service->price) / 100) ;
            $customer = User::where('phone_number',$request->phone_number)->where('name',$request->name)->first();

            if(!$customer){
                $customer = $this->createCustomerDefault($request);
            }
            if($customer){
                $order = OrderPackage::create([
                    'idPackage' => $request->idPackage,
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
                    'type' => 'new',
                    'time_reservations' => $request->time_reservations,
                    'product_selected' => $request->product_selected,
                    'time_approve' => $request->time_approve,
                    'time_end' => Carbon::parse($request->time_approve)->addDays($time_life),
                    'price_percent' => $request->price_percent,
                    'sale_id' => Auth::user()->id,
                    'ref_id' => $request->ref_id,
                    'to_id' => $request->leader_sale_id,
                    'customer_resources' => $request->type_customer_resource,
                    'customer_resources_id' => $request->customer_resource_id,

                ]);

                // foreach ($request->images as $image) {
                //     $order->addMedia($image)->toMediaCollection('order_package_images');
                // }
                $payment_date = Carbon::now();
                $historypayment = $this->storeHistoryPayment($order->id,$request->payment_method,$request->price_percent,$payment_date,$request->images);
                $this->storeOrderPackage($order);
                if($order->status =='pending'){
                    $order->time_expried = Carbon::now()->addDay($order->time_reservations);
                    // $order->time_expried = Carbon::now()->addSecond(20);
                    $order->save();
                    OrderPackageEndTimeJob::dispatch($order)->delay(now()->addDay($order->time_reservations));
                    // OrderPackageEndTimeJob::dispatch($order)->delay(now()->addSecond(20));
                }
                return redirect()->route('admin.orders.package.detail',[$order->id]);
            }else{
                return back()->with('error', 'Customer k ton tai');
            }
        }
    }
    public function saveEditOrder(Request $request, $id){
        // dd($request);
        $this->validate(
            $request,
            [
                'name' => 'required',
                'payment_method' => 'required',
                'address' => 'required',
                'city' => 'required',
                'district' => 'required',
                'wards' => 'required',
                'phone_number' => 'required',
                'vat' => 'nullable|numeric|between:0,100',
                'discount_deal' => 'nullable|nullable|between:0,100',
                'type' => 'required',
                'shipping_fee' => 'nullable|nullable|gt:-1',
                'time_reservations' => 'required|gt:0',
                'price_percent' => 'required|gt:-1',
                'product_selected' => 'required',
                'time_approve' =>'required'
            ]
        );
        $order = OrderPackage::findOrFail($id);
        // return $order;
        $product_service = ProductService::findOrFail($request->product_selected);
        $time_life = (int)$this->checkDay($product_service->life_time,$product_service->unit);
        $total_price = $product_service->price;
        if ($request->discount_deal > 0) {
            $total_price  = $total_price - (( $total_price  * $request->discount_deal) / 100);
        }
        if ($request->vat > 0) {
            $total_price +=  (( $total_price * $request->vat) / 100);
        }
        if (count($order->historyPayment) > 0){
            // neu khong xoa lich su thanh toán
            $price_percent = $order->price_percent;
        }else{
            $price_percent = $request->price_percent;
        }
        $customer = $order->customer;
        $customer = $this->updateCustomer($request,$customer);
        $order->update([
            'idPackage' => $request->idPackage,
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
            'type' => 'new',
            'time_reservations' => $request->time_reservations,
            'product_selected' => $request->product_selected,
            'time_approve' => $request->time_approve,
            'time_end' => Carbon::parse($request->time_approve)->addDays($time_life),
            'price_percent' => $price_percent,
            'to_id' => $request->leader_sale_id,
            'ref_id'  => $request->ref_id,
            'customer_resources' => $request->type_customer_resource,
            'customer_resources_id' => $request->customer_resource_id,

        ]);
        // if ($request->hasFile('images')) {
        //     $order->clearMediaCollection('order_package_images');

        //     foreach ($request->images as $image) {
        //         $order->addMedia($image)->toMediaCollection('order_package_images');
        //     }
        // }
        if (count($order->historyPayment) == 0){
            $order->historyPayment()->delete();
            $payment_date = Carbon::now();
            $historypayment = $this->storeHistoryPayment($order->id,$request->payment_method,$request->price_percent,$payment_date,$request->images);
        }
        if($order->status =='pending'){
            $order->time_expried = Carbon::now()->addDay($order->time_reservations);
            $order->save();
            OrderPackageEndTimeJob::dispatch($order)->delay(now()->addDay($order->time_reservations));
        }
        return redirect()->route('admin.orders.package.detail',[$order->id]);
    }
    public function saveHistoryPaymentOrder(Request $request,$id){
        $this->validate($request, [
            'payment_method' => 'required',
            'amount_received' => 'required|numeric|gt:0',
            'payment_date' => 'required'
        ]);
        $order = OrderPackage::find($id);
        if($order->totalPayment() < $order->grand_total){
            $historypayment = $this->storeHistoryPayment($order->id,$request->payment_method,$request->amount_received,$request->payment_date,$request->images);
            $order->price_percent = $order->totalPayment() + $request->amount_received;
        }else{
            $order->price_percent = $order->grand_total;
        }
        $order->save();
        $order = OrderPackage::find($id);
        $order->price_percent = $order->totalPayment();
        $order->save();
        // if($order->totalPayment() >= $order->grand_total){
        //     $order->payment_status = 1;
        //     $order->save();
        // }else{

        //     $order->payment_status = 0;
        //     $order->save();
        // }

        return back()->with('success', 'Lưu payment thành công');
    }
    public function storeHistoryPayment($order_package_id,$payment_method,$amount_received,$payment_date,$images){
        $history_payment = new HistoryPayment;
        $history_payment->order_package_id = $order_package_id;
        $history_payment->payment_method = $payment_method;
        $history_payment->amount_received = $amount_received;
        $history_payment->payment_date = $payment_date;
        $history_payment->user_id = Auth::user()->id;
        $history_payment->save();

        foreach ($images as $image) {
            $history_payment->addMedia($image)->toMediaCollection('order_package_payment');
        }
        return $history_payment;
    }
    public function saveOrderPackage(Request $request){
        $order = OrderPackage::create($request->all());
    }
    public function OrderPending(Request $request,$id){
            $order = OrderPackage::with('customer','product_service','product_service_owner.trees','historyPayment')->findOrFail($id);
            // return $order;
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
            'reason' => $request->reason,
            'package_reviewer' => Auth::user()->id
        ]);

        $product_service_owner = $product_service_owner = $order->product_service_owner;

        if($product_service_owner){
            foreach($product_service_owner->trees as $tree){
                $tree->product_service_owner_id = null;
                $tree->save();
            }
            $product_service_owner->delete();
        }

        return back()->with('success', 'Hủy đơn thành công');
    }
    public function orderComplete(Request $request, OrderPackage $order)
    {

        $order->update([
            'status' => $request->status,
            'package_reviewer' => Auth::user()->id
        ]);
        $this->storeOrderPackage($order);
        return back()->with('success', 'Đơn hàng đã được chuyển sang trạng thái');
    }
    //
    public function orderDelete(OrderPackage $order)
    {
        $product_service_owner = ProductServiceOwner::where('user_id',$order->user_id)->whereHas('trees')->where('product_service_id',$order->product_service)->first();

        if($product_service_owner){
            foreach($product_service_owner->trees as $tree){
                $tree->product_service_owner_id = null;
                $tree->save();
            }
            $product_service_owner->delete();
        }
        $order->delete();
        return back()->with('success', 'Hủy đơn thành công');
    }
    public function storeOrderPackage($order){

        $customer = $order->customer;
        $product_service = $order->product_service;
        $trees = Tree::where('state','public')->where('product_service_owner_id',null)->first();
        if(!$trees){
            return back()->with('error', 'Hết cây');
        }
        if ($product_service && $order->product_service_owner == null) {

            $new_product_owner = new ProductServiceOwner;
            $new_product_owner->time_approve = $order->time_approve;
            $new_product_owner->time_end = $order->time_end;
            $new_product_owner->price = $order->grand_total;
            $new_product_owner->description = $customer->name . " sử dụng gói " . $product_service->name;
            $new_product_owner->state = "pending"; //active, expired, stop, pending
            $new_product_owner->user_id = $customer->id;
            $new_product_owner->product_service_id = $product_service->id;
            $new_product_owner->order_id = $order->id;
            $new_product_owner->save();


            if($trees){
                $trees->pay_status = "đã nhận nuôi";
                $new_product_owner->trees()->save($trees);
                $customer->save();
            }
            //history

            $history_extend = new HistoryExtend;
            $history_extend->price = $order->grand_total;
            $history_extend->product_name = $product_service->name;
            $history_extend->date_from = $order->time_approve;
            $history_extend->date_to = $order->time_end;
            $history_extend->description = "tạo mới";
            $history_extend->order_id = $order->id;
            $new_product_owner->history_extend()->save($history_extend);
        }else{
            $order->product_service_owner->state = "active";
            $order->product_service_owner->save();
            return back()->with('error', 'Đơn đã tạo hợp đồng');
        }


    }
    public function createCustomerDefault($request){
        $this->validate(
            $request,
            [
                'name' => 'required',
                'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,phone_number,',
            ]
        );
        $customer = new User;
        $customer->name = $request->name;
        $customer->username = $request->name;
        $customer->phone_number = $request->phone_number;
        $customer->sex = $request->sex;
        $roles = 'customer';
        $customer->assignRole($roles);
        $customer->password = Hash::make('cammattroi');
        if($request->address){
            $customer->address = $request->address;
            $customer->city = $request->city;
            $customer->district = $request->district;
            $customer->wards = $request->wards;
        }
        $customer->save();
        return $customer;
    }
    public function updateCustomer($request,$customer){

        $this->validate(
            $request,
            [
                'name' => 'required',
                'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,phone_number,' . $customer->id,
            ]
        );
        $customer->name = $request->name;
        $customer->username = $request->name;
        $customer->phone_number = $request->phone_number;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->district = $request->district;
        $customer->wards = $request->wards;
        $customer->sex = $request->sex;
        $customer->save();
        return $customer;
    }
    public function groupByOrderStatus()
    {
        $array_status = ['pending','complete', 'decline'];
        $statusGroup = OrderPackage::role()->select('status', DB::raw('count(*) as total'))
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
        $newCollections[] = array(
            'status' => 'draf',
            'total' => $this->groupByPaymentDraf(),
        );
        return $newCollections;
    }
    public function groupByPayment(){
        $paymentGroup = OrderPackage::role()->whereColumn('price_percent', '<', 'grand_total')->count();
        return $paymentGroup;
    }
    public function groupByPaymentDraf(){
        $paymentGroup = OrderPackage::role()->where('price_percent','=',0)->count();
        return $paymentGroup;
    }
    public function getOrder($request, $status)
    {
        return OrderPackage::with(['customer','package_reviewer', 'product_service','historyPayment.order_package_payment','historyPayment.user','saler','product_service_owner','history_extend.contract.lastcontract.images'])->role()->whereHas(
            'customer',
            function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->customer . '%');
            }
        )->where('status', $status)->fillter($request->only('search', 'from', 'to', 'payment_status', 'payment_method', 'type'))->orderBy('created_at', 'desc')->paginate($request->per_page ? $request->per_page : 5);
    }


}
