<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['title' => 'Administrator']);
        Role::create(['title' => 'User']);
        Role::create(['title' => 'Store']);
    }
}
