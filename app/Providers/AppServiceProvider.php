<?php

namespace App\Providers;

use App\Services\Chatbot\ChatbotResponder;
use App\Services\Chatbot\RuleBasedChatbotResponder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->bind(ChatbotResponder::class, RuleBasedChatbotResponder::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'area' => \App\Models\Area::class,
            'article' => \App\Models\Article::class,
            'banner' => \App\Models\Banner::class,
            'sub_area' => \App\Models\SubArea::class,
            'compound' => \App\Models\Compound::class,
            'consultant' => \App\Models\Consultant::class,
            'country' => \App\Models\Country::class,
            'developer' => \App\Models\Developer::class,
            'property' => \App\Models\Property::class,
            'social' => \App\Models\Social::class,
            'user' => \App\Models\User\User::class,
        ]);

        Builder::macro('macroPaginate', function () {
            /** @var Builder $this */
            $perPage = Request::query('per_page', 10);

            return $this->paginate($perPage);
        });
    }
}
