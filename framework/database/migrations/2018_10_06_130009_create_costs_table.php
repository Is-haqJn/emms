<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if(Schema::hasTable('costs')){
            return;
        }
		Schema::create('costs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('hospital_id')->nullable();
			$table->string('type')->nullable();
			$table->string('cost_by')->nullable();
			$table->string('tp_name')->nullable();
			$table->string('tp_mobile')->nullable();
			$table->string('tp_email')->nullable();
			$table->text('equipment_ids')->nullable();
			$table->text('start_dates')->nullable();
			$table->text('end_dates')->nullable();
			$table->text('costs')->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('costs');
	}
}
