<?php

namespace Database\Seeders;

use App\Models\Social;
use Illuminate\Database\Seeder;

class SocialSeeder extends Seeder
{
    public function run(): void
    {
        $socials = [
            [
                'type' => 'facebook',
                'link' => 'https://www.facebook.com/A97Infinity',
            ],
            [
                'type' => 'instagram',
                'link' => 'https://www.instagram.com/a97infinity',
            ],
            [
                'type' => 'twitter',
                'link' => 'https://www.x.com/A97Infinity',
            ],
            [
                'type' => 'linkedin',
                'link' => 'https://www.linkedin.com/company/a97infinity',
            ],
            [
                'type' => 'youtube',
                'link' => 'https://www.youtube.com/@A97Infinity',
            ],
            [
                'type' => 'tiktok',
                'link' => 'https://www.tiktok.com/@a97infinity',
            ],
            [
                'type' => 'whatsapp',
                'link' => 'https://wa.me/201012345678',
            ],
        ];

        foreach ($socials as $social) {
            Social::firstOrCreate(
                ['type' => $social['type']],
                $social
            );
        }
    }
}
