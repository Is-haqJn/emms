<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalibrationsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('calibrations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->integer('equip_id')->nullable();;
			$table->date('date_of_calibration')->nullable();
			$table->date('due_date')->nullable();
			$table->string('certificate_no')->nullable();
			$table->string('company')->nullable();
			$table->string('contact_person')->nullable();
			$table->string('contact_person_no')->nullable();
			$table->string('engineer_no')->nullable();
			$table->string('traceability_certificate_no')->nullable();
			$table->string('calibration_certificate')->nullable();
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
		Schema::dropIfExists('calibrations');
	}
}
