<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class BannerSeeder extends Seeder
{
    private const IMAGE_DIR = 'seeders/banners';

    public function run(): void
    {
        $banners = [
            [
                'title' => [
                    'en' => 'Grand Coast Resort',
                    'ar' => 'جراند كوست ريزورت',
                ],
                'subtitle' => [
                    'en' => 'Beach. Chill. Deals. Up to 50% off',
                    'ar' => 'شاطئ. استرخاء. عروض. خصم حتى 50%',
                ],
                'link' => null,
                'is_active' => true,
                'sort_order' => 1,
                'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1440&h=430&fit=crop&q=80',
            ],
            [
                'title' => [
                    'en' => 'North Coast Summer Deals',
                    'ar' => 'عروض صيف الساحل الشمالي',
                ],
                'subtitle' => [
                    'en' => 'Exclusive offers on beachfront properties - limited time only',
                    'ar' => 'عروض حصرية على العقارات المطلة على البحر - لفترة محدودة',
                ],
                'link' => null,
                'is_active' => true,
                'sort_order' => 2,
                'image' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1440&h=430&fit=crop&q=80',
            ],
            [
                'title' => [
                    'en' => 'New Capital Launch Event',
                    'ar' => 'حدث إطلاق العاصمة الإدارية',
                ],
                'subtitle' => [
                    'en' => 'Be the first to own in Egypt\'s new capital - Starting from 2M EGP',
                    'ar' => 'كن أول من يمتلك في العاصمة الإدارية الجديدة - يبدأ من 2 مليون جنيه',
                ],
                'link' => null,
                'is_active' => true,
                'sort_order' => 3,
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1440&h=430&fit=crop&q=80',
            ],
        ];

        $localDir = public_path(self::IMAGE_DIR);

        if (! File::isDirectory($localDir)) {
            File::makeDirectory($localDir, 0755, true);
        }

        foreach ($banners as $index => $bannerData) {
            $imageUrl = $bannerData['image'];
            unset($bannerData['image']);

            $banner = Banner::updateOrCreate(
                ['title->en' => $bannerData['title']['en']],
                $bannerData,
            );

            if ($banner->getFirstMedia(Banner::MEDIA_COLLECTION_IMAGE) !== null) {
                continue;
            }

            $filename = 'banner_'.($index + 1).'.jpg';
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

            $banner
                ->addMedia($localPath)
                ->preservingOriginal()
                ->usingFileName($filename)
                ->toMediaCollection(Banner::MEDIA_COLLECTION_IMAGE);
        }
    }
}
