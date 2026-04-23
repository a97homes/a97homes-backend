<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ArticleTypeEnum;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titleEn = $this->faker->unique()->sentence(6);

        return [
            'slug' => Str::slug($titleEn).'-'.$this->faker->unique()->numberBetween(1000, 999999),
            'type' => ArticleTypeEnum::Blog,
            'title' => [
                'en' => $titleEn,
                'ar' => 'مقال '.$this->faker->unique()->word(),
            ],
            'excerpt' => [
                'en' => $this->faker->paragraph(1),
                'ar' => 'ملخص للمقال.',
            ],
            'body' => [
                'en' => $this->faker->paragraphs(4, true),
                'ar' => 'محتوى المقال الكامل باللغة العربية.',
            ],
            'author' => $this->faker->name(),
            'published_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'views_count' => $this->faker->numberBetween(0, 5000),
            'is_featured' => false,
        ];
    }

    public function draft(): self
    {
        return $this->state(fn () => ['published_at' => null]);
    }

    public function featured(): self
    {
        return $this->state(fn () => ['is_featured' => true]);
    }

    public function media(): self
    {
        return $this->state(fn () => ['type' => ArticleTypeEnum::Media]);
    }

    public function news(): self
    {
        return $this->state(fn () => ['type' => ArticleTypeEnum::News]);
    }
}
