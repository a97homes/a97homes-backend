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
                'link' => 'https://www.facebook.com/share/198y2oUpbw/?mibextid=wwXIfr',
            ],
            [
                'type' => 'instagram',
                'link' => 'https://www.instagram.com/a97homes?igsi=ZzFyYXdrbHRxMHdl',
            ],
            [
                'type' => 'youtube',
                'link' => 'https://www.youtube.com/@a97homes',
            ],
            [
                'type' => 'tiktok',
                'link' => 'https://www.tiktok.com/@a97homes?_r=1&_t=ZS-99DMU9fM3a3',
            ],
            [
                'type' => 'whatsapp',
                'link' => 'https://wa.me/+201097809259',
            ],
            [
                'type' => 'mail',
                'link' => 'a97homes@gmail.com',
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
