<?php

namespace App\Http\Controllers;
use App\Http\Requests\PermissionRequest;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$this->availibility('View Permissions');

		$index['page'] = 'permissions';
		$index['permissions'] = Permission::latest()->get();

		return view('permissions.index', $index);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$this->availibility('Create Permissions');
		$index['page'] = 'permissions';
		return view('permissions.create', $index);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(PermissionRequest $request) {
		$permission = new Permission;
		$permission->name = $request->name;
		$permission->save();
		$p = Permission::where('id', '=', $permission->id)->firstOrFail();
		$role = Role::where('name', '=', 'Admin')->first();
		$role->givePermissionTo($p);
		return redirect('admin/permissions')->with('flash_message', 'Permission "' . $permission->name . '" created');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$index['page'] = 'permissions';
		$index['permission'] = Permission::findOrFail($id);
		$this->availibility('Edit Permissions');
		return view('permissions.edit', $index);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(PermissionRequest $request, $id) {
		$permission = Permission::findOrFail($id);
		$permission->name = $request->name;
		$permission->save();
		return redirect('admin/permissions')->with('flash_message', 'Permission "' . $permission->name . '" updated');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$permission = Permission::findOrFail($id);
		$this->availibility('Delete Permissions');
		$permission->delete();

		return redirect()->route('permissions.index')
			->with('flash_message',
				'Permission "' . $permission->name . '" Deleted!');
	}
	public static function availibility($method) {
		// $r_p = \Auth::user()->getPermissionsViaRoles()->pluck('name')->toArray();
		if (\Auth::user()->hasDirectPermission($method)) {
			return true;
		} else {
			abort('401');
		}
		// if (\Auth::user()->hasDirectPermission($method)) {
		// 	return true;
		// } elseif (!in_array($method, $r_p)) {
		// 	abort('401');
		// } else {
		// 	return true;
		// }
	}
}
