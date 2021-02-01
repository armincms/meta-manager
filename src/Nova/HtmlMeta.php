<?php

namespace Armincms\MetaManager\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\{Text, Number};
use Whitecube\NovaFlexibleContent\Flexible;
use Armincms\Nova\ConfigResource;

 
class HtmlMeta extends ConfigResource
{  
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request): array
    {
    	return [ 
    		Flexible::make(__('Meta Field'), '_seo_meta_data_')
    			->resolver(MetaDataResolver::class)
    			->addLayout(__('Normal Meta'), 'meta', [
    				Text::make(__('Name'), 'name')
    					->required()
    					->rules('required'),

    				Text::make(__('Content'), 'content')
    					->required()
    					->rules('required'),

		    		Number::make(__('Display Order'), 'order')
		    			->required()
		    			->rules('required'),
    			])
    			->addLayout(__('Open Graph Meta'), 'og', [
    				Text::make(__('Property'), 'property')
    					->required()
    					->rules('required'),

    				Text::make(__('Content'), 'content')
    					->required()
    					->rules('required'),

		    		Number::make(__('Display Order'), 'order')
		    			->required()
		    			->rules('required'),
    			])
    			->addLayout(__('HTTP Equiv'), 'equiv', [
    				Text::make(__('Http-equiv'), 'equiv')
    					->required()
    					->rules('required'),

    				Text::make(__('Content'), 'content')
    					->required()
    					->rules('required'),

		    		Number::make(__('Display Order'), 'order')
		    			->required()
		    			->rules('required'),
    			])
    			->addLayout(__('Link'), 'link', [ 
    				Text::make(__('Rel'), 'rel')
    					->required()
    					->rules('required'),

    				Text::make(__('Href'), 'href')
    					->required()
    					->rules('required'),

		    		Number::make(__('Display Order'), 'order')
		    			->required()
		    			->rules('required'),

    				Text::make(__('Type'), 'type'), 

    				Text::make(__('Sizes'), 'sizes')
    					->rules([function($attribute, $value, $fail) {
    						if(! (is_null($value) || preg_match('/^[0-9]+[xX][0-9]+$/', $value))) {
    							$fail(__('Sizes should be format of [0-9]x[0-9]'));
    						}
    					}]), 

    				Text::make(__('Media'), 'media'),

    				Text::make(__('Hreflang'), 'hreflang'),
    			])
    			->button(__('New Meta')),
    	];
    }

    /**
     * Get the available meta datas.
     * 
     * @return \\Illuminate\Support\Collection
     */
    public static function metaDatas()
    {
    	return collect(static::option('_seo_meta_data_'))->sortBy('order');
    }
}