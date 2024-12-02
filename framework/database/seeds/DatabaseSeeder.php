<?php

use Database\Seeders\RoleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run() {
		$this->call([
			Tableseeder::class,
			DemoDataseeder::class,
			RoleSeeder::class
		]);
	}
}