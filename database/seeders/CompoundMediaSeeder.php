<?php

namespace Database\Seeders;

use App\Models\Compound;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class CompoundMediaSeeder extends Seeder
{
    private const IMAGE_DIR = 'seeders/compounds';

    public function run(): void
    {
        $compoundImages = [
            'مراسي' => 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=800&h=500&fit=crop&q=80',
            'أبتاون كايرو' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=500&fit=crop&q=80',
            'أليجريا' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&h=500&fit=crop&q=80',
            'فيليت' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=500&fit=crop&q=80',
            'بالم هيلز أكتوبر' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&h=500&fit=crop&q=80',
            'بادية' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&h=500&fit=crop&q=80',
            'ماونتن فيو آي سيتي' => 'https://images.unsplash.com/photo-1460317442991-0ec209397118?w=800&h=500&fit=crop&q=80',
            'زيد الشيخ زايد' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&h=500&fit=crop&q=80',
            'المونت جلالة' => 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?w=800&h=500&fit=crop&q=80',
            'سوان ليك ريزيدنس' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=500&fit=crop&q=80',
            'تاج سيتي' => 'https://images.unsplash.com/photo-1448630360428-65456659e233?w=800&h=500&fit=crop&q=80',
            'هايد بارك' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=800&h=500&fit=crop&q=80',
            'نورث إيدج تاورز' => 'https://images.unsplash.com/photo-1462826303086-329426d1aef5?w=800&h=500&fit=crop&q=80',
        ];

        $localDir = public_path(self::IMAGE_DIR);

        if (! File::isDirectory($localDir)) {
            File::makeDirectory($localDir, 0755, true);
        }

        foreach ($compoundImages as $name => $imageUrl) {
            $compound = Compound::where('name', $name)->first();

            if (! $compound) {
                continue;
            }

            if ($compound->getFirstMedia(Compound::MEDIA_COLLECTION_IMAGE) !== null) {
                $this->command->info("Skipping (already has media): {$name}");

                continue;
            }

            $filename = "compound_{$compound->id}.jpg";
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
                        $this->command->warn("  Failed to download image for: {$name}");

                        continue;
                    }
                } catch (\Exception $e) {
                    $this->command->warn("  Error downloading: {$e->getMessage()}");

                    continue;
                }
            } else {
                $this->command->info("  Using cached: {$filename}");
            }

            $compound
                ->addMedia($localPath)
                ->preservingOriginal()
                ->usingFileName($filename)
                ->toMediaCollection(Compound::MEDIA_COLLECTION_IMAGE);
        }
    }
}
