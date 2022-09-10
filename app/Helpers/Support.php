<?php
namespace App\Helpers;

use App\Helpers\Media;
use App\Models\Menu;
use Carbon\Carbon;
use Currency;

class Support
{
    public static function isDateTime($string, $format = 'Y-m-d H:i:s')
    {
        return \DateTime::createFromFormat($format, $string);
    }

    public static function showDateTime($string, $format = 'H:i d-m-Y')
    {
        if (self::isDateTime($string)) {
            return Carbon::parse($string)->format($format);
        }
    }

    public static function show($object, $key, $def = '')
    {
        if (!is_object($object) && !is_array($object)) {
            return $def;
        }
        if (is_object($object)) {
            $value = isset($object->$key) ? $object->$key : $def;
        } else {
            $value = isset($object[$key]) ? $object[$key] : $def;
        }
        switch ($key) {
            case 'price':
            case 'price_old':
            case 'starting_price':
            case 'origin_price':
            case 'price_step':
            case 'subtotal':
            case 'priceTotal':
                return Currency::showMoney($value);
                break;
            case 'slug':
                return $value;
                break;
            case 'link':
                return self::language($value);
                break;
            default:
                return $value;
                break;
        }
    }

    public static function language($value)
    {
        if (\App::getLocale() == 'en') {
            return '/en' . '/' . $value;
        } else {
            return $value;
        }
    }

    public static function getMenuRecursive($group = null, int $take = null)
    {
        $menus = Menu::where('menu_category_id', $group)->where('parent', 0)->act()->ord()->with('recursiveChilds');
        if ($take != null) {
            return $menus->take($take)->get();
        }
        return $menus->get();
    }

    public static function showMenuRecursive($menus)
    {
        if ($menus->count() > 0) {
            $addClass = '';
            if ($menus->count() > 10) {
                $addClass = 'big-menu';
            }
            echo '<ul class="' . $addClass . '">';
            foreach ($menus as $menu) {
                $active = url()->current() == url($menu->link) ? "active" : " ";
                echo '<li>';
                echo '<a href="' . $menu->link . '" title="' . \Support::show($menu, 'name') . '" class="' . $active . '" >';
                echo \Support::show($menu, 'name');
                echo '</a>';
                self::showMenuRecursive($menu->recursiveChilds);
                echo '</li>';
            }
            echo '</ul>';
        }
    }

    public static function isLightHouseSp()
    {
        $agent = request()->header('User-Agent');
        preg_match('/Lighthouse/i', $agent, $outs);
        return count($outs) > 0;
    }

    public static function asset($file)
    {
        $file_path = public_path($file);

        if (file_exists($file_path)) {
            return asset($file) . '?v=' . filemtime($file_path);
        } else {
            $path = collect(get_headers(url($file)));

            $path = $path->first(function ($string) {
                return strpos($string, 'path-link') === 0;
            });
            if ($path) {
                $file_path = base_path() . str_replace('path-link: ', '', $path);
            }
            if (file_exists($file_path)) {
                return asset($file) . '?v=' . filemtime($file_path);
            } else {
                return asset($file) . '?v=2';
            }
        }
    }

    public static function numberFormat($number, $stringFormat = ',', $stringChange = '.')
    {
        if (is_null($number) || $number == '') {
            return 0;
        }
        return number_format($number, 0, $stringFormat, $stringChange);
    }

    public static function getAllChildLevel($model, string $field, string $field_parent, int $id, array $categoryIds = [])
    {
        $categoryIds[] = $id;
        $items = $model::where($field_parent, $id)->get();
        if ($items->count() > 0) {
            foreach ($items as $item) {
                $categoryIds = array_merge($categoryIds, self::getAllChildLevel($model, $field, $field_parent, $item->$field, []));
            }
        }
        return $categoryIds;
    }

    public static function URLPrevious(bool $isSet = true, string $defaultUrl = '/')
    {
        $key = 'PREVIOUS_URL_RS';
        if ($isSet) {
            $url = url()->previous();
            $url = strpos($url, url('/')) >= 0 ? $url : url($defaultUrl);
            session()->put($key, $url);
        }

        if (!$isSet) {
            $url = is_null(($url = session()->get($key))) ? url()->previous() : $url;
            $url = $url == url()->current() ? url($defaultUrl) : $url;
            session()->forget($key, null);
        }

        $arrayNotRedirect = ['.js', '.css', '_debugbar', 'javascript', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg'];
        foreach ($arrayNotRedirect as $error) {
            if (strpos($url, $error) !== false) {
                $url = url($defaultUrl);
                break;
            }
        }
        return $url;
    }

    public static function redirectTo($url, $code, $message)
    {
        return redirect()->to($url)->with('messageNotify', $message)->with('typeNotify', $code);
    }

    public static function buildLinkPagination($urlDefault, $pageParam = false)
    {
        $params = request()->all();
        if ($pageParam) {
            $params['page'] = $pageParam;
        }
        $newParam = http_build_query($params);

        if ($newParam !== '') {
            $urlDefault .= '?' . $newParam;
        }
        return $urlDefault;
    }
}
