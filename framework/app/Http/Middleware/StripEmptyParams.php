<?php

namespace App\Http\Middleware;

use Closure;

class StripEmptyParams {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		$query = request()->query();
		$querycount = count($query);
		foreach ($query as $key => $value) {
			if ($value == '') {
				unset($query[$key]);
			}
		}
		if ($querycount > count($query)) {
			$path = url()->current() . (!empty($query) ? '/?' . http_build_query($query) : '');
			return redirect()->to($path);
		}
		return $next($request);
	}
}
