<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Inertia\Inertia::share('social', function () {
            return [
                'instagram' => \App\Models\StoreSetting::where('key', 'instagram_url')->value('value'),
                'tiktok' => \App\Models\StoreSetting::where('key', 'tiktok_url')->value('value'),
            ];
        });
    }
}
