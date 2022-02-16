<?php

namespace Kraenkvisuell\NovaCmsNews;

use Illuminate\Support\ServiceProvider;
use Kraenkvisuell\NovaCmsNews\Nova\NewsItem;
use Laravel\Nova\Nova;

class NovaCmsNewsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang/nova-cms-news', 'nova-cms-news');

        $this->publishes([
            __DIR__.'/../resources/lang/nova-cms-news' => resource_path('lang/vendor/nova-cms-news'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/nova-cms-news.php' => config_path('nova-cms-news.php'),
        ]);

        Nova::resources([
            NewsItem::class,
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/nova-cms-news.php',
            'nova-cms-news'
        );
    }
}
