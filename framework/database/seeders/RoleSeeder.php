<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Role;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate(['name'=>'admin']);
        Role::create(['name'=>'user']);
        Role::create(['name'=>'writter']);
        Role::create(['name'=>'Guest']);
       
    }
}
