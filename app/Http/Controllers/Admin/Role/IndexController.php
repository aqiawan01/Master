<?php

namespace App\Http\Controllers\Admin\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Auth;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::orderBy('id', 'desc')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permission = Permission::get();
        return view('admin.roles.create',compact('permission'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
    
        $role = Role::create(['name' => $request->input('name'), 'added_by' => Auth::user()->id]);
        $role->syncPermissions($request->input('permission'));

        if ($role) {
            return 1;
        } else {
            return 0;
        }
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
        return view('admin.roles.edit',compact('role','permission','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
    
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->updated_by = Auth::user()->id;
        $role->save();
    
        $role->syncPermissions($request->input('permission'));

        if ($role) {
            return 1;
        } else {
            return 0;
        }
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        Toastr::success('Role deleted successfully.', 'Success');
       	return redirect()->route('roles.index');
    }
}
