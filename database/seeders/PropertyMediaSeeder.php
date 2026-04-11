<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PropertyMediaSeeder extends Seeder
{
    private const IMAGE_DIR = 'seeders/properties';

    public function run(): void
    {
        $propertyImages = $this->getPropertyImages();

        foreach ($propertyImages as $propertyNameEn => $imageUrls) {
            $property = Property::where('name->en', $propertyNameEn)->first();

            if (! $property) {
                $this->command->warn("Property not found: {$propertyNameEn}");

                continue;
            }

            if ($property->getMedia(Property::MEDIA_COLLECTION_FILE)->count() >= 4) {
                $this->command->info("Skipping (already has media): {$propertyNameEn}");

                continue;
            }

            $property->clearMediaCollection(Property::MEDIA_COLLECTION_FILE);

            $slug = Str::slug($propertyNameEn);
            $localDir = public_path(self::IMAGE_DIR.'/'.$slug);

            if (! File::isDirectory($localDir)) {
                File::makeDirectory($localDir, 0755, true);
            }

            $addedCount = 0;

            foreach ($imageUrls as $index => $imageUrl) {
                $filename = "property_{$slug}_{$index}.jpg";
                $localPath = $localDir.'/'.$filename;

                if (! File::exists($localPath)) {
                    try {
                        $response = Http::timeout(30)
                            ->withOptions(['allow_redirects' => true])
                            ->get($imageUrl);

                        if ($response->successful()) {
                            File::put($localPath, $response->body());
                            $this->command->info("  Downloaded: {$filename}");
                        } else {
                            $this->command->warn("  Failed to download: {$imageUrl}");

                            continue;
                        }
                    } catch (\Exception $e) {
                        $this->command->warn("  Error downloading: {$e->getMessage()}");

                        continue;
                    }
                } else {
                    $this->command->info("  Using cached: {$filename}");
                }

                try {
                    $property
                        ->addMedia($localPath)
                        ->preservingOriginal()
                        ->usingFileName($filename)
                        ->toMediaCollection(Property::MEDIA_COLLECTION_FILE);
                    $addedCount++;
                } catch (\Exception $e) {
                    $this->command->warn("  Error adding media: {$e->getMessage()}");
                }
            }

            $this->command->info("Added {$addedCount} images for: {$propertyNameEn}");
        }
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function getPropertyImages(): array
    {
        return [
            // Apartment 1 - Luxury apartment interiors
            'Luxury Apartment in Nasr City' => [
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1484154218962-a197022b5858?w=800&h=600&fit=crop&q=80',
            ],

            // Apartment 2 - Modern apartment
            'Modern Apartment in New Cairo' => [
                'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1560185893-a55cbc8c57e8?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1560440021-33f9b867899d?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?w=800&h=600&fit=crop&q=80',
            ],

            // Villa - Luxury villa exteriors and interiors
            'Standalone Villa in Madinaty' => [
                'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&h=600&fit=crop&q=80',
            ],

            // Studio - Compact living spaces
            'Cozy Studio in Downtown' => [
                'https://images.unsplash.com/photo-1536376072261-38c75010e6c9?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1585412727339-54e4bae3bbf9?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1560185127-6ed189bf02f4?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1505691938895-1758d7feb511?w=800&h=600&fit=crop&q=80',
            ],

            // Penthouse - Luxury high-rise living
            'Luxury Penthouse in Zamalek' => [
                'https://images.unsplash.com/photo-1600607687644-c7171b42498f?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600573472592-401b489a3cdc?w=800&h=600&fit=crop&q=80',
            ],

            // Office - Professional workspaces
            'Office Space in Smart Village' => [
                'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1497215842964-222b430dc094?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=800&h=600&fit=crop&q=80',
            ],

            // Shop - Commercial retail spaces
            'Commercial Shop in Heliopolis' => [
                'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1582037928769-181f2644ecb7?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1604719312566-8912e9227c6a?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?w=800&h=600&fit=crop&q=80',
            ],

            // Townhouse - Townhouse exteriors and living areas
            'Townhouse in Palm Hills' => [
                'https://images.unsplash.com/photo-1605276374104-dee2a0ed3cd6?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600210492493-0946911123ea?w=800&h=600&fit=crop&q=80',
            ],
        ];
    }
}
