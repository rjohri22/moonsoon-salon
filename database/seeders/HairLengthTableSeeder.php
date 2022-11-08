<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HairLengthTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("hair_lengths")->insert([
            "hair_length" => "Below Shoulder",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table("hair_lengths")->insert([
            "hair_length" => "Upto Shoulder",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table("hair_lengths")->insert([
            "hair_length" => "Upto Waist",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table("hair_lengths")->insert([
            "hair_length" => "Upto Neck",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
