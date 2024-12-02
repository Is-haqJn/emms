<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceRenderedItem extends Model {
	protected $fillable = ['name'];
	protected $table = 'service_rendered_items';
	protected $guard_name = 'web';
}
