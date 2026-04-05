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
                'icon' => 'fa-brands fa-facebook-f',
            ],
            [
                'type' => 'instagram',
                'link' => 'https://www.instagram.com/a97infinity',
                'icon' => 'fa-brands fa-instagram',
            ],
            [
                'type' => 'twitter',
                'link' => 'https://www.x.com/A97Infinity',
                'icon' => 'fa-brands fa-x-twitter',
            ],
            [
                'type' => 'linkedin',
                'link' => 'https://www.linkedin.com/company/a97infinity',
                'icon' => 'fa-brands fa-linkedin-in',
            ],
            [
                'type' => 'youtube',
                'link' => 'https://www.youtube.com/@A97Infinity',
                'icon' => 'fa-brands fa-youtube',
            ],
            [
                'type' => 'tiktok',
                'link' => 'https://www.tiktok.com/@a97infinity',
                'icon' => 'fa-brands fa-tiktok',
            ],
            [
                'type' => 'whatsapp',
                'link' => 'https://wa.me/201012345678',
                'icon' => 'fa-brands fa-whatsapp',
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
