<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titleEn = $this->faker->unique()->sentence(3);

        return [
            'slug' => Str::slug($titleEn).'-'.$this->faker->unique()->numberBetween(1000, 999999),
            'title' => [
                'en' => $titleEn,
                'ar' => 'صفحة '.$this->faker->unique()->word(),
            ],
            'body' => [
                'en' => $this->faker->paragraphs(3, true),
                'ar' => 'محتوى الصفحة الكامل باللغة العربية.',
            ],
            'is_published' => true,
            'published_at' => now(),
            'sort_order' => 0,
        ];
    }

    public function draft(): self
    {
        return $this->state(fn () => ['is_published' => false]);
    }
}
