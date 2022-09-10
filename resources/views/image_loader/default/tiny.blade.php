<picture {{$attribute}}>
	<source media="(min-width:0px)" data-srcset="{%IMGV2.itemImageShow.img.150x0%}" srcset="{%IMGV2.itemImageShow.img.150x0%}">
	<img loading="{{isset($noLazyLoad) && $noLazyLoad == 1 ? 'auto':'lazy'}}" src="{%IMGV2.itemImageShow.img.150x0%}" {%IMGV2.itemImageShow.img.attr.150x0%} data-src="{%IMGV2.itemImageShow.img.150x0%}" title="{%AIMGV2.itemImageShow.img.title%}" alt="{%AIMGV2.itemImageShow.img.alt%}" class="img-fluid">
</picture>