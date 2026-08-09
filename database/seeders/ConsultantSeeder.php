<?php

namespace Database\Seeders;

use App\Models\Consultant;
use Illuminate\Database\Seeder;
use Throwable;

class ConsultantSeeder extends Seeder
{
    public function run(): void
    {
        $consultants = [
            [
                'name' => 'أحمد إبراهيم عبدالله',
                'job_title' => 'مدير المبيعات',
                'sales_percentage' => 92.50,
                'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                'cover_image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200',
                'is_featured' => true,
                'phones' => ['+201012345678', '+201112345678'],
            ],
            [
                'name' => 'أميرة إبراهيم عبدالله',
                'job_title' => 'مديرة المبيعات',
                'sales_percentage' => 88.00,
                'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'cover_image' => 'https://images.unsplash.com/photo-1582407947092-06087b228689?w=1200',
                'is_featured' => true,
                'phones' => ['+201023456789', '+201123456789'],
            ],
            [
                'name' => 'محمد عبدالرحمن السيد',
                'job_title' => 'استشاري عقاري أول',
                'sales_percentage' => 85.30,
                'image' => 'https://randomuser.me/api/portraits/men/75.jpg',
                'cover_image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1200',
                'is_featured' => true,
                'phones' => ['+201034567890', '+201134567890'],
            ],
            [
                'name' => 'سارة أحمد محمود',
                'job_title' => 'مستشارة عقارية',
                'sales_percentage' => 78.60,
                'image' => 'https://randomuser.me/api/portraits/women/68.jpg',
                'cover_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200',
                'is_featured' => false,
                'phones' => ['+201045678901', '+201145678901'],
            ],
            [
                'name' => 'خالد حسن عبدالعزيز',
                'job_title' => 'مدير فريق المبيعات',
                'sales_percentage' => 91.20,
                'image' => 'https://randomuser.me/api/portraits/men/22.jpg',
                'cover_image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200',
                'is_featured' => true,
                'phones' => ['+201056789012', '+201156789012'],
            ],
            [
                'name' => 'نورهان علي حسين',
                'job_title' => 'استشارية مبيعات',
                'sales_percentage' => 72.40,
                'image' => 'https://randomuser.me/api/portraits/women/25.jpg',
                'cover_image' => 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=1200',
                'is_featured' => false,
                'phones' => ['+201067890123'],
            ],
            [
                'name' => 'عمر محمد فاروق',
                'job_title' => 'استشاري عقاري',
                'sales_percentage' => 80.10,
                'image' => 'https://randomuser.me/api/portraits/men/45.jpg',
                'cover_image' => 'https://images.unsplash.com/photo-1600573472550-8090b5e0745e?w=1200',
                'is_featured' => false,
                'phones' => ['+201078901234', '+201178901234'],
            ],
            [
                'name' => 'ياسمين كريم مصطفى',
                'job_title' => 'مديرة حسابات العملاء',
                'sales_percentage' => 83.70,
                'image' => 'https://randomuser.me/api/portraits/women/33.jpg',
                'cover_image' => 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=1200',
                'is_featured' => true,
                'phones' => ['+201089012345'],
            ],
            [
                'name' => 'حسام الدين عادل',
                'job_title' => 'مستشار استثمار عقاري',
                'sales_percentage' => 76.90,
                'image' => 'https://randomuser.me/api/portraits/men/55.jpg',
                'cover_image' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=1200',
                'is_featured' => false,
                'phones' => ['+201090123456', '+201190123456'],
            ],
            [
                'name' => 'منى عبدالله الشافعي',
                'job_title' => 'استشارية مبيعات أولى',
                'sales_percentage' => 87.50,
                'image' => 'https://randomuser.me/api/portraits/women/52.jpg',
                'cover_image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1200',
                'is_featured' => true,
                'phones' => ['+201001234567'],
            ],
        ];

        foreach ($consultants as $data) {
            $phones = $data['phones'];
            $image = $data['image'] ?? null;
            $coverImage = $data['cover_image'] ?? null;
            unset($data['phones'], $data['image'], $data['cover_image']);

            $consultant = Consultant::create($data);

            foreach ($phones as $phone) {
                $consultant->phones()->create(['phone' => $phone]);
            }

            try {
                if ($image !== null) {
                    $consultant->addMediaFromUrl($image)->toMediaCollection(Consultant::MEDIA_COLLECTION_IMAGE);
                }

                if ($coverImage !== null) {
                    $consultant->addMediaFromUrl($coverImage)->toMediaCollection(Consultant::MEDIA_COLLECTION_COVER);
                }
            } catch (Throwable) {
                // Remote seed images are best-effort; skip when unreachable.
            }
        }
    }
}
