<?php

namespace Armincms\MetaManager;
 
use Illuminate\Support\HtmlString;

class Meta extends HtmlString 
{   
	/**
	 * Cdn Url of asset.
	 * 
	 * @var String
	 */
	protected $document;

	public function __construct($document)
	{
		$this->document = $document;	
	} 
	 
	public function toHtml() 
	{  
		return (string) view("meatadata::meatadata", [
			'metaDatas' => Nova\HtmlMeta::metaDatas()
		]);
	}
}
