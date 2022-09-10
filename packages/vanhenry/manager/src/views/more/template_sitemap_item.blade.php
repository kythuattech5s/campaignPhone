<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    @foreach ($listItems as $item)
        @php
            $checkShow = false;
            if (isset($item->is_static) && (int) $item->is_static == 1) {
                $checkShow = true;
            } else {
                $rootItem = \DB::table($item->table)->find($item->map_id);
                if (isset($rootItem)) {
                    if (isset($rootItem->act) && (int) $rootItem->act == 1) {
                        if (isset($rootItem->noindex)) {
                            if ((int) $rootItem->noindex != 1) {
                                $checkShow = true;
                            }
                        } else {
                            $checkShow = true;
                        }
                    }
                }
                unset($rootItem);
            }
        @endphp
        @if ($checkShow)
            @foreach (Config::get('app.locales') as $locale => $value)
                @php
                    $language = $locale . '_link';
                    if ($locale != Config::get('app.locale_origin')) {
                        $url = url('/') . "/$locale/" . $item->$language;
                    } else {
                        $url = url('/') . '/' . $item->$language;
                    }
                @endphp
                <url>
                    <loc><?= $url ?></loc>
                    <lastmod><?= date_create_from_format('Y-m-d H:i:s', $item->created_at)->format('Y-m-d\TH:i:sP') ?></lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.9</priority>
                </url>
            @endforeach
        @endif
    @endforeach
</urlset>
