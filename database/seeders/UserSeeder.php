<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = Admin::create([
            'username' => '超级管理员',
            'email' => 'super@a.com',
            'password' => bcrypt('123456')
        ]);

        $user->assignRole('super_admin');
    }
}
