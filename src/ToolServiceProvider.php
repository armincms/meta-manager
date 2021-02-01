<?php

namespace Armincms\MetaManager;
 
use Illuminate\Support\ServiceProvider; 
use Laravel\Nova\Nova as LaravelNova;

class ToolServiceProvider extends ServiceProvider 
{     
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'meatadata');
    } 

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \Event::listen(\Core\Document\Events\Rendering::class, function($event) {
            $event->document->pushMeta(new Meta($event->document));
        }); 

        LaravelNova::serving(function() {
            LaravelNova::resources([
                Nova\HtmlMeta::class,
            ]);
        });
    } 
}
