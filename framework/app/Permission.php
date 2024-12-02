<?php

namespace App;
use Illuminate\Notifications\Notifiable;

class Permission extends \Spatie\Permission\Models\Permission {
	use Notifiable;
	protected $guard_name = 'web';
}
