<?php

namespace Database\Seeders;

use App\Models\CompanyInfo;
use Illuminate\Database\Seeder;

class CompanyInfoSeeder extends Seeder
{
    public function run(): void
    {
        CompanyInfo::firstOrCreate([], [
            'phone' => '+201097809259',
            'email' => 'a97homes@gmail.com',
            'working_hours' => 'من 9 صباحا حتي 6 مساءا',
            'address' => '١٦٩، سكن مصر، ارض المعارض، محور الجامعة الامريكية، التجمع الخامس، القاهرة الجديدة',
        ]);
    }
}
