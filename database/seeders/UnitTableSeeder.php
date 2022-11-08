<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::updateOrCreate(['id' => 1], [
            'name' => 'Gram',
            'slug' => 'gram',
            'description' => 'Gram',
            'status' => 'active',
        ]);

        Unit::updateOrCreate(['id' => 2], [
            'name' => 'KG',
            'slug' => 'kg',
            'description' => 'KG',
            'status' => 'active',
        ]);

        Unit::updateOrCreate(['id' => 3], [
            'name' => 'Piece',
            'slug' => 'piece',
            'description' => 'Piece',
            'status' => 'active',
        ]);

        Unit::updateOrCreate(['id' => 4], [
            'name' => 'Packet',
            'slug' => 'packet',
            'description' => 'packet',
            'status' => 'active',
        ]);

        Unit::updateOrCreate(['id' => 5], [
            'name' => 'Dozen',
            'slug' => 'dozen',
            'description' => 'Dozen',
            'status' => 'active',
        ]);
    }
}
