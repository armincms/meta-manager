<?php

namespace Armincms\MetaManager\Nova;

use Illuminate\Support\Str;
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
    					->rules('required')
                        ->help(__('Use `TITLE`, `DESCRIPTION`, `KEYWORDS` to include current page meta.')),

                    $this->orderField($request),
    			])
    			->addLayout(__('Open Graph Meta'), 'og', [
    				Text::make(__('Property'), 'property')
    					->required()
    					->rules('required')
                        ->fillUsing(function($request, $model, $attribute) {
                            $property = $request->get($attribute);
                            
                            $model->property = Str::startsWith($property, 'og:') ? $property : "og:{$property}"; 
                        }),

    				Text::make(__('Content'), 'content')
    					->required()
    					->rules('required')
                        ->help(__('Use `TITLE`, `DESCRIPTION`, `KEYWORDS` to include current page meta.')),

                    $this->orderField($request),
    			])
    			->addLayout(__('HTTP Equiv'), 'http-equiv', [
    				Text::make(__('Http-equiv'), 'http-equiv')
    					->required()
    					->rules('required'),

    				Text::make(__('Content'), 'content')
    					->required()
    					->rules('required'),

                    $this->orderField($request),
    			])
    			->addLayout(__('Link'), 'link', [ 
    				Text::make(__('Rel'), 'rel')
    					->required()
    					->rules('required'),

    				Text::make(__('Type'), 'type'), 

    				Text::make(__('Media'), 'media'),

                    Text::make(__('Sizes'), 'sizes')
                        ->rules([function($attribute, $value, $fail) {
                            if(! (is_null($value) || preg_match('/^[0-9]+[xX][0-9]+$/', $value))) {
                                $fail(__('Sizes should be format of [0-9]x[0-9]'));
                            }
                        }]), 

    				Text::make(__('Hreflang'), 'hreflang'),

                    Text::make(__('Href'), 'href')
                        ->required()
                        ->rules('required'),

                    $this->orderField($request),
    			])
    			->button(__('New Meta'))
                ->collapsed(),
    	];
    }

    public function orderField($request)
    {
        if(! isset($this->order)) {
            $this->order = 1;
        }

        $fillUsingCallback = function($flexibleRequest, $model, $attribute) use ($request) {
            $model->{$attribute} = is_numeric($flexibleRequest->get($attribute)) 
                            ? $flexibleRequest->get($attribute)
                            : intval(collect($request->get('_seo_meta_data_'))->max('attributes.order')) + $this->order++; 
        };

        return Number::make(__('Display Order'), 'order')->fillUsing($fillUsingCallback);
    }

    /**
     * Get the available meta datas.
     * 
     * @return \\Illuminate\Support\Collection
     */
    public static function metaDatas()
    {
    	return with(collect(static::option('_seo_meta_data_'))->sortBy('order'), function($metaDatas) { 
            return $metaDatas->isEmpty() ? static::insertDefaults() : $metaDatas;
        });
    }

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return mixed
     */
    public static function newModel()
    {
        return tap(parent::newModel(), function() {
            if(static::metaDatas()->isEmpty()) {
                static::insertDefaults();
            } 
        });
    }

    public static function insertDefaults()
    {
        static::store()->put('_seo_meta_data_', static::defaults());

        return static::metaDatas(); 
    }

    public static function defaults()
    { 
        return [
            [
                "group" => "meta",
                "name"  => "title",
                "order" => $order = 0,
                "content"   => "TITLE",
            ],
            [
                "group" => "meta",
                "name"  => "description",
                "order" => $order++,
                "content"   => "DESCRIPTION",
            ],
            [
                "group" => "meta",
                "name"  => "keywords",
                "order" => $order++,
                "content"   => "KEYWORDS",
            ]
        ];
    }
}