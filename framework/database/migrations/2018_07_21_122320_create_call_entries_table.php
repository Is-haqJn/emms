<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallEntriesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('call_entries', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->integer('equip_id')->nullable();
			$table->string('call_type')->nullable(); // breakdown/prventive
			$table->string('call_handle')->nullable(); //Internal/external
			$table->string('report_no')->nullable();
			$table->date('next_due_date')->nullable();
			$table->timestamp('call_register_date_time')->nullable();
			$table->timestamp('call_attend_date_time')->nullable();
			$table->timestamp('call_complete_date_time')->nullable();
			$table->integer('user_attended')->nullable(); //who attended
			$table->string('working_status')->nullable();
			$table->string('service_rendered')->nullable();
			$table->string('remarks')->nullable();
			$table->text('nature_of_problem')->nullable();
			$table->string('sign_of_engineer')->nullable();
			$table->string('sign_stamp_of_incharge')->nullable();
			$table->boolean('is_contamination')->nullable();
			$table->boolean('from_api')->default(0);
			// $table->string('working_status')->nullable();
			$table->SoftDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('call_entries');
	}
}
