<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UserTableSeeder::class);
        $this->call(GenderTableSeeder::class);
        $this->call(HairTypeTableSeeder::class);
        $this->call(HairLengthTableSeeder::class);
        $this->call(SkinTypeTableSeeder::class);
        $this->call(MaritalStatusTableSeeder::class);
        $this->call(UnitTableSeeder::class);
        $this->call(DiscountTypeTableSeeder::class);
    }
}
