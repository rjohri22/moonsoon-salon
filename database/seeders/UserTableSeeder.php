<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(['email'=>'admin@gmail.com'],[
            'first_name' => 'monsoon',
            'last_name' => 'admin',
            'email' => 'admin@gmail.com',
            'mobile' => '9876543210',
            'gender' => '1',
            'password' => Hash::make('123456789')
        ]);
    }
}
