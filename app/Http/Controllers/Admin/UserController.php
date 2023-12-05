<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\MakeLeaderJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Modules\Customer\app\Models\ReviewManagement;
use Spatie\Permission\Models\Role;

class UserController extends Controller
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
        // $this->makeUsers();
        // $user = Auth::user();
        // return $user->leaders->pluck('id');
        $user = Auth::user();

        $filters = $request->all('search');
        $subadmins = User::whereHas(
            'roles',
            function ($query) {
                $query->where('name', 'subadmin');
            }
        )->get();

        if ($user->hasRole('super-admin')) {
            $users =  User::with('roles', 'tokens', 'team')->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
                $query->orwhere('email', 'LIKE', '%' . $request->search . '%');
                // $query->orwhere('phone', 'LIKE', '%' . $request->term . '%');
            })->paginate(20)->appends($request->search);
            $roles = Role::get();
        } elseif ($user->hasRole('leader-sale')) {
            $users =  User::with('roles', 'tokens', 'team')->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
                $query->orwhere('email', 'LIKE', '%' . $request->search . '%');
                // $query->orwhere('phone', 'LIKE', '%' . $request->term . '%');
            })->where('created_byId', $user->id)->paginate(20)->appends($request->search);
            $roles = Role::where('name', 'saler')->get();
        }elseif ($user->hasRole('leader-shipper')) {
            $users =  User::with('roles', 'tokens', 'team')->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
                $query->orwhere('email', 'LIKE', '%' . $request->search . '%');
                // $query->orwhere('phone', 'LIKE', '%' . $request->term . '%');
            })->where('created_byId', $user->id)->paginate(20)->appends($request->search);
            $roles = Role::where('name', 'saler')->get();
        } else {
            return  abort(403);
        }
        foreach($users as $us){
            if($us->doesntHave('roles')){
                // dd($us);
                // if(str_contains($us->email,'leader')){
                //     $us->assignRole('leader-sale');
                // }
                if(str_contains($us->email,'sale')){
                    $us->assignRole('saler');
                }
            }

         }
        return Inertia::render('Admin/User', compact('filters', 'users', 'roles'));
    }


    public function create(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            $roles = Role::where('name', '!=', 'super-admin')->get();
            $leader_sales = User::role('leader-sale')->get();
            $leader_shipper = User::role('leader-shipper')->get();
        } elseif($user->hasRole('leader-sale')) {
            $roles = Role::where('name', 'saler')->get();
            $leader_sales = null;
            $leader_shipper =null;

        }
        elseif($user->hasRole('leader-shipper')) {
            $roles = Role::where('name', 'shipper')->get();
            $leader_sales = null;
            $leader_shipper =null;
        }

        return Inertia::render('Admin/CreateUser', compact('roles', 'leader_sales','leader_shipper'));
    }


    public function edit(Request $request, User $user)
    {

        $user->load('roles');
        $user_auth = Auth::user();
        if ($user_auth->hasRole('super-admin')) {
            $leader_sales = User::role('leader-sale')->get();
            $roles = Role::where('name', '!=', 'super-admin')->get();
        } else {
            $roles = Role::where('name', 'saler')->get();
            $leader_sales = null;
        }
        return Inertia::render('Admin/EditUser', compact('roles', 'user', 'leader_sales'));
    }


    public function store(Request $request)
    {

        $auth_user = Auth::user();
        $this->validate(
            $request,
            [
                'name' => 'required',
                'cic_number' => 'required|unique:users,cic_number',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'required|unique:users,phone_number|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',

                'city' => 'nullable',
                'wards' => 'nullable',
                'district' => 'nullable',
                'date_of_birth' => 'nullable|date',

                'cic_date' => 'nullable|date',
                'cic_date_expried' => 'nullable|date|after:cic_date',

                'created_byId' => 'nullable',
                'password' => 'nullable',
                'roles' => 'required'

            ]
        );

        $user = User::create([
            'name' => $request->name,
            'cic_number' => $request->cic_number,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'sex' => $request->sex,
            'address' => $request->address,
            'city' => $request->city,
            'wards' => $request->wards,
            'district' => $request->district,
            'date_of_birth' => $request->date_of_birth,
            'cic_date' => $request->cic_date,
            'cic_date_expried' => $request->cic_date_expried,
        ]);
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->assignRole($roles);
        if ($auth_user->hasRole('leader-sale')) {
            $user->created_byId = $auth_user->id;
            $user->save();
        }
        if ($auth_user->hasRole('leader-shipper')) {
            $user->created_byId = $auth_user->id;
            $user->save();
        }
        if ($auth_user->hasPermissionTo('super-admin')) {
            if ($request->leader_sale_id && !$user->hasRole('leader-sale')) {
                $lead_sale = User::findOrFail($request->leader_sale_id);
                $user->created_byId = $lead_sale->id;
                $user->save();
            }
        }
        if ($request->password) {
            $user->password = Hash::make($request->password);
        } else {
            $user->password = Hash::make('cammattroi');
        }
        $user->save();
        return redirect()->route('users.index')->with('success', 'Tạo mới thành công');
 
    }

    public function update(Request $request, $id)
    {
        $auth_user = Auth::user();
        $user = User::findOrFail($id);

        $this->validate(
            $request,
            [
                'name' => 'required',
                'cic_number' => 'required|unique:users,cic_number,' . $user->id,
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,phone_number,' . $user->id,
                'sex' => 'required',
                'address' => 'required',
                'city' => 'nullable',
                'wards' => 'nullable',
                'district' => 'nullable',
                'date_of_birth' => 'nullable|date',
                'cic_date' => 'nullable|date',
                'cic_date_expried' => 'nullable|date|after:cic_date',
                'created_byId' => 'nullable',
            ]
        );

        $user->update([
            'name' => $request->name,
            'cic_number' => $request->cic_number,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'sex' => $request->sex,
            'address' => $request->address,
            'city' => $request->city,
            'wards' => $request->wards,
            'district' => $request->district,
            'date_of_birth' => $request->date_of_birth,
            'cic_date' => $request->cic_date,
            'cic_date_expried' => $request->cic_date_expried,

        ]);
        if ($auth_user->hasPermissionTo('super-admin')) {
            if ($request->leader_sale_id && !$user->hasRole('leader-sale')) {
                $lead_sale = User::findOrFail($request->leader_sale_id);
                $user->created_byId = $lead_sale->id;
                $user->save();
            } else {
                $user->created_byId = null;
                $user->save();
            }
        }

        if ($auth_user->hasPermissionTo('super-admin')) {
            if ($request->leader_shipper_id && !$user->hasRole('leader-shipper')) {
                $lead_sale = User::findOrFail($request->leader_shipper_id);
                $user->created_byId = $lead_sale->id;
                $user->save();
            } else {
                $user->created_byId = null;
                $user->save();
            }
        }
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->syncRoles($roles);

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return redirect()->route('users.index')->with('success',  'Cập nhật user thành công');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // return   $this->DeleteUser($user);
        // $user->delete();
        return redirect()->back()->with('success', "Xóa tài khoản  thành công");
    }
    public function setActive(Request $request)
    {


        $user = User::findOrFail($request->id);
        $user->isActive = $request->active;
        $user->save();
        return back()->with('success', 'Update user successfully');
    }


    public function approveUserInfor(User $user)
    {
        $infor = $user->lastInfoChange;
        if ($infor) {
            if ($infor->status == 0) {
                $user->update([
                    'name' => $infor->name,
                    'email' => $infor->email,
                    'cic_number' => $infor->cic_number,
                    'phone_number' => $infor->phone_number,
                    'sex' => $infor->sex,
                    'address' => $infor->address,
                    'date_of_birth' => $infor->date_of_birth,
                    'city' => $infor->city,
                    'district' => $infor->district,
                    'wards' => $infor->wards,
                    'cic_date' => $infor->cic_date,
                    'cic_date_expried' => $infor->cic_date_expried,
                    'status' => true,
                ]);
                return back()->with('success', 'Cập nhật thành công');
            } else {
                return back()->with('warning', 'Thông tin này đã được cập nhật rồi ');
            }
            return back()->with('success', 'Cập nhật thành công');
        }
        return back()->with('warning', 'Không tìm thấy thông tin  ');
    }


    public function makeUsers()
    {
        for ($i = 1; $i <= 30; $i++) {
            dispatch(new MakeLeaderJob($i));
        }
    }

    public function DeleteUser($user){
        $admin = User::role("super-admin")->first();
        $orders_sale= $user->saler_orders()->get();
        $orders_shipper= $user->shipper_orders()->where("status", 'pending')->where('payment_status', 0)->get();
        $orders_package_sale= $user->sale_order_packages()->get();
        foreach($orders_shipper as $order){
            $order->shipper_id = null;
            $order->save();
        }
        $leader = User::find($user->created_byId);
        // Update order_package
        foreach($orders_package_sale as $order){
            if($leader){
                $order->sale_id = $leader->id;
            }
            else{
                $order->sale_id = $admin->id;
            }

            $order->save();
        }
        // Update orders_sale
        foreach($orders_sale as $order){
            if($leader){
                $order->sale_id = $leader->id;
            }
            else{
                $order->sale_id = $admin->id;
            }

            $order->save();
        }
    }
}
