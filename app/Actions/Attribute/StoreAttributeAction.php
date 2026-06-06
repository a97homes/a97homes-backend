<?php

namespace App\Actions\Attribute;

use App\Models\Attribute;
use Illuminate\Support\Str;

class StoreAttributeAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Attribute
    {
        $data['slug'] = $this->generateUniqueSlug($data['name']['en']);

        return Attribute::create($data);
    }

    private function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 2;

        while (Attribute::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
