<?php

namespace App\Http\Middleware;

use Closure;

class InstalledOrNot {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {

		if ($this->alreadyInstalled() == false) {
			return redirect('install');
		}

		return $next($request);
	}

	public function alreadyInstalled() {

		return file_exists(storage_path('installed'));

	}
}
