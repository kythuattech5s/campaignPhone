<?php
namespace vanhenry\helpers\helpers;

use vanhenry\helpers\helpers\FCHelper;
use vanhenry\helpers\helpers\SettingHelper;
use vanhenry\helpers\helpers\StringHelper;

class SEOHelper
{
    public static function showBreadcrumb($currentItem, $itemTable, $itemRoutes)
    {
        $br = '<ul class="breadcrumb"><li itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . url('/') . '">' . trans('fdb::HOME') . '</a></li>';
        if ($currentItem != null && $itemTable != null) {
            if (isset($currentItem->parent)) {
                $tableParent = $itemTable->table_parent;
                $parent = $currentItem->parent;
                $q = \DB::table($tableParent);
                if (is_string($parent)) {
                    $q = $q->whereRaw("find_in_set(id,'?')>0", [$parent]);
                } else {
                    $q = $q->where('id', $parent);
                }
                $arr = $q->get();
                if (count($arr) > 0) {
                    $br .= '<li itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . FCHelper::er($arr[0], 'slug') . '"><span itemprop="title">' . FCHelper::er($arr[0], 'name') . '</span></a></li>';
                    while ($arr[0]->parent != 0) {
                        $parent = $arr[0]->parent;
                        $q = \DB::table($tableParent);
                        $q = $q->where('id', $parent);
                        $arr = $q->get();
                        if (count($arr) > 0) {
                            $br .= '<li itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . FCHelper::er($arr[0], 'slug') . '"><span itemprop="title">' . FCHelper::er($arr[0], 'name') . '</span></a></li>';
                        } else {
                            break;
                        }
                    }
                }
                $br .= '<li itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' . FCHelper::er($currentItem, 'name') . '</span></li>';
            } else {
                $br .= '<li itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' . FCHelper::er($currentItem, 'name') . '</span></li>';
            }
        } elseif ($currentItem != null) {
            if ($itemRoutes != null) {
                $br .= '<li itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' . FCHelper::er($itemRoutes, 'name') . '</span></li>';
            }
        } else {
            if ($itemRoutes != null) {
                $br .= '<li itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">' . FCHelper::er($itemRoutes, 'name') . '</span></li>';
            }
        }
        $br .= '</ul>';
        return $br;
    }
    private static function getFieldSeoByLang($key, $dataitem, $def)
    {
        if (!@$dataitem) {
            return $def;
        }

        if (is_array($dataitem)) {
            $dataitem = (object) $dataitem;
        }
        $lang = \App::getLocale();
        if ($dataitem instanceof \vanhenry\manager\model\VRoute) {
            return StringHelper::isNull($dataitem->{$lang . '_' . $key}) ? $def : $dataitem->{$lang . '_' . $key};
        }
        return isset($dataitem->$key) && !StringHelper::isNull($dataitem->$key) ? $dataitem->$key : $def;
    }
    public static function HEADER_SEO($dataitem)
    {
        $ret = "<base href='" . asset('/') . "'/>";
        $tmp = SettingHelper::getSetting('seo_title');
        $extra = isset($_GET['page']) ? " - " . trans('fdb::page') . " " . $_GET['page'] : "";
        $titleSEO = static::getFieldSeoByLang("seo_title", $dataitem, $tmp) . $extra;
        $tmp = SettingHelper::getSetting('seo_des');
        $desSEO = static::getFieldSeoByLang("seo_des", $dataitem, $tmp);
        $tmp = SettingHelper::getSetting('seo_key');
        $keySEO = static::getFieldSeoByLang("seo_key", $dataitem, $tmp);
        if ($titleSEO == SettingHelper::getSetting('seo_title') && isset($dataitem->name) && $dataitem->name != '') {
            $seo_title = $dataitem->name;
        } else {
            $seo_title = htmlspecialchars($titleSEO);
        }
        if ($seo_title == '') {
            $seo_title = isset($dataitem->name) ? $dataitem->name : '';
        }
        $ret .= '<title>' . $seo_title . '</title>';
        $ret .= '<meta name="description" content="' . addslashes($desSEO) . '">';
        $ret .= '<meta name="keywords" content="' . addslashes($keySEO) . '">';
        $tmp = SettingHelper::getSetting('site_name');
        $ret .= '<meta property="og:site_name" content="' . (StringHelper::isNull($tmp) ? $titleSEO : $tmp) . '">';
        $ret .= '<meta property="og:url" content="' . request()->url() . '">';
        $ret .= '<meta property="og:type" content="article">';
        $ret .= '<meta property="og:title" content="' . addslashes($titleSEO) . '">';
        if (request()->url() == asset('/')) {
            $img = SettingHelper::getSetting('fbshare');
            $img = json_decode($img, true);
            if (@$img) {
                $img = $img["path"] . $img["file_name"];
            } else {
                $logo = json_decode(SettingHelper::getSetting("logo"), true);
                $img = @$logo ? $logo["path"] . $logo["file_name"] : "";
            }
            $pos = strpos($img, 'http');
            if ($pos === false) {
                $img = asset('/') . $img;
            }

        } else {
            $img = (@$dataitem && @$dataitem->img) ? $dataitem->img : "";
            if (StringHelper::isNull($img)) {
                $tmp = (@$dataitem && @$dataitem->content) ? $dataitem->content : "";
                $img = SettingHelper::getSetting('fbshare');
                $img = json_decode($img, true);
                if (@$img) {
                    $img = $img["path"] . $img["file_name"];
                } else {
                    $img = "";
                }
                $img = static::getImageFromContent($tmp, $img);
                if (StringHelper::isNull($img) || $img == 'value') {
                    $logo = json_decode(SettingHelper::getSetting("logo"), true);
                    $img = @$logo ? $logo["path"] . $logo["file_name"] : "";
                }
            } else {
                $img = json_decode($img, true);
                $img = @$img ? $img["path"] . $img["file_name"] : "";
            }
            if (isset($dataitem->share_image_facebook) && $dataitem->share_image_facebook != '') {
                $img = json_decode($dataitem->share_image_facebook, true);
                $img = @$img ? $img["path"] . $img["file_name"] : "";
            }
            $pos = strpos($img, 'http');
            if ($pos === false) {
                $img = asset('/') . $img;
            }

        }
        $ret .= '<meta property="og:image" content="' . $img . '">';
        $ret .= '<meta property="og:locale" content="vi_vn">';
        $wmt = SettingHelper::getSetting('wmt');
        if (!StringHelper::isNull($wmt) && $wmt != "value") {
            $ret .= '<meta name="google-site-verification" content="' . SettingHelper::getSetting('wmt') . '" />';
        }
        $fbappid = SettingHelper::getSetting('fbappid');
        if ('value' != $fbappid) {
            $ret .= '<meta property="fb:app_id" content="' . $fbappid . '">';
        }
        $ret .= '<link rel="canonical" href="' . request()->url() . '">';
        $fav = json_decode(SettingHelper::getSetting('favicon'), true);
        $fav = @$fav ? asset('/') . $fav["path"] . $fav["file_name"] : "";
        $ret .= '<link rel="shortcut icon" href="' . $fav . '">';
        $ret .= '<meta name="csrf-token" content="' . csrf_token() . '">';
        $ret .= '<meta name="is-login" content="' . (\Auth::check() == true ? 1 : 0) . '">';
        $ret .= '<meta name="current-lang" content="' . \App::getLocale() . '">';
        $ret .= '<meta name="exchange-rate" content="' . self::getExchangeRate() . '">';
        return $ret;
    }
    public static function getExchangeRate()
    {
        if (\App::getLocale() == 'vi') {
            return 1;
        } else {
            return SettingHelper::getSetting('exchange_rate');
        }
    }
    public static function getImageFromContent($html, $def)
    {
        preg_match_all('/<img [^>]*src=["|\']([^"|\']+)/i', $html, $matches);
        $val = $def;
        foreach ($matches[1] as $key => $value) {
            $val = $value;
            break;
        }
        $pos = strpos($val, 'http');
        if ($pos === false) {
            $val = asset('/') . $val;
        }

        return $val;
    }

    public static function loadCss($files, $file = "theme/frontend/css/style_chunk.min.css", $isInline = \false)
    {
        set_time_limit(0);
        $minifyTime = 0;
        $needMinify = false;
        if (file_exists(public_path($file))) {
            $minifyTime = filemtime(public_path($file));
            foreach ($files as $key => $value) {
                if (filemtime(public_path($value)) > $minifyTime) {
                    $needMinify = true;
                    break;
                }
            }
        } else {
            $needMinify = true;
        }
        if ($needMinify) {
            $minifier = new \MatthiasMullie\Minify\CSS();
            foreach ($files as $key => $value) {
                $minifier->add(public_path($value));
                $minifier->minify(public_path($file));
            }
        }
        if ($isInline) {
            echo '<style type="text/css">' . file_get_contents(public_path($file)) . '</style>';
        } else {
            echo '<link rel="stylesheet" type="text/css" href="' . \Support::asset($file) . '" defer>';
        }
    }

    public static function loadJs($files, $file = "theme/frontend/js/script_chunk.min.js")
    {
        set_time_limit(0);
        $minifyTime = 0;
        $needMinify = false;
        if (file_exists(public_path($file))) {
            $minifyTime = filemtime(public_path($file));
            foreach ($files as $key => $value) {
                if (filemtime(public_path($value)) > $minifyTime) {
                    $needMinify = true;
                    break;
                }
            }
        } else {
            $needMinify = true;
        }
        if ($needMinify) {
            $minifier = new \MatthiasMullie\Minify\JS();
            foreach ($files as $key => $value) {
                $minifier->add(public_path($value));
                $minifier->minify(public_path($file));
            }
        }
        echo '<script src="' . \Support::asset($file) . '" defer></script>';
    }
}
