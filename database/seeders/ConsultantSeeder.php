<?php

namespace Database\Seeders;

use App\Models\Consultant;
use App\Models\ConsultantPhone;
use Illuminate\Database\Seeder;

class ConsultantSeeder extends Seeder
{
    public function run(): void
    {
        Consultant::factory()
            ->count(10)
            ->has(ConsultantPhone::factory()->count(2), 'phones')
            ->create();

        Consultant::factory()
            ->count(3)
            ->featured()
            ->has(ConsultantPhone::factory()->count(2), 'phones')
            ->create();
    }
}
