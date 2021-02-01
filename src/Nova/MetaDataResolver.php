<?php

namespace Armincms\MetaManager\Nova;

use Whitecube\NovaFlexibleContent\Value\ResolverInterface;

class MetaDataResolver implements ResolverInterface
{
    /**
     * get the field's value
     *
     * @param  mixed  $resource
     * @param  string $attribute
     * @param  Whitecube\NovaFlexibleContent\Layouts\Collection $layouts
     * @return Illuminate\Support\Collection
     */
    public function get($resource, $attribute, $layouts)
    { 
        return collect(HtmlMeta::metaDatas())->map(function($attributes, $key) use ($layouts) { 
            $layout = $layouts->find($attributes['group'] ?? time());
 
            return optional($layout)->duplicateAndHydrate($attributes['group'].$key, $attributes); 
        })->filter()->values();

    }

    /**
     * Set the field's value
     *
     * @param  mixed  $model
     * @param  string $attribute
     * @param  Illuminate\Support\Collection $groups
     * @return string
     */
    public function set($model, $attribute, $groups)
    {  
        $model->{$attribute} = $groups->map(function($group) {
            return array_merge(['group' => $group->name()], $group->getAttributes());
        })->toArray();
    }
}
