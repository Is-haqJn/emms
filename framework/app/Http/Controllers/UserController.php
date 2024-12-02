<?php

namespace App\Http\Controllers;

use App\Hospital;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Trait\PermissionModule;
use DB;
class UserController extends Controller
{
	use PermissionModule;
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index()
	{
		$this->availibility('View Users');
		

		$index['page'] = 'users';
		$index['users'] = User::all();

		return view('users.index', $index);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$this->availibility('Create Users');
		$index['page'] = 'users';
		$index['roles'] = Role::all();
		$index['hospitals'] = Hospital::all();
		return view('users.create', $index);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(UserCreateRequest $request)
	{
		$user = DB::table('users')->where('email',$request->email)->first();
		if(!is_null($user)){
			return redirect()->back()->with('flash_message', 'User before same email Alreay Exist Please Contact super adminstator for further query.')->withInput();
		}
		// dd($user);
		$user = User::create($request->only('name', 'email'));
		$user->password = bcrypt($request->password);
		$user->assignRole($request->role);
		$role = Role::find($request->role);
		$r_p = $role->permissions->pluck('name')->toArray();
		$user->syncPermissions($r_p);
		if ($request->select_all == 1) {
			$user->select_all = 1;
		} else {
			$user->select_all = 0;
		}
		$user->save();
		$user->hospitals()->sync($request->hospitals);
		// dd($user);
		return redirect()->route('users.index')
			->with(
				'flash_message',
				'User "' . $user->name . '" added.'
			);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$this->availibility('Edit Users');
		$index['permission_array'] = $this->module()['permission_array'];
		$index['module_names'] = $this->module()['module_names'];
		$index['page'] = 'users';
		$index['user'] = User::findOrFail($id);
		$index['roles'] = Role::all();
		$index['hospitals'] = Hospital::all();
		$permissions = Permission::all();
		$index['selectedHospitalIds'] = $index['user']->hospitals->pluck('id')->toArray();
		// $role_p = $index['user']->getPermissionsViaRoles()->pluck('id')->toArray();
		//  dd(\Auth::user()->givePermissionTo(Permission::get()));  
		// $index['permissions'] = Permission::whereNotIn('id', $role_p)
		// 	->get();
		$index['permissions'] = Permission::get();

		return view('users.edit', $index);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(UserUpdateRequest $request, $id)
	{
		$user = DB::table('users')->where('email', $request->email)->where('id','!=',$id)->first();
		if (!is_null($user)) {
			return redirect()->back()->with('flash_message', 'User before same email Alreay Exist Please Contact super adminstator for further query.')->withInput();
		}
		// dd($request->all());
		$user = User::findOrFail($id);
		$user->update($request->only('name', 'email'));
		if ($request->password != null) {

			$user->password = bcrypt($request->password);
		}

		// $user->password = bcrypt($request->password);

		if ($request->role) {
			$user->roles()->sync($request->role);
		} else {
			$user->roles()->detach();
		}

		// if ($request->role != $user->role_id) {
		// 	$role = Role::find($request->role);
		// 	$r_p = $role->permissions->pluck('name')->toArray();
		// 	$user->syncPermissions($r_p);
		// } 
		// else {
			if (isset($request->permissions)) {
				$perm = Permission::whereIn('name', $request->permissions)->get();
				$user->syncPermissions($perm->pluck('name')->toArray());
				// dd($perm->pluck('name'));
			} else {
				$user->revokePermissionTo(Permission::pluck('name')->toArray());
			}
		// }
		$user->role_id = $request->role;
		if ($request->select_all == 1) {
			$user->select_all = 1;
		} else {
			$user->select_all = 0;
		}
		$user->save();
		$user->hospitals()->sync($request->hospitals);
		app()['cache']->forget('spatie.permission.cache');

		return redirect()->route('users.index')
			->with(
				'flash_message',
				'User "' . $user->name . '" updated.'
			);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$user = User::findOrFail($id);
		$this->availibility('Delete Users');

		if ($user->id == \Auth::user()->id) {
			return redirect('admin/users')->with(
				'flash_message_error',
				' Logged In User  can not Delete.'
			);
		}
		$user->delete();
		return redirect('admin/users')->with(
			'flash_message',
			'User "' . $user->name . '" deleted.'
		);
	}
	public static function availibility($method)
	{
		// $r_p = \Auth::user()->getPermissionsViaRoles()->pluck('name')->toArray();
		if (\Auth::user()->hasDirectPermission($method)) {
			return true;
		} else {
			abort('401');
		}
		// elseif (!in_array($method, $r_p)) {
		// 	abort('401');
		// }
	}
	public function user_permissions(Request $request){
		$index['data'] = User::find($request->user_id);
		$index['permission_array'] = $this->module()['permission_array'];
		$index['module_names'] = $this->module()['module_names'];
		$index['view'] = view('user_role_permission.table_edit_user')->with($index)->render();
		return response()->json($index);
	}
}
