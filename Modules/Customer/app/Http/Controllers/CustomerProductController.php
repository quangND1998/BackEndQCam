<?php

namespace Modules\Customer\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Modules\Tree\app\Models\ProductService;
use Modules\Tree\app\Models\Tree;
use Inertia\Inertia;
use Modules\Customer\app\Models\ProductServiceOwner;
use Carbon\Carbon;
use Modules\Customer\app\Models\HistoryExtend;
use Modules\Customer\app\Models\HistoryUseService;

class CustomerProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-user|create-user|delete-user|update-user', ['only' => ['index']]);
        $this->middleware('permission:create-user', ['only' => ['store']]);
        $this->middleware('permission:update-user', ['only' => ['update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }
    public function index($id)
    {
        $customer = User::with('product_service_owners.product','product_service_owners.trees','product_service_owners.history_extend')->whereHas('roles', function ($query) {
            $query->where('name', 'Customer');
        })->findOrFail($id);
        $trees = Tree::where('state','public')->where('product_service_owner_id',null)->get();
        $products = ProductService::where("status", 1)->get();
        return Inertia::render('Modules/Customer/detail/products', compact('customer','products','trees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer::create');
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
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$id): RedirectResponse
    {
        $customer = User::findOrFail($id);
        //dd($request);
        if ($request->product_service) {
            $product_service = ProductService::findOrFail($request->product_service);

            if ($product_service) {
                $time_life = (int)$this->checkDay($product_service->life_time,$product_service->unit);
                $new_product_owner = new ProductServiceOwner;
                $new_product_owner->time_approve = $request->time_approve;
                $new_product_owner->time_end = Carbon::parse($request->time_approve)->addDays($time_life);

                $new_product_owner->description = $customer->name . " sử dụng gói " . $product_service->name;
                $new_product_owner->state = "active"; //active, expired, stop
                $new_product_owner->user_id = $customer->id;
                $new_product_owner->product_service_id = $product_service->id;
                $new_product_owner->save();
                $trees = Tree::find($request->tree);
                if($trees){
                    $new_product_owner->trees()->saveMany($trees);
                    $customer->save();
                }

                //history
                $this->extendProduct($new_product_owner,$request->time_approve,"tạo mới");
            }

        }
        return back()->with('success', 'Create customer successfully');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('customer::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,$id)
    {
        return view('customer::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, ProductService $product_service): RedirectResponse
    {
            $product_service_owner = ProductServiceOwner::with('product')->findOrFail($request->id);
            $product_service = ProductService::findOrFail($request->product_service);
            $product_service_owner->state = $request->state; //active, expired, stop

            if ($product_service_owner->product ==null || $product_service_owner->product->id != $product_service->id ) {
                $time_life = (int)$this->checkDay($product_service->life_time,$product_service->unit);
                $product_service_owner->time_approve = $request->time_approve;
                $product_service_owner->time_end = Carbon::parse($request->time_approve)->addDays($time_life);
                $product_service_owner->product_service_id = $product_service->id;
            }
            foreach($product_service_owner->trees() as $tree){
                $tree->product_service_owner_id = null;
                $tree->save();
            }
            $trees = Tree::find($request->tree);
            $product_service_owner->trees()->saveMany($trees);

            $product_service_owner->save();
            return back()->with('success', 'Create customer successfully');
    }
    public function extend(Request $request,$id){

        $product_owner = ProductServiceOwner::findOrFail($request->product_service);
        if($product_owner){
            $time_limit =  $this->extendProduct($product_owner,$request->time_approve,"gia hạn");
            $product_owner->time_approve = $request->time_approve;
            $product_owner->time_end = $time_limit;

            $product_owner->description = " gia hạn gói " . $product_owner->product->name;
            $product_owner->state = "active";
            $product_owner->save();
            return back()->with('success', 'Create customer successfully');
        }else{
            return back()->with('error', 'không tồn tại gói dịch vụ');
        }

    }

    public function extendProduct($product_service,$time,$state){
        if($product_service->product){
        $time_life = (int)$this->checkDay($product_service->product->life_time,$product_service->product->unit);
        $time_limit = Carbon::parse($time)->addDays($time_life);

        $history_extend = new HistoryExtend;
        $history_extend->date_from = $time;
        $history_extend->date_to = $time_limit;
        $history_extend->description = $state;
        $product_service->history_extend()->save($history_extend);
        return $time_limit;
        }
    }
    public function getExtendHistory($id){
        $product_owner = ProductServiceOwner::with('customer','history_extend.contract')->findOrFail($id);
        $customer =  $product_owner->customer;

        return Inertia::render('Modules/Customer/detail/extendHistory', compact('customer','product_owner'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
