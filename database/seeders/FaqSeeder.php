<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\SubArea;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'question' => [
                    'en' => 'Where is this area located?',
                    'ar' => 'أين تقع هذه المنطقة؟',
                ],
                'answer' => [
                    'en' => 'It is strategically located with easy access to main roads, airports, and key destinations.',
                    'ar' => 'تقع المنطقة في موقع استراتيجي مع سهولة الوصول إلى الطرق الرئيسية والمطارات والوجهات المهمة.',
                ],
            ],
            [
                'question' => [
                    'en' => 'What are the most famous compounds in this area?',
                    'ar' => 'ما هي أشهر الكمبوندات في هذه المنطقة؟',
                ],
                'answer' => [
                    'en' => 'The area features a variety of high-end compounds developed by leading real estate companies.',
                    'ar' => 'تضم المنطقة مجموعة متنوعة من الكمبوندات الراقية التي تطورها كبرى شركات التطوير العقاري.',
                ],
            ],
            [
                'question' => [
                    'en' => 'What is the starting price range in this area?',
                    'ar' => 'ما هو نطاق الأسعار في هذه المنطقة؟',
                ],
                'answer' => [
                    'en' => 'Prices vary based on the compound, unit type, and finishing. Contact us for the latest offers.',
                    'ar' => 'تختلف الأسعار حسب الكمبوند ونوع الوحدة والتشطيب. تواصل معنا للاطلاع على أحدث العروض.',
                ],
            ],
            [
                'question' => [
                    'en' => 'Are there payment plans available?',
                    'ar' => 'هل تتوفر خطط سداد ميسرة؟',
                ],
                'answer' => [
                    'en' => 'Yes, multiple developers offer installment plans up to 10 years with competitive down payments.',
                    'ar' => 'نعم، تقدم العديد من شركات التطوير خطط تقسيط تصل حتى 10 سنوات مع مقدمات تنافسية.',
                ],
            ],
            [
                'question' => [
                    'en' => 'Which delivery dates are available?',
                    'ar' => 'ما هي مواعيد التسليم المتاحة؟',
                ],
                'answer' => [
                    'en' => 'Delivery dates range from immediate to upcoming years depending on the project phase.',
                    'ar' => 'تتراوح مواعيد التسليم بين الفوري والسنوات المقبلة حسب مرحلة المشروع.',
                ],
            ],
        ];

        SubArea::query()->whereHas('compounds')->limit(10)->get()->each(function (SubArea $subArea) use ($samples) {
            foreach ($samples as $index => $item) {
                Faq::updateOrCreate(
                    [
                        'faqable_type' => (new SubArea)->getMorphClass(),
                        'faqable_id' => $subArea->id,
                        'sort_order' => $index,
                    ],
                    [
                        'question' => $item['question'],
                        'answer' => $item['answer'],
                        'is_active' => true,
                    ],
                );
            }
        });
    }
}
