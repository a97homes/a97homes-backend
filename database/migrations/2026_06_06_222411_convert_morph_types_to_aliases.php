<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var array<string, class-string> alias => FQCN
     */
    private array $morphMap = [
        'article' => \App\Models\Article::class,
        'banner' => \App\Models\Banner::class,
        'city' => \App\Models\City::class,
        'compound' => \App\Models\Compound::class,
        'developer' => \App\Models\Developer::class,
        'property' => \App\Models\Property::class,
        'user' => \App\Models\User\User::class,
    ];

    /**
     * @var array<string, string> table => morph type column
     */
    private array $morphColumns = [
        'media' => 'model_type',
        'faqs' => 'faqable_type',
        'personal_access_tokens' => 'tokenable_type',
        'notifications' => 'notifiable_type',
        'model_has_roles' => 'model_type',
        'model_has_permissions' => 'model_type',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->morphColumns as $table => $column) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($this->morphMap as $alias => $class) {
                DB::table($table)
                    ->where($column, $class)
                    ->update([$column => $alias]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->morphColumns as $table => $column) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($this->morphMap as $alias => $class) {
                DB::table($table)
                    ->where($column, $alias)
                    ->update([$column => $class]);
            }
        }
    }
};
