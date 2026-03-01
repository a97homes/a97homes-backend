<?php

namespace Database\Seeders;

use App\Models\CompanyInfo;
use Illuminate\Database\Seeder;

class CompanyInfoSeeder extends Seeder
{
    public function run(): void
    {
        CompanyInfo::firstOrCreate([], [
            'phone' => '+1012 3456 789',
            'email' => 'info@a97infinity.com',
            'working_hours' => 'من 9 صباحا حتي 10 مساءا',
            'address' => 'القاهرة الجديدة، مصر أفنيو 22، الحي الثاني الشيخ زايد، مصر',
        ]);
    }
}
