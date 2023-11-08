<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('role:super-admin');
    // }
    public function index()
    {

        return   Inertia::render('Admin/Permission', ['permissions' => Permission::paginate(10)]);
    }

    public function store(Request $request)
    {


        $this->validate($request, [
            'name' => 'required|unique:permissions',
        ]);

        Permission::create($request->all());

        return back()->with('success', 'Create permission successfully');
    }
    public function update(Request $request, $id)
    {

        $permission = Permission::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $permission->name = $request->name;
        $permission->save();

        return redirect()->back()->with('success', 'Update permission successfully');
    }
    public function delete($id)
    {


        $permission = Permission::find($id);

        if ($permission == null) {
            $msg = [
                'msg' => 'The permission is not found'
            ];
            return response()->json($msg, Response::HTTP_BAD_REQUEST);
        }
        $permission->delete();
        return redirect()->back()->with('success', `Delete {{$permission->name}} permission successfully`);
    }
}
