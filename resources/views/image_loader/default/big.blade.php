<picture {{$attribute}}>
	<source media="(min-width:0px)" data-srcset="{%IMGV2.itemImageShow.img.450x0%}" srcset="{%IMGV2.itemImageShow.img.450x0%}">
	<img loading="{{isset($noLazyLoad) && $noLazyLoad == 1 ? 'auto':'lazy'}}" src="{%IMGV2.itemImageShow.img.450x0%}" data-src="{%IMGV2.itemImageShow.img.450x0%}" title="{%AIMGV2.itemImageShow.img.title%}" {%IMGV2.itemImageShow.img.attr.450x0%} class="img-fluid">
</picture>