<?php

namespace App\Providers;

use App\Services\Abbreviator\AbbreviatorInterface;
use App\Services\Abbreviator\FirstCharOfWordAbbreviator;
use App\Services\WordCombinator\BitwiseCombinator;
use App\Services\WordCombinator\WordCombinatorInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(WordCombinatorInterface::class,BitwiseCombinator::class);
        $this->app->bind(AbbreviatorInterface::class, FirstCharOfWordAbbreviator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
