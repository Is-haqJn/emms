<?php

namespace App\Http\Controllers;
use App\Http\Requests\ChangePasswordRequest;
use Auth;
use Hash;

class ChangePasswordController extends Controller {
	public function changePassword() {
		$index['page'] = 'Change Password';
		return view('change-password', $index);
	}

	public function updatePassword(ChangePasswordRequest $request) {
		if ($request->new_password) {
			if ($request->new_password) {

				if (Auth::attempt(['id' => Auth::user()->id, 'password' => $request->old_password])) {
					$change = Auth::user();
					if (Hash::check($request->new_password, $change->password)) {
						return redirect()->back()->withErrors('New password must be different from the old password.');
					}
					$change->password = bcrypt($request->new_password);
					$change->save();
					return redirect(route('change-password'))->with('flash_message', 'Password changed successful');
				} else {
					return redirect()->back()->withErrors('Old password is wrong.');
				}
			}

		} else {
			return redirect(route('change-password'))->with('flash_message_error', 'No Changes made.');

		}

	}
}
