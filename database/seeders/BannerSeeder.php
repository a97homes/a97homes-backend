<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
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
                'image' => 'https://placehold.co/1440x430/1a1a2e/ffffff/png?text=Grand+Coast+Resort',
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
                'image' => 'https://placehold.co/1440x430/0a3d62/ffffff/png?text=North+Coast+Summer',
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
                'image' => 'https://placehold.co/1440x430/2c3e50/ffffff/png?text=New+Capital+Launch',
            ],
        ];

        foreach ($banners as $bannerData) {
            $imageUrl = $bannerData['image'];
            unset($bannerData['image']);

            $banner = Banner::updateOrCreate(
                ['title->en' => $bannerData['title']['en']],
                $bannerData,
            );

            if ($banner->getFirstMedia(Banner::MEDIA_COLLECTION_IMAGE) === null) {
                $banner
                    ->addMediaFromUrl($imageUrl)
                    ->usingFileName("banner_{$banner->id}.png")
                    ->toMediaCollection(Banner::MEDIA_COLLECTION_IMAGE);
            }
        }
    }
}
