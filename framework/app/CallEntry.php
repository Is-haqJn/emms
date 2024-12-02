<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallEntry extends Model {
	use SoftDeletes;
	protected $table = 'call_entries';
	protected $guard_name = 'web';
	protected $fillable = [
		'user_id', 'equip_id', 'call_type', 'call_handle', 'report_no',
		'next_due_date', 'call_register_date_time', 'call_attend_date_time',
		'call_complete_date_time', 'user_attended', 'working_status',
		'service_rendered', 'remarks', 'nature_of_problem', 'sign_of_engineer',
		'sign_stamp_of_incharge', 'is_contamination',
	];
	public function equipment() {
		return $this->belongsTo('App\Equipment', 'equip_id')->withTrashed();
	}
	public function user() {
		return $this->belongsTo('App\User', 'user_id')->withTrashed();
	}
	public function user_attended_fn() {
		return $this->belongsTo('App\User', 'user_attended')->withTrashed();
	}
	public function scopeHospital($query)
	{
		return
			$query->join('equipments','call_entries.equip_id','=','equipments.id')->whereIn('equipments.hospital_id', auth()->user()->hospitals->pluck('id')->toArray())->select('call_entries.*');
	}

}
