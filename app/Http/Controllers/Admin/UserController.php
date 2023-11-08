<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
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
            $users =  User::with('roles', 'tokens')->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
                $query->orwhere('email', 'LIKE', '%' . $request->search . '%');
                // $query->orwhere('phone', 'LIKE', '%' . $request->term . '%');
            })->where('created_byId', $user->id)->paginate(20)->appends($request->search);
            $roles = Role::where('name', 'saler')->get();
        }
        return Inertia::render('Admin/User', compact('filters', 'users', 'roles', 'subadmins'));
    }

    public function store(Request $request)
    {

        $auth_user = Auth::user();
        $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'required|unique:users,phone_number|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'roles' => 'required',
                // 'time_limit' => 'nullable|date|after:tomorrow',
                'created_byId' => 'nullable',
                'password' => 'nullable',

            ]
        );

        $user = User::create($request->all());
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->assignRole($roles);
        if ($request->created_byId) {
            $user->created_byId = $request->created_byId;
        } else {
            $user->created_byId = $auth_user->id;
        }
        // $user->created_byId = Auth::user()->id;
        if ($request->password) {
            $user->password = Hash::make($request->password);
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
                'email' => 'required|email|unique:users,email,' . $user->id,
                // 'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,phone,' . $user->id,
                'roles' => 'required',
                // 'time_limit' => 'nullable|date|after:tomorrow',
                // 'number_device' => 'nullable|numeric|gt:-1',
                'created_byId' =>  'nullable',
                'password' => 'nullable'
            ]
        );

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        if ($request->created_byId) {
            $user->created_byId = $request->created_byId;
        } else {
            $user->created_byId = Auth::user()->id;
        }
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->syncRoles($roles);
        // $user->created_byId = Auth::user()->id;
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
