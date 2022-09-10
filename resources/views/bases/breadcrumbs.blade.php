@if (count($breadcrumbs))
    <ul class="breadcrumb">
        @foreach ($breadcrumbs as $key => $breadcrumb)
            @if ($breadcrumb->url && !$loop->last)
                <li><a href="{{ $breadcrumb->url }}" title="">{{ $breadcrumb->title }}</a></li>
            @else
                <li><a href="{{ $breadcrumb->url }}" title="" class="active">{{ $breadcrumb->title }}</a></li>
            @endif
        @endforeach
    </ul>
@endif
