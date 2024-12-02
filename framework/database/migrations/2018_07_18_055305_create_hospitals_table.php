<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if(Schema::hasTable('hospitals')){
            return;
        }
		Schema::create('hospitals', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->string('name')->nullable();
			$table->string('slug', 8)->unique()->nullable();
			$table->string('email')->nullable();
			$table->string('contact_person')->nullable();
			$table->string('phone_no')->nullable();
			$table->string('mobile_no')->nullable();
			$table->text('address')->nullable();
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
		Schema::dropIfExists('hospitals');
	}
}
