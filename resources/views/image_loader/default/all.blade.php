<picture {{$attribute}}>
	<source media="(min-width:450px)" data-srcset="{%IMGV2.itemImageShow.img.-1%}" srcset="{%IMGV2.itemImageShow.img.-1%}">
	<source media="(min-width:0px)" data-srcset="{%IMGV2.itemImageShow.img.450x0%}" srcset="{%IMGV2.itemImageShow.img.450x0%}">
	<img loading="{{isset($noLazyLoad) && $noLazyLoad == 1 ? 'auto':'lazy'}}" src="{%IMGV2.itemImageShow.img.-1%}" {%IMGV2.itemImageShow.img.attr.-1%} data-src="{%IMGV2.itemImageShow.img.-1%}" title="{%AIMGV2.itemImageShow.img.title%}" alt="{%AIMGV2.itemImageShow.img.alt%}" class="img-fluid">
</picture>
