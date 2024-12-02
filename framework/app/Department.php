<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model {
	use SoftDeletes;
	protected $table = 'departments';
	protected $guard_name = 'web';

	protected $fillable = ['name', 'short_name'];

	public function equipments() {
		return $this->hasMany('App\Equipment', 'department');
	}

}
