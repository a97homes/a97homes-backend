<?php

namespace Database\Seeders;

use App\Models\Compound;
use Illuminate\Database\Seeder;

class CompoundMediaSeeder extends Seeder
{
    public function run(): void
    {
        $compoundImages = [
            'مراسي' => 'https://placehold.co/800x500/0a3d62/ffffff/png?text=Marassi',
            'أبتاون كايرو' => 'https://placehold.co/800x500/1a1a2e/ffffff/png?text=Uptown+Cairo',
            'أليجريا' => 'https://placehold.co/800x500/27ae60/ffffff/png?text=Allegria',
            'فيليت' => 'https://placehold.co/800x500/8e44ad/ffffff/png?text=Villette',
            'بالم هيلز أكتوبر' => 'https://placehold.co/800x500/16a085/ffffff/png?text=Palm+Hills+October',
            'بادية' => 'https://placehold.co/800x500/2c3e50/ffffff/png?text=Badya',
            'ماونتن فيو آي سيتي' => 'https://placehold.co/800x500/e74c3c/ffffff/png?text=Mountain+View+iCity',
            'زيد الشيخ زايد' => 'https://placehold.co/800x500/f39c12/ffffff/png?text=ZED+Sheikh+Zayed',
            'المونت جلالة' => 'https://placehold.co/800x500/3498db/ffffff/png?text=Il+Monte+Galala',
            'سوان ليك ريزيدنس' => 'https://placehold.co/800x500/d35400/ffffff/png?text=Swan+Lake',
            'تاج سيتي' => 'https://placehold.co/800x500/c0392b/ffffff/png?text=Taj+City',
            'هايد بارك' => 'https://placehold.co/800x500/2980b9/ffffff/png?text=Hyde+Park',
            'نورث إيدج تاورز' => 'https://placehold.co/800x500/1abc9c/ffffff/png?text=North+Edge+Towers',
        ];

        foreach ($compoundImages as $name => $imageUrl) {
            $compound = Compound::where('name', $name)->first();

            if (! $compound) {
                continue;
            }

            if ($compound->getFirstMedia(Compound::MEDIA_COLLECTION_IMAGE) === null) {
                $compound
                    ->addMediaFromUrl($imageUrl)
                    ->usingFileName('compound_'.str_replace(' ', '_', $compound->id).'.png')
                    ->toMediaCollection(Compound::MEDIA_COLLECTION_IMAGE);
            }
        }
    }
}
