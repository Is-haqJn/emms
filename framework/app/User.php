<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable {
	use Notifiable;
	use HasRoles;
	use SoftDeletes;
    use HasApiTokens, HasFactory;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $guard_name = 'web';
	protected $table = 'users';
	protected $fillable = [
		'name', 'email', 'password','role_id'
	];
	protected $metaTable = 'users_meta';
	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];
	protected function getMetaKeyName() {
		return 'user_id'; // The parent foreign key
	}
	public function role() {
		return $this->belongsTo('App\Role', 'role_id');
	}
	public function hospitals()
	{
		return $this->belongsToMany(Hospital::class);
	}
}
