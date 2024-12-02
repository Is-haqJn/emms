<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if(Schema::hasTable('settings')){
            return;
        }
		Schema::create('settings', function (Blueprint $table) {
			$table->increments('id');
			$table->string('company')->nullable();
			$table->string('logo')->nullable();
			$table->string('favicon')->nullable();
			$table->integer('allow_guest')->default(0); 
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
		Schema::dropIfExists('settings');
	}
}
