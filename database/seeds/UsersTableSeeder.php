<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'admin@marketplace.local',
            'password' => bcrypt('123456'),
            'firstname' => 'Administrator',
            'role_id' => Role::ADMINISTRATOR
        ]);
    }
}
