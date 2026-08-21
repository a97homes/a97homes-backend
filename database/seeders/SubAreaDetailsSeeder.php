<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SubArea;
use Illuminate\Database\Seeder;

class SubAreaDetailsSeeder extends Seeder
{
    /**
     * Backfill area-specific details (description, coords, phones) for sub areas
     * that host compounds so the public area detail page has rich data.
     */
    public function run(): void
    {
        $details = [
            'El Alamein' => [
                'description' => [
                    'en' => 'New El Alamein is a charming coastal city considered one of the most important fourth-generation cities on the North Coast.',
                    'ar' => 'العلمين الجديدة مدينة ساحلية ساحرة تعتبر إحدى أهم مدن الجيل الرابع على الساحل الشمالي.',
                ],
                'latitude' => 30.8418,
                'longitude' => 28.9522,
            ],
            'New Cairo' => [
                'description' => [
                    'en' => 'New Cairo is one of the largest and most premium residential areas in Egypt, home to leading developers and compounds.',
                    'ar' => 'القاهرة الجديدة من أكبر المناطق السكنية الراقية في مصر وتضم أهم الكمبوندات وشركات التطوير.',
                ],
                'latitude' => 30.0300,
                'longitude' => 31.4900,
            ],
            '6th of October' => [
                'description' => [
                    'en' => '6th of October City is a major residential and industrial hub offering a wide variety of compounds and services.',
                    'ar' => 'مدينة السادس من أكتوبر مركز سكني وصناعي رئيسي يقدم مجموعة متنوعة من الكمبوندات والخدمات.',
                ],
                'latitude' => 29.9361,
                'longitude' => 30.9269,
            ],
            'Sheikh Zayed' => [
                'description' => [
                    'en' => 'Sheikh Zayed is an upscale residential district known for its modern compounds and quiet family environment.',
                    'ar' => 'الشيخ زايد منطقة سكنية راقية تشتهر بالكمبوندات الحديثة والأجواء الهادئة للعائلات.',
                ],
                'latitude' => 30.0769,
                'longitude' => 30.9688,
            ],
        ];

        foreach ($details as $nameEn => $data) {
            SubArea::query()
                ->where('name->en', $nameEn)
                ->each(function (SubArea $subArea) use ($data) {
                    $subArea->update([
                        'description' => $data['description'],
                        'latitude' => $data['latitude'],
                        'longitude' => $data['longitude'],
                    ]);
                });
        }
    }
}
