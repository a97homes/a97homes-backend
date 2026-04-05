<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Compound;
use App\Models\Property;
use Illuminate\Database\Seeder;

class FeaturedSeeder extends Seeder
{
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
            'New Cairo' => 'https://placehold.co/623x343/2c3e50/ffffff/png?text=New+Cairo',
            '6th of October' => 'https://placehold.co/299x343/1a1a2e/ffffff/png?text=6th+of+October',
            'Sheikh Zayed' => 'https://placehold.co/299x343/0a3d62/ffffff/png?text=Sheikh+Zayed',
            'El Alamein' => 'https://placehold.co/623x343/16a085/ffffff/png?text=El+Alamein',
            'Hurghada' => 'https://placehold.co/299x343/e74c3c/ffffff/png?text=Hurghada',
            'Maadi' => 'https://placehold.co/299x343/8e44ad/ffffff/png?text=Maadi',
            'Nasr City' => 'https://placehold.co/299x343/d35400/ffffff/png?text=Nasr+City',
            'El Shorouk' => 'https://placehold.co/299x343/27ae60/ffffff/png?text=El+Shorouk',
        ];

        foreach ($cityImages as $cityNameEn => $imageUrl) {
            $city = City::where('name->en', $cityNameEn)->first();

            if (! $city) {
                continue;
            }

            if ($city->getFirstMedia(City::MEDIA_COLLECTION_IMAGE) === null) {
                $city
                    ->addMediaFromUrl($imageUrl)
                    ->usingFileName('city_'.str_replace(' ', '_', strtolower($cityNameEn)).'.png')
                    ->toMediaCollection(City::MEDIA_COLLECTION_IMAGE);
            }
        }
    }
}
