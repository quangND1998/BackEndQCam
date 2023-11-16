<?php

namespace Modules\Customer\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use App\Models\User;
use Carbon\Carbon;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Tree\app\Models\ProductService;
use Spatie\Permission\Models\Role;
use Modules\Tree\app\Models\Tree;
use Modules\Customer\app\Models\HistoryExtend;
class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-user|create-user|delete-user|update-user', ['only' => ['index']]);
        $this->middleware('permission:create-user', ['only' => ['store']]);
        $this->middleware('permission:update-user', ['only' => ['update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $user = Auth::user();
        $filters = $request->all('search');
        $customers = User::with('product_service_owners.product','product_service_owners.trees')->whereHas(
            'roles',
            function ($query) {
                $query->where('name', 'Customer');
            }
        )->where(function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
            $query->orwhere('email', 'LIKE', '%' . $request->search . '%');
            $query->orwhere('username', 'LIKE', '%' . $request->search . '%');
            $query->orwhere('phone_number', 'LIKE', '%' . $request->search . '%');
        })->paginate(20)->appends($request->search);

        $product_services = ProductService::where("status", 1)->get();
        $trees = Tree::where('state','public')->where('product_service_owner_id',null)->get();

        return Inertia::render('Modules/Customer/index', compact('filters', 'customers', 'product_services','trees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // return $request;


        $this->validate(
            $request,
            [
                'name' => 'required',
                'username' => 'required|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'required|unique:users,phone_number|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'password' => 'nullable',
                'product_service' => 'nullable',

            ]
        );
        $customer = User::create($request->all());
        $roles = 'customer';
        $customer->assignRole($roles);
        if ($request->password) {
            $customer->password = Hash::make($request->password);
        }
        $customer->save();
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

                $new_product_owner->trees()->saveMany($trees);

                $customer->save();

                //history
                $time_life = (int)$this->checkDay($product_service->life_time,$product_service->unit);
                $time_limit = Carbon::parse($request->time_approve)->addDays($time_life);

                $history_extend = new HistoryExtend;
                $history_extend->price = $product_service->price;
                $history_extend->product_name = $product_service->name;
                $history_extend->date_from = $request->time_approve;
                $history_extend->date_to = $time_limit;
                $history_extend->description = "tạo mới";
                $new_product_owner->history_extend()->save($history_extend);
            }

        }
        return back()->with('success', 'Create customer successfully');
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
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('customer::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('customer::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->validate(
            $request,
            [
                'name' => 'required',
                'username' => 'required|unique:users,username,' . $user->id,
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,phone,' . $user->id,
                'password' => 'nullable'
            ]
        );

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number
        ]);
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return back()->with('success', 'Update user successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        foreach($user->product_service_owners as $product_service_owner){
            foreach($product_service_owner->trees() as $tree){
                $tree->product_service_owner_id = null;
                $tree->save();
            }
        }
        $user->product_service_owners()->delete();
        $user->delete();
        return redirect()->back()->with('success', "Xóa tài khoản  thành công");
    }
    public function setActive(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->isActive = $request->active;
        $user->save();
        return back()->with('success', 'Update user successfully');
    }
}
