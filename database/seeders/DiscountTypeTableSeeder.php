<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscountTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('discount_types')->insert([
            'discount_type' => "percent",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('discount_types')->insert([
            'discount_type' => "flat",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        /* DB::table('discount_types')->insert([
            'discount_type' => "promo",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]); */
    }
}
