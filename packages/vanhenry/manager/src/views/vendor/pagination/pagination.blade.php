@if ($paginator->hasPages())
    <div class="pagination wow fadeInUp">
        {{-- Previous Page Link --}}
        @if (!$paginator->onFirstPage())
            <a href="{{ $paginator->previousPageUrl() }}"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
            @if (!in_array($paginator->currentPage(), [1, 2]))
                <a href="{{ $paginator->url(1) }}" >1</a></li>
                @if ($paginator->currentPage() !== 3)
                    <a style="pointer-events: none"> ... </a>
                @endif
            @endif
        @endif
        {{-- Pagination Elements --}}
        @foreach ($elements as $key => $element)
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <strong>{{ $page }}</strong>
                    @elseif($page == $paginator->currentPage() + 1 || $page == $paginator->currentPage() - 1)
                        <a href="{{ $url }}" >{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            @if(!in_array($paginator->currentPage(),[$paginator->lastPage(),$paginator->lastPage()-1]))
                @if($paginator->currentPage() !== $paginator->lastPage()-2)
                <a style="pointer-events: none"> ... </a>
                @endif
                <a href="{{ $paginator->url($paginator->lastPage()) }}" >{{ $page }}</a></li>
            @endif
            <a href="{{ $paginator->nextPageUrl() }}"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
        @endif
    </div>
@endif