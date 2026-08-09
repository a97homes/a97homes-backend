<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'about-us',
                'sort_order' => 1,
                'title' => [
                    'en' => 'About Us',
                    'ar' => 'من نحن',
                ],
                'body' => [
                    'en' => 'With 97, you will find the most prominent real-estate developers in Egypt in one place. Buy or sell, pick the broker that suits you, compare every option, and let our smart chatbot help you reach the right decision.',
                    'ar' => 'مع 97 هتلاقي أشهر مطوري مصر في مكان واحد… تقدر تشتري أو تبيع، تختار البروكر اللي يناسبك، تقارن بين كل الخيارات، وكمان يساعدك شات بوت ذكي يوصلّك للقرار الصح.',
                ],
            ],
            [
                'slug' => 'terms-and-conditions',
                'sort_order' => 2,
                'title' => [
                    'en' => 'Terms & Conditions',
                    'ar' => 'الشروط والأحكام',
                ],
                'body' => [
                    'en' => 'These Terms & Conditions govern your use of the A97 Infinity platform. By accessing the service you agree to the stated obligations, usage policy, and liability limitations.',
                    'ar' => 'تحكم هذه الشروط والأحكام استخدامك لمنصة A97 Infinity. باستخدامك للخدمة، فإنك توافق على الالتزامات المذكورة وسياسة الاستخدام وحدود المسؤولية.',
                ],
            ],
            [
                'slug' => 'privacy-policy',
                'sort_order' => 3,
                'title' => [
                    'en' => 'Privacy Policy',
                    'ar' => 'سياسة الخصوصية',
                ],
                'body' => [
                    'en' => 'We respect your privacy. This policy explains what data we collect, how we use it, and the rights you have to access, correct, or delete it.',
                    'ar' => 'نحترم خصوصيتك. توضح هذه السياسة البيانات التي نجمعها وكيفية استخدامها، وحقوقك في الوصول إليها أو تصحيحها أو حذفها.',
                ],
            ],
        ];

        foreach ($pages as $attributes) {
            Page::updateOrCreate(
                ['slug' => $attributes['slug']],
                $attributes + ['is_published' => true, 'published_at' => now()],
            );
        }
    }
}
