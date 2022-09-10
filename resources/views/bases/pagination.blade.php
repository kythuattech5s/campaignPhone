@php
    $urlDefault = isset($url) ? url($url) : false;
    $params = request()->all();
@endphp
@if ($paginator->hasPages())
	@php
		$lastPage = $paginator->lastPage();
		$currentPage = $paginator->currentPage();
	@endphp
	<div class="{{ $class ?? 'pagination' }}" {{ $attributeAjax ?? 'pagination-filter' }}>
		{{-- Previous Page Link --}}
		@if ($paginator->onFirstPage())
		@else
			<a href="{{ $paginator->previousPageUrl() }}" {{ $attribute ?? 'data-page' }}="{{ $currentPage - 1 }}"><i class="fa-solid fa-arrow-left"></i></a>
			@if (!in_array($currentPage, [1, 2, 3, 4]))
				<a href="{{ $urlDefault ? Support::buildLinkPagination($urlDefault, 1) : $paginator->url(1) }}" {{ $attribute ?? 'data-page' }}="1">1</a></li>
				@if ($currentPage !== 5)
					<span style="pointer-events: none"> ... </span>
				@endif
			@endif
		@endif
		{{-- Pagination Elements --}}
		@foreach ($elements as $key => $element)
			@if (is_array($element))
				@foreach ($element as $page => $url)
					@if ($page == $currentPage)
						<strong>{{ $page }}</strong>
					@elseif($page == $currentPage + 1 || $page == $currentPage + 2 || $page == $currentPage + 3 || $page == $currentPage - 1 || $page == $currentPage - 2 || $page == $currentPage - 3)
						<a href="{{  $urlDefault ? Support::buildLinkPagination($urlDefault,$page) : $url }}" {{ $attribute ?? 'data-page' }}="{{ $page }}">{{ $page }}</a></li>
					@endif
				@endforeach
			@endif
		@endforeach
		{{-- Next Page Link --}}
		@if ($paginator->hasMorePages())
			@if (!in_array($currentPage, [$lastPage, $lastPage - 1, $lastPage - 2, $lastPage - 3]))
				@if ($currentPage !== $lastPage - 4)
					<span style="pointer-events: none"> ... </span>
				@endif
				<a href="{{ $urlDefault ? Support::buildLinkPagination($urlDefault,$page) : $paginator->url($page) }}" {{ $attribute ?? 'data-page' }}="{{ $page }}">{{ $page }}</a></li>
			@endif
			<a href="{{ $urlDefault ? Support::buildLinkPagination($urlDefault,$currentPage + 1) : $paginator->nextPageUrl() }}" {{ $attribute ?? 'data-page' }}="{{ $currentPage + 1 }}"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
		@else
		@endif
	</div>
@endif
