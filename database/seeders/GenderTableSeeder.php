<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genders')->insert([
            'gender' => "Male",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('genders')->insert([
            'gender' => "Female",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('genders')->insert([
            'gender' => "Other inclined towards male",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('genders')->insert([
            'gender' => "Other inclined towards female",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
