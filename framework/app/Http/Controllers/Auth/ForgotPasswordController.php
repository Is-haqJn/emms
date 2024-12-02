<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
class ForgotPasswordController extends Controller {
	/*
		    |--------------------------------------------------------------------------
		    | Password Reset Controller
		    |--------------------------------------------------------------------------
		    |
		    | This controller is responsible for handling password reset emails and
		    | includes a trait which assists in sending these notifications from
		    | your application to your users. Feel free to explore this trait.
		    |
	*/

	use SendsPasswordResetEmails {
		sendResetLinkEmail as protected traitsendResetLinkEmail;
	}

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	function sendResetLinkEmail(Request $request) {
		if (!$this->email_setup()) {
			return redirect(route('login'))->with('flash_message_error', 'No Email Setup done.');
		}
		return $this->traitsendResetLinkEmail($request);

	}

	public function __construct() {
		$this->middleware('guest');
	}

	public function email_setup() {
		if (!empty(env('MAIL_DRIVER')) &&
			!empty(env('MAIL_HOST')) &&
			!empty(env('MAIL_PORT')) &&
			!empty(env('MAIL_USERNAME')) &&
			!empty(env('MAIL_PASSWORD')) &&
			!empty(env('MAIL_ENCRYPTION'))
		) {
			return true;
		}
		return false;
	}
}
