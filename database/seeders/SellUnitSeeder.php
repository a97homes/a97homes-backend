<?php

namespace Database\Seeders;

use App\Models\SellUnit;
use Illuminate\Database\Seeder;

class SellUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SellUnit::factory()->count(10)->create();
    }
}
