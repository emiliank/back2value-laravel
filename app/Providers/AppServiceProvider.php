<?php

namespace App\Providers;

use App\Content\SiteContentRepository;
use App\Support\Locale;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SiteContentRepository::class);
        $this->app->singleton(Locale::class);
    }

    public function boot(): void
    {
        // Console, queue and test code generate URLs outside a request, so
        // start from the configured default. SetLocale replaces this with the
        // visitor's own language once a request is being handled.
        URL::defaults(['locale' => (string) config('locales.default', 'sq')]);

        View::composer('*', function ($view): void {
            $content = app(SiteContentRepository::class)->all();

            $view->with([
                'settings' => $content['settings'] ?? [],
                'navigation' => $content['navigation'] ?? [],
            ]);
        });
    }
}
