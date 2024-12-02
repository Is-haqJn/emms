<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if(Schema::hasTable('users')){

			return;
		} 
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->nullable();
			// $table->string('email')->unique()->nullable();
			$table->string('email')->nullable();
			$table->integer('role_id')->nullable();
			$table->string('password')->nullable();
			$table->string('image')->nullable();
			$table->boolean('select_all')->default(0);
			$table->softDeletes();
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('users');
	}
}
