<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    function __construct()
    {
        $this->middleware('role:super-admin');
    }

    public function index()
    {
        $permissions = Permission::get();
        return Inertia::render('Admin/Roles', ['roles' => Role::with('permissions')->paginate(10), 'permissions' => $permissions]);
    }


    public function store(Request $request)
    {
            $this->validate($request, [
                'name' => 'required|unique:roles',
                'permission' =>  'required',
               
            ]);
            $role = Role::create($request->except('permission'));
            $role->save();
            $permissions = $request->input('permission') ? $request->input('permission') : [];
            $role->givePermissionTo($permissions);
            return redirect()->back()->with('success', 'Create role successfully');
   
    }
    public function update(Request $request, $id)
    {
            $role = Role::findOrFail($id);
            $this->validate($request, [
                'name' => 'required|unique:roles,name,' . $role->id,
                'limit_device' => 'nullable|numeric|gt:0'
            ]);
            $role->update($request->except('permission'));
            $role->save();
            $permissions = $request->input('permission') ? $request->input('permission') : [];
            $role->syncPermissions($permissions);
            return redirect()->back()->with('success', 'Update role successfully');
    }

    public function delete($id)
    {
            $role = Role::findOrFail($id);
            $role->delete();
            return redirect()->back()->with('success', 'Delete role successfully');
     
    }
}
