<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        $user = Auth::user();
        $filters = $request->all('search');
        $subadmins = User::whereHas(
            'roles',
            function ($query) {
                $query->where('name', 'subadmin');
            }
        )->get();
        if ($user->hasRole('super-admin')) {
            $users =  User::with('roles', 'tokens')->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
                $query->orwhere('email', 'LIKE', '%' . $request->search . '%');
                // $query->orwhere('phone', 'LIKE', '%' . $request->term . '%');
            })->paginate(20)->appends($request->search);
            $roles = Role::get();
        } else {
            return  abort(403);
        }
        return Inertia::render('Admin/User', compact('filters', 'users', 'roles', 'subadmins'));
    }


    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('super-admin')) {
            $roles = Role::where('name', '!=', 'super-admin')->get();
        } else {
            return  abort(403);
        }
        return Inertia::render('Admin/CreateUser', compact('roles'));
    }


    public function edit(Request $request, User $user)
    {

        $user->load('roles');
        $user_auth = Auth::user();
        if ($user_auth->hasRole('super-admin')) {

            $roles = Role::where('name', '!=', 'super-admin')->get();
        } else {
            return  abort(403);
        }
        return Inertia::render('Admin/EditUser', compact('roles', 'user'));
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
                'sex' => 'required',
                'address' => 'required',

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

        $user = User::create($request->all());
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->assignRole($roles);
        if ($request->password) {
            $user->password = Hash::make($request->password);
        } else {
            $user->password = Hash::make('cammattroi');
        }
        $user->save();
        return back()->with('success', 'Create user successfully');
    }

    public function update(Request $request, $id)
    {

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
                'password' => 'nullable',
                'city' => 'nullable',
                'wards' => 'nullable',
                'district' => 'nullable',
                'date_of_birth' => 'nullable|date',

                'cic_date' => 'nullable|date',
                'cic_date_expried' => 'nullable|date|after:cic_date',

                'created_byId' => 'nullable',
                'password' => 'nullable',
            ]
        );

        $user->update($request->all());
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->syncRoles($roles);

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return back()->with('success', 'Update user successfully');
    }

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
