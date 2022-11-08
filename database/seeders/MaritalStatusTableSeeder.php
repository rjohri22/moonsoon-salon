<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MaritalStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('marital_statuses')->insert([
            "marital_status" => "Married",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('marital_statuses')->insert([
            "marital_status" => "Unmarried",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
