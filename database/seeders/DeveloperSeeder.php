<?php

namespace Database\Seeders;

use App\Models\Developer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class DeveloperSeeder extends Seeder
{
    private const IMAGE_DIR = 'seeders/developers';

    public function run(): void
    {
        $developers = [
            [
                'name' => 'إعمار مصر',
                'about' => 'إعمار مصر هي شركة تطوير عقاري رائدة وتابعة لشركة إعمار العقارية الإماراتية. من أبرز مشاريعها مراسي على الساحل الشمالي وأبتاون كايرو وكايرو جيت.',
            ],
            [
                'name' => 'سوديك',
                'about' => 'شركة السادس من أكتوبر للتنمية والاستثمار (سوديك) من أكبر شركات التطوير العقاري في مصر. من أبرز مشاريعها أليجريا وإيستاون وفيليت في القاهرة الجديدة والشيخ زايد.',
            ],
            [
                'name' => 'بالم هيلز للتعمير',
                'about' => 'بالم هيلز للتعمير من أكبر شركات التطوير العقاري في مصر بأكثر من 27 مشروعاً منها بالم هيلز أكتوبر وبادية وبالم هيلز القاهرة الجديدة وهاسيندا على الساحل الشمالي.',
            ],
            [
                'name' => 'ماونتن فيو',
                'about' => 'ماونتن فيو من أفضل شركات التطوير العقاري في مصر وتتميز بتصميمات مجتمعية مبتكرة. من أبرز مشاريعها ماونتن فيو آي سيتي وتشيل أوت بارك وماونتن فيو الساحل الشمالي.',
            ],
            [
                'name' => 'أورا ديفلوبرز',
                'about' => 'أورا ديفلوبرز أسسها رجل الأعمال نجيب ساويرس وتشتهر بمشاريع فاخرة مثل زيد الشيخ زايد وزيد إيست في القاهرة الجديدة وسيلفر ساندز على الساحل الشمالي وبيراميدز هيلز في الجيزة.',
            ],
            [
                'name' => 'تطوير مصر',
                'about' => 'تطوير مصر شركة حائزة على جوائز عديدة ومن أبرز مشاريعها المونت جلالة في العين السخنة وفوكا باي على الساحل الشمالي وبلوم فيلدز في مدينة المستقبل.',
            ],
            [
                'name' => 'حسن علام العقارية',
                'about' => 'حسن علام العقارية تابعة لمجموعة حسن علام القابضة، إحدى أكبر المجموعات في مصر. من أبرز مشاريعها سوان ليك ريزيدنس وهاب تاون وبارك فيو في القاهرة الجديدة.',
            ],
            [
                'name' => 'مدينة نصر للإسكان والتعمير',
                'about' => 'مدينة نصر للإسكان والتعمير شركة عقارية تاريخية قامت بتطوير مدينة نصر. مشروعها الرئيسي الحديث هو تاج سيتي، مشروع متعدد الاستخدامات في القاهرة الجديدة.',
            ],
            [
                'name' => 'هايد بارك للتطوير العقاري',
                'about' => 'هايد بارك للتطوير العقاري تقوم بتطوير أحد أكبر المشاريع السكنية في القاهرة الجديدة. يمتد مشروع هايد بارك على مساحة أكثر من 6 مليون متر مربع بمناطق سكنية وتجارية متكاملة.',
            ],
            [
                'name' => 'سيتي إيدج للتطوير العقاري',
                'about' => 'سيتي إيدج للتطوير العقاري هي شراكة بين هيئة المجتمعات العمرانية الجديدة وبنك الإسكان والتعمير. من أبرز مشاريعها إيتابا ومازارين ونورث إيدج تاورز في العلمين الجديدة.',
            ],
        ];

        $developerImages = [
            'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=400&h=400&fit=crop&q=80',
            'https://images.unsplash.com/photo-1554469384-e58fac16e23a?w=400&h=400&fit=crop&q=80',
            'https://images.unsplash.com/photo-1577495508048-b635879837f1?w=400&h=400&fit=crop&q=80',
            'https://images.unsplash.com/photo-1460317442991-0ec209397118?w=400&h=400&fit=crop&q=80',
            'https://images.unsplash.com/photo-1448630360428-65456659e233?w=400&h=400&fit=crop&q=80',
            'https://images.unsplash.com/photo-1462826303086-329426d1aef5?w=400&h=400&fit=crop&q=80',
            'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=400&h=400&fit=crop&q=80',
            'https://images.unsplash.com/photo-1464938050520-ef2571e0d6e0?w=400&h=400&fit=crop&q=80',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=400&h=400&fit=crop&q=80',
            'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=400&h=400&fit=crop&q=80',
        ];

        $localDir = public_path(self::IMAGE_DIR);

        if (! File::isDirectory($localDir)) {
            File::makeDirectory($localDir, 0755, true);
        }

        foreach ($developers as $index => $developerData) {
            $developer = Developer::firstOrCreate(
                ['name->ar' => $developerData['name']],
                [
                    'name' => ['ar' => $developerData['name']],
                    'about' => ['ar' => $developerData['about']],
                ]
            );

            if ($developer->getFirstMedia(Developer::MEDIA_COLLECTION_LOGO) !== null) {
                continue;
            }

            $imageUrl = $developerImages[$index] ?? $developerImages[0];
            $filename = "developer_{$developer->id}_logo.jpg";
            $localPath = $localDir.'/'.$filename;

            if (! File::exists($localPath)) {
                try {
                    $response = Http::timeout(30)
                        ->withOptions(['allow_redirects' => true])
                        ->get($imageUrl);

                    if ($response->successful()) {
                        File::put($localPath, $response->body());
                    } else {
                        continue;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            $developer
                ->addMedia($localPath)
                ->preservingOriginal()
                ->usingFileName($filename)
                ->toMediaCollection(Developer::MEDIA_COLLECTION_LOGO);
        }
    }
}
