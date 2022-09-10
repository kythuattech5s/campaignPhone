@php
    $keyImage = $keyImage ?? 'img';
	$itemImageShow = new \StdClass;
	$itemImageShow->img = '';
	if(is_object($itemImage)){
		$itemImageShow->img = $itemImage->$keyImage;
	}
	if(is_array($itemImage)){
		$itemImageShow->img = $itemImage[$keyImage];
	}
@endphp
@include('image_loader.default.tiny',['itemImageShow' => $itemImageShow,'noLazyLoad' => $noLazyLoad ?? 0, 'attribute' => $attribute ?? ''])
@php
	unset($itemImageShow);
@endphp