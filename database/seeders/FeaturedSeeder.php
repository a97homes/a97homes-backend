<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Compound;
use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class FeaturedSeeder extends Seeder
{
    private const IMAGE_DIR = 'seeders/cities';

    public function run(): void
    {
        $this->markFeaturedCompounds();
        $this->markFeaturedProperties();
        $this->addCityImages();
    }

    private function markFeaturedCompounds(): void
    {
        $featuredCompounds = [
            'هايد بارك',
            'ماونتن فيو آي سيتي',
            'بادية',
            'زيد الشيخ زايد',
            'مراسي',
            'أبتاون كايرو',
            'تاج سيتي',
            'نورث إيدج تاورز',
        ];

        Compound::whereIn('name', $featuredCompounds)
            ->update(['is_featured' => true]);
    }

    private function markFeaturedProperties(): void
    {
        // Ensure compound properties are active, then mark top 6 as featured
        Property::whereHas('compound')
            ->where('status', '!=', 'active')
            ->update(['status' => 'active']);

        Property::where('status', 'active')
            ->whereNotNull('price')
            ->orderByDesc('price')
            ->limit(6)
            ->update(['is_featured' => true]);
    }

    private function addCityImages(): void
    {
        $cityImages = [
            'New Cairo' => 'https://images.unsplash.com/photo-1549918864-48ac978761a4?w=623&h=343&fit=crop&q=80',
            '6th of October' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=299&h=343&fit=crop&q=80',
            'Sheikh Zayed' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=299&h=343&fit=crop&q=80',
            'El Alamein' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=623&h=343&fit=crop&q=80',
            'Hurghada' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=299&h=343&fit=crop&q=80',
            'Maadi' => 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=299&h=343&fit=crop&q=80',
            'Nasr City' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=299&h=343&fit=crop&q=80',
            'El Shorouk' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=299&h=343&fit=crop&q=80',
        ];

        $localDir = public_path(self::IMAGE_DIR);

        if (! File::isDirectory($localDir)) {
            File::makeDirectory($localDir, 0755, true);
        }

        foreach ($cityImages as $cityNameEn => $imageUrl) {
            $city = City::where('name->en', $cityNameEn)->first();

            if (! $city) {
                continue;
            }

            if ($city->getFirstMedia(City::MEDIA_COLLECTION_IMAGE) !== null) {
                continue;
            }

            $slug = str_replace(' ', '_', strtolower($cityNameEn));
            $filename = "city_{$slug}.jpg";
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

            $city
                ->addMedia($localPath)
                ->preservingOriginal()
                ->usingFileName($filename)
                ->toMediaCollection(City::MEDIA_COLLECTION_IMAGE);
        }
    }
}
