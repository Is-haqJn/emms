<?php
namespace App\Http\Controllers;
use App\Http\Requests\RoleCreate;
use App\Permission;
use Illuminate\Http\Request;
use  App\Role;
use App\User;
use App\Trait\PermissionModule;
class RoleController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	use PermissionModule;
	public function index() {
		$index['page'] = 'roles';
		$index['roles'] = Role::all();
		$this->availibility('View Roles');
		return view('roles.index', $index);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$index['page'] = 'roles';
		$this->availibility('Create Roles');
		$index['permission_array'] = $this->module()['permission_array'];
		$index['module_names'] = $this->module()['module_names'];
		$index['permissions'] = Permission::all();
		return view('roles.create', $index);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(RoleCreate $request) {
		$role = new Role;

		$role->name = $request->name;
		$role->save();
		$permissions = $request->permissions;
		$name = $request->name;
		foreach ($permissions as $permission) {
			$p = Permission::where('name', '=', $permission)->firstOrFail();
			$role = Role::where('name', '=', $name)->first();
			$role->givePermissionTo($p);
		}

		app()['cache']->forget('spatie.permission.cache');

		return redirect()->route('roles.index')
			->with('flash_message',
				'Role "' . $role->name . '" added!');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$index['page'] = 'roles';
		$this->availibility('Edit Roles');
		$index['permission_array'] = $this->module()['permission_array'];
		$index['module_names'] = $this->module()['module_names'];
		$index['role'] = Role::findOrFail($id);
		$index['permissions'] = Permission::all();

		return view('roles.edit', $index);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$role = Role::findOrFail($id);
		$role->name = $request->name;
		$role->save();
		$permissions = $request->permissions;
		$requestPermissions = array_map('intval', $permissions ?? []);
		$rolePermissions = $role->permissions->pluck('name')->toArray();
		// Check if there is a difference in permissions
		if (array_diff($requestPermissions, $rolePermissions) || array_diff($rolePermissions, $requestPermissions)) {
			// Retrieve users associated with the role
			$users = $role->users;

			// Get corresponding permissions from the database
			$permissionsToUpdate = Permission::whereIn('name', $requestPermissions)->get();

			// Sync permissions for each user
			foreach ($users as $user) {
				$user->syncPermissions($permissionsToUpdate);
			}
		}
		foreach ($role->permissions as $rp) {
			$p = Permission::where('id', '=', $rp->id)->get();
			$role->revokePermissionTo($p);
		}
		if (isset($permissions)) {
			foreach ($permissions as $permission) {
				$p = Permission::where('name', '=', $permission)->firstOrFail(); //Get corresponding form permission in db
				$role->givePermissionTo($p);

			}
		}
		app()['cache']->forget('spatie.permission.cache');
		return redirect()->route('roles.index')
			->with('flash_message',
				'Role "' . $role->name . '" Updated!');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$role = Role::findOrFail($id);
		$this->availibility('Delete Roles');
		$role->delete();

		return redirect()->route('roles.index')
			->with('flash_message',
				'Role ' . $role->name . ' Deleted!');
	}
	public static function availibility($method) {
		$r_p = \Auth::user()->getPermissionsViaRoles()->pluck('name')->toArray();
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
	public function role_permissions(Request $request){
		$role = Role::find($request->role_id);
		// dd($role->permissions);
		$role_permissions = $role->permissions->pluck('name');
		return response()->json($role_permissions);
	}
}
