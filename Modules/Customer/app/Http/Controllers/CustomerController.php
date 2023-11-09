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
use Spatie\Permission\Models\Role;
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
        $customers = User::whereHas(
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


        return Inertia::render('Modules/Customer/index', compact('filters', 'customers'));
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
        $this->validate(
            $request,
            [
                'name' => 'required',
                'username' => 'required|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'required|unique:users,phone_number|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'password' => 'nullable',

            ]
        );
        $customer = User::create($request->all());
        $roles = 'customer';
        $customer->assignRole($roles);
        if ($request->password) {
            $customer->password = Hash::make($request->password);
        }
        $customer->save();
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
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,phone,' . $user->id,
                'roles' => 'required',
                'created_byId' =>  'nullable',
                'password' => 'nullable'
            ]
        );

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' =>$request->phone_number
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
