@foreach($metaDatas as $meta)
	@switch($meta['group'])
		@case('meta') 
			<meta 
				name="{{ $meta['name'] }}" 
				content="{{ $meta['content'] }}"
			> 
			@break
		@case('og')
			<meta 
				property="{{ $meta['property'] }}" 
				content="{{ $meta['content'] }}"
			> 
			@break
		@case('equiv')
			<meta 
				http-equiv="{{ $meta['equiv'] }}" 
				content="{{ $meta['content'] }}"
			> 
			@break 
		@case('link')
			<link   
				rel="{{ $meta['rel'] }}" 
				@isset($meta['type']) type="{{ $meta['type'] }}" @endif 
				@isset($meta['sizes']) sizes="{{ $meta['sizes'] }}" @endif 
				@isset($meta['hreflang']) hreflang="{{ $meta['hreflang'] }}" @endif 
				href="{{ $meta['href'] }}"
			>
			@break 
	@endswitch
@endforeach