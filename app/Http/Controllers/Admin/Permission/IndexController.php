<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Permission;
use Auth;

class IndexController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('id', 'desc')->get();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $input = $request->all();
        $input['added_by'] = Auth::user()->id;

        $permission = Permission::create($input);

        if ($permission) {
            return 1;
        } else {
            return 0;
        }
    }

    public function edit($id)
    {
        $permission = Permission::find($id);

        return view('admin.permissions.edit',compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        $this->validate($request, [
            'name' => 'required',
        ]);

        $input = $request->all();
        $input['updated_by'] = Auth::user()->id;
    
        $permission->update($input);

        if ($permission) {
            return 1;
        } else {
            return 0;
        }
    }

    public function destroy($id)
    {
        $permission = Permission::find($id);
        $permission->delete();

        Toastr::success('Permission deleted successfully', 'Success');
       	return redirect()->route('permissions.index');
    }
}
