<?php

namespace App\Providers;

use App\Content\SiteContentRepository;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SiteContentRepository::class);
    }

    public function boot(): void
    {
        View::composer('*', function ($view): void {
            $repository = app(SiteContentRepository::class);
            $content = $repository->all();

            $view->with([
                'settings' => $content['settings'] ?? [],
                'navigation' => $content['navigation'] ?? [],
            ]);
        });
    }
}
