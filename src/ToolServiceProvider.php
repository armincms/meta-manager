<?php

namespace Armincms\MetaManager;
  
use Illuminate\Support\ServiceProvider; 
use Laravel\Nova\Nova as LaravelNova;

class ToolServiceProvider extends ServiceProvider 
{      
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \Event::listen(\Core\Document\Events\Rendering::class, function($event) {
            $document = $event->document;

            Nova\HtmlMeta::metaDatas()->each(function($metadata) use ($document) { 
                $name = $metadata['name'] ?? $metadata['property'] ?? $metadata['http-equiv'] ?? $metadata['href'];
                
                $tag = $metadata['group'] === 'link' ? 'link' : 'meta'; 

                $metadata = collect($metadata)->map(function($value) use ($document) { 
                    return str_replace('DESCRIPTION', $document->getDescription(), str_replace(
                        'TITLE', $document->getTitle(), str_replace(
                            'KEYWORDS', $document->getKeywords(), $value
                    )));  
                });

                $document->meta($tag, $name, $metadata->except(['group', 'order'])->filter()->all()); 
            }); 
        }); 

        LaravelNova::serving(function() {
            LaravelNova::resources([
                Nova\HtmlMeta::class,
            ]);
        });
    } 
}
