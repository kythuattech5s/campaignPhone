<?php

namespace vanhenry\helpers\helpers;

use DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache as Cache;
use vanhenry\manager\model\MediaTableDetail;

class FCHelper
{
    public static function ep($item, $key, $checklang = 1, $isAdmin = true)
    {
        $isAdmin = self::isAdminUI();
        if (isset($item) && isset($key)) {
            if (is_array($item)) {
                $locale = \App::getLocale();
                $localekey = $locale . "_" . $key;
                if (array_key_exists($localekey, $item)) {
                    return static::getRawData($localekey, $item[$localekey]);
                } else if (array_key_exists($key, $item)) {
                    return static::getRawData($key, $item[$key]);
                }
            } else if (is_object($item)) {
                if (is_subclass_of($item, "Illuminate\Database\Eloquent\Model")) {
                    return static::ep($item->getAttributes(), $key, $checklang, false);
                } else if ($item instanceof \Illuminate\Support\Collection) {
                    return static::ep($item->toArray(), $key, $checklang, false);
                } else if (is_object($item)) {
                    $item = (array) $item;
                    return static::ep($item, $key, $checklang, false);
                } else {
                    //return $item->$key;
                }
            }
            return $key;
        }
        return "";
    }
    public static function getTailLink()
    {
        $seg = request()->segment(1, "");
        $isAdmin = $seg == "esystem";
        if (!$isAdmin) {
            $locale = \App::getLocale();
            if ($locale == 'vi') {
                return '';
            }
            return "?lang=" . $locale;
        }
        return "";
    }
    public static function showStaticLink($url)
    {
        if ($url == "#") {
            $url = "";
        }
        if ($url == "") {
            return $url;
        }
        return $url . self::getTailLink();
    }
    public static function isAdminUI()
    {
        $seg = request()->segment(1, "");
        $isAdmin = $seg == "esystem";
        return $isAdmin;
    }
    private static function getRawData($key, $value)
    {
        return $value;
    }
    public static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    public static function ec($item, $key, $checklang)
    {
        echo FCHelper::ep($item, $key, $checklang);
    }
    public static function objectToArray($data)
    {
        $data = array_map(function ($item) {
            return (array) $item;
        }, $data);
        return $data;
    }

    public static function normalValue($str)
    {
        return trim(strtolower($str));
    }

    public static function er($item, $key, $normal = 0)
    {
        if (!isset($item) || (is_array($item) && count($item) == 0)) {
            return "";
        }

        if ($item instanceof \Illuminate\Database\Eloquent\Model) {
            $item = $item->toArray();
        }
        if (is_object($item)) {
            $item = (array) $item;
            return static::er($item, $key);
        } else if (is_array($item)) {
            $locale = \App::getLocale();
            $fkey = $locale . "_" . $key;
            $finalkey = $key;
            if (array_key_exists($fkey, $item)) {
                $finalkey = $fkey;
            }
            if (array_key_exists($key, $item)) {
                switch ($key) {
                    case 'content':
                    case 'short_content':
                        return html_entity_decode($item[$finalkey]);
                    case 'price':
                    case 'subtotal':
                        return number_format($item[$finalkey], 0, ",", ".");
                    case 'slug':
                        return FCHelper::showStaticLink($item[$finalkey]);
                    case 'created_at':
                    case 'updated_at':
                        return Carbon::parse($item[$finalkey])->format('d/m/Y h:i A');
                    case 'birthday':
                        return Carbon::parse($item[$finalkey])->format('d/m/Y');
                    default:
                        return $normal != 0 ? static::normalValue($item[$finalkey]) : $item[$finalkey];
                }
            }
            return $key;
        }
        return $key;
    }

    public static function eimg($data, $def = "admin/images/noimage.png", $folder = "")
    {
        if ($def == "") {
            $def = "admin/images/noimage.png";
        }

        if (is_string($data) && (strpos($data, 'http://') === 0 || strpos($data, 'https://') === 0)) {
            return $data;
        }

        if (is_string($data) && $data != '' && file_exists(public_path($data))) {
            return $data;
        }

        if (is_array($data)) {
            $json = $data;
        } else if (is_string($data)) {
            $json = json_decode($data, true);
        }
        if (isset($json) && is_array($json) && array_key_exists("path", $json)) {
            $img = $json["path"] . $json["file_name"];
            $def2 = $img;
            if ($folder != "" && $folder != "-1") {
                if (strpos($folder, '.') && isset($json["resizes"])) {
                    list($attr, $folder) = explode('.', $folder);
                    return $folder == '-1' ? $json[$attr] : $json["resizes"][$folder][$attr];
                } else {
                    if (isset($folder) && isset($json["resizes"]) && (file_exists(base_path($json["resizes"][$folder]['path'])) || file_exists($json["resizes"][$folder]['path']))) {
                        $img = $json["resizes"][$folder]['path'];
                    } elseif (file_exists($def2) || file_exists(base_path($def2))) {
                        $img = $def2;
                    } else {
                        $img = $def;
                    }
                }
            }

            if (file_exists($img) || file_exists($img = str_replace('public/', '', $img)) || file_exists(($img = base_path($def2))) || file_exists(($img = str_replace('public/', '', $def2)))) {
                return $img;
            }

            if (isset($json["has_file"]) && $json['has_file'] == 0) {
                return $json["file_name"];
            }
        }

        if (strpos($folder, 'attr') === 0) {
            return '';
        }
        return $def;
    }

    public static function eimg1($item, $key, $def = "admin/images/noimage.png", $folder = "")
    {
        if (is_array($item) && isset($item[$key])) {
            return static::eimg($item[$key], $def, $folder);
        }
        if (!isset($item->$key)) {
            return $def;
        }
        return static::eimg($item->$key, $def, $folder);
    }
    public static function eimg2($item, $key, $folder = "")
    {
        $def = "admin/images/noimage.png";
        if (is_array($item) && isset($item[$key])) {
            return static::eimg($item[$key], $def, $folder);
        }
        if (!isset($item->$key)) {
            return $def;
        }
        return static::eimg($item->$key, $def, $folder);
    }
    public static function aimg($item, $key, $field, $def = "admin/images/noimage.png")
    {

        if (is_array($item) && isset($item[$key])) {
            $data = $item[$key];
        } elseif (isset($item->$key)) {
            $data = $item->$key;
        } else {
            return $def;
        }
        return static::aimg2($data, $field, $def);
    }
    public static function aimg2($data, $field, $def = "admin/images/noimage.png")
    {
        if (!is_array($data)) {
            $json = json_decode($data, true);
            $json = @$json ? $json : array();
        } else {
            $json = $data;
        }

        if (is_array($json) && array_key_exists($field, $json)) {
            $val = $json[$field];
            return $val;
        }
        return $def;
    }
    public static function gallery($item, $key)
    {
        if (!isset($item->$key)) {
            return [];
        }
        $data = $item->$key;
        $json = json_decode($data, true);
        return $json;
    }
    public static function getTranslationTable($tableMap)
    {
        return \vanhenry\manager\model\VTable::getTranslateTable($tableMap);
    }
    public static function recursivePivotPrint($results, $values, $parent = 0, $level = 0)
    {
        if (array_key_exists($parent, $results) && count($results[$parent]) > 0) {
            foreach ($results[$parent] as $k => $item) {
                if ($item['parent'] == $parent) {
                    $str = '<li class="clazzli clazzli-%s %s" data-level="%s">
						<label>
							%s
							<input %s type="checkbox" value="%s"/>
							%s
						</label>
						<span class="expand">-</span>
					';
                    $checked = (is_array($values) && in_array($item['id'], $values)) ? ' checked ' : '';
                    $str = sprintf($str, $item['id'], 'level-' . $level . ' ' . ($checked == '' ? '' : 'choose'), $level, str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level) . ($level > 0 ? "└----" : ''), $checked, $item['id'], $item['name']);
                    echo $str;
                    $nextlevel = $level + 1;
                    self::recursivePivotPrint($results, $values, $item['id'], $nextlevel);
                    echo '</li>';
                }
            }
        }
    }
    public static function recursiveParentPrint($data, $parent = 0, $level = 0, $itemSelect)
    {
        foreach ($data as $k => $item) {
            if ($item['parent'] == $parent) {
                $strChecked = $itemSelect == $item['id'] ? 'selected' : '';
                $str = '<option value="%s" %s>%s %s</option>';
                $str = sprintf($str, $item['id'], $strChecked, str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level) . ($level > 0 ? "└----" : ''), $item['name']);
                echo $str;
                $nextlevel = $level + 1;
                self::recursiveParentPrint($data, $item['id'], $nextlevel, $itemSelect);
            }
        }
    }
    public static function pivotPrint($results, $values)
    {
        foreach ($results as $k => $item) {
            $keys = array_keys($item);

            $str = '<li class="clazzli clazzli-%s">
				<label>
					<input %s type="checkbox" value="%s"/>
					%s
				</label>';
            $checked = (is_array($values) && in_array($item['id'], $values)) ? ' checked ' : '';
            $str = sprintf($str, $item['id'], $checked, $item['id'], isset($keys[1]) ? $item[$keys[1]] : $item['name']);
            echo $str . '</li>';
        }
    }
    public static function viewRecursivePivotPrint($show, $baseUrlSearch, $results, $parent = 0, $level = 0)
    {
        if (array_key_exists($parent, $results) && count($results[$parent]) > 0) {
            foreach ($results[$parent] as $k => $item) {
                if ($item['parent'] == $parent) {
                    $linkFillter = $baseUrlSearch . vsprintf('search-%s=none&type-%s=%s&%s=%s', [$show->name, $show->name, $show->type_show, $show->name, $item['id']]);
                    echo '<p class="select" data-id="' . $item['id'] . '"><a href="' . $linkFillter . '?">' . ($level > 0 ? "└--- " : '') . $item['name'] . '</a></p>';
                    $nextlevel = $level + 1;
                    self::viewRecursivePivotPrint($show, $baseUrlSearch, $results, $item['id'], $nextlevel);
                }
            }
        }
    }
    public static function viewPivotPrint($show, $baseUrlSearch, $results)
    {
        foreach ($results as $k => $item) {
            $linkFillter = $baseUrlSearch . vsprintf('search-%s=none&type-%s=%s&%s=%s', [$show->name, $show->name, $show->type_show, $show->name, $item['id']]);
            echo '<p class="select" data-id="' . $item['id'] . '"><a href="' . $linkFillter . '?">' . $item['name'] . '</a></p>';
        }
    }

    public static function getDataPivot($pivotTable, $originField, $targetTable, $targetField, $columns, $originId = null, $wheres = [])
    {
        $transTableOfTarget = self::getTranslationTable($targetTable);
        foreach ($columns as $key => &$column) {
            $column = 'a.' . $column;
        }
        if ($transTableOfTarget != null) {
            $sql = "SELECT " . implode(',', $columns) . " from $targetTable a inner join $transTableOfTarget->name b on a.id = b.map_id";
            if ($originId != null) {
                $sql .= " inner join $pivotTable c on a.id = c.$targetField where c.$originField = $originId";
            } else {
                $sql .= " where 1 = 1";
            }
            $sql .= " and a.act = 1 and b.language_code = 'vi'";
        } else {
            $sql = "SELECT " . implode(',', $columns) . " from $targetTable a";
            if ($originId != null) {
                $sql .= " inner join $pivotTable c on a.id = c.$targetField where 1 = 1";
                $sql .= " and c.$originField = $originId";
            } else {
                $sql .= ' where 1 = 1';
            }
            $sql .= ' and a.act = 1';
        }
        if (count($wheres) > 0) {
            foreach ($wheres as $key => $where) {
                $sql .= vsprintf(' %s %s %s %s', [$key, str_replace($targetTable . '.', 'a.', $where['key']), $where['compare'], $where['value']]);
            }
        }
        $sql .= ' group by a.id';
        if (in_array('a.parent', $columns)) {
            $sql .= ' order by a.parent';
            $data = \DB::select($sql);
            $arr = [];
            foreach ($data as $key => $value) {
                $arr[] = (array) $value;
            }
            // echo '<pre>'; var_dump($arr); die(); echo '</pre>';
            $arr = self::groupBy($arr, 'parent');
            // echo '<pre>'; var_dump($arr); die(); echo '</pre>';
        } else {
            $sql .= ' order by a.id desc';
            $data = \DB::select($sql);
            $arr = [];
            foreach ($data as $key => $value) {
                $arr[] = (array) $value;
            }
        }
        return $arr;
    }

    public static function groupBy($array, $key)
    {
        $return = array();
        foreach ($array as $val) {
            if (array_key_exists($key, $val)) {
                $return[empty($val[$key]) ? 0 : $val[$key]][] = $val;
            }
        }
        return $return;
    }
    public static function getRealValuePuts($dataItem, $pivotTable, $originField, $targetField)
    {
        $sql = vsprintf('SELECT a.%s from %s a where a.%s = %d', [$targetField, $pivotTable, $originField, $dataItem->id]);
        $valueChooses = \DB::select($sql);
        $arr = [];
        foreach ($valueChooses as $key => $value) {
            $arr[] = $value->$targetField;
        }
        return $arr;
    }

    public static function generateSlugWithLanguage($slugWithLang, $localeCode, $mapId = null)
    {
        $num = 2;
        do {
            $e = \DB::table('v_routes')->select($localeCode . '_link')->where(function ($q) use ($slugWithLang, $localeCode, $mapId) {
                $q->where([$localeCode . '_link' => $slugWithLang]);
                if ($mapId != null) {
                    $q->where('map_id', '!=', $mapId);
                }
            })->first();
            if ($e != null) {
                $slugWithLang .= '-' . $num;
            } else {
                return $slugWithLang;
            }
        } while (true);
    }
    public static function generateSlug($tableMap, $slug, $id = null)
    {
        $num = 2;
        do {
            $e = \DB::table($tableMap)->select('id')->where(function ($q) use ($id, $slug) {
                $q->where(['vi_link' => $slug]);
                if ($id != null) {
                    $q->where('id', '!=', $id);
                }
            })->first();
            if ($e != null && $id == null) {
                $slug .= '-' . $num;
            } else {
                return $slug;
            }
        } while (true);
    }
    public static function getItemTransShow($transTable, $itemMain, $localeCode, $field)
    {
        return \DB::table($transTable->table_map)->select($field . ' as ' . $field . '_' . $localeCode)->where(['map_id' => $itemMain->id, 'language_code' => $localeCode])->first();
    }
    public static function langChooseOfTable($table)
    {
        $langChoose = \Session::get('_table_lang');
        if (!is_array($langChoose) || !array_key_exists($table, $langChoose)) {
            $langChoose = \Config::get('app.locale_origin');
        } else {
            $langChoose = $langChoose[$table];
        }
        return $langChoose;
    }
    public static function filterData($transTable, $data)
    {
        /*lấy all field của bảng dịch*/
        $fields = \DB::select('SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`= database() AND `TABLE_NAME`="' . $transTable->table_map . '"');
        $arrField = [];
        foreach ($fields as $key => $field) {
            $arrField[] = $field->COLUMN_NAME;
        }
        /*Tách data của bảng gốc và bảng dịch ra*/
        $originData = [];
        $transData = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $arrField)) {
                $transData[$key] = $value;
            } else {
                $originData[$key] = $value;
            }
        }
        return [$originData, $transData];
    }

    public static function getDistrict($provinceId)
    {
        $html = '<option disabled selected>Quận huyện</option>';
        $provinces = \DB::select("SELECT * FROM `provinces` WHERE `id` = $provinceId ORDER BY `id` DESC");
        if (count($provinces) == 0) {
            return $html;
        }
        $provinceRealId = $provinces[0]->province_id;
        $districts = \DB::select("SELECT * FROM `districts` WHERE  `province_id` = $provinceRealId ORDER BY `id` DESC");
        foreach ($districts as $district) {
            $html .= '<option value="' . $district->id . '">' . $district->name . '</option>';
        }
        return $html;
    }

    public static function getHistories($arrayKey, $dataItem)
    {
        return DB::table($arrayKey['data_tables'])->where($arrayKey['origin_field'], $dataItem->id)->orderBy('id', 'DESC')->get();
    }

    public static function getHUserById($id)
    {
        return DB::table('h_users')->where('id', $id)->first();
    }

    public static function checkActiveLinkMenuAdmin($link)
    {
        if (\URL::to($link) == \Request::url()) {
            return true;
        }
        $tableUrl = self::getSegment(request(), 3);
        $arrLinkInfo = explode('/', $link);
        if (isset($arrLinkInfo[2]) && $arrLinkInfo[2] == $tableUrl) {
            return true;
        }
        return false;
    }

    public static function getSegment($request, $level)
    {
        if (\App::getLocale() == \Config::get('app.locale_origin')) {
            $seg = $request->segment($level, '');
        } else {
            $seg = $request->segment($level + 1, '');
        }
        return $seg;
    }

    public static function buildUrlSort($show)
    {
        $params = request()->all();
        if (isset($params['orderkey'])) {
            unset($params['orderkey']);
        }
        if ($show->simple_sort == 1) {
            if (isset($params['ordervalue']) && $params['ordervalue'] == 'desc') {
                $ordervalue = 'asc';
            } else {
                $ordervalue = 'desc';
            }
            if (isset($params['ordervalue'])) {
                unset($params['ordervalue']);
            }
            if (count($params) > 0) {
                $paramBuild = implode('&', array_map(function ($val, $key) {
                    return sprintf("%s=%s", $key, $val);
                }, $params, array_keys($params)));
            } else {
                $paramBuild = '';
            }
            $cursor = 'cursor-pointer';
            $url_sort = url()->current() . '?' . $paramBuild . '&orderkey=' . $show->name . '&ordervalue=' . $ordervalue;
        } else {
            $cursor = '';
            $url_sort = '';
            $ordervalue = '';
        }
        return compact('cursor', 'url_sort', 'ordervalue');
    }

    public static function checkHaveActiveLinkMenuAdmin($admincp, $pmenu)
    {
        foreach ($pmenu->childs as $cmenu) {
            $checkInfo = self::checkActiveLinkMenuAdmin($admincp . '/' . $cmenu->link);
            if ($checkInfo) {
                return true;
            }
        }
        return false;
    }

    public static function checkArr(&$var)
    {
        return is_array($var) && count($var) > 0;
    }
    public static function checkObj(&$var)
    {
        return is_object($var) && count((array) $var) > 0;
    }
    public static function checkStr(&$var)
    {
        return is_string($var) && trim($var) != '';
    }
    public static function checkInt(&$var)
    {
        return is_numeric($var) && (int) $var > 0;
    }
    public static function checkFloat(&$var)
    {
        return is_numeric($var) && (float) $var > 0;
    }

    public static function getDataList($table, $name, $tableMain, $field_show)
    {
        return Cache::remember($table . $name . $tableMain, 300, function () use ($field_show, $table) {
            return DB::table($table)->select($field_show)->get();
        });
    }

    public static function getConfigMapTableRewrite($table)
    {
        $table = $table->table_map ?? $table;
        $configDefaultMapTable = collect(config('sys_orther_table.default', []));
        $configCustomMapTable = collect(config('sys_orther_table.' . $table, []));
        $configDefaultMapTable = $configDefaultMapTable->filter(function ($config) use ($configCustomMapTable) {
            return $configCustomMapTable->filter(function ($customConfig) use ($config) {
                return $customConfig['key_catch'] == $config['key_catch'];
            })->count() == 0;
        });
        return $configCustomMapTable->merge($configDefaultMapTable);
    }

    public static function getFieldNeedUpdateTable($table)
    {
        $fields = config('sys_type_show.img');
        $array = [];
        foreach ($fields as $field) {
            $array[] = $field['type_show'];
        }
        $detailTables = DB::table('v_detail_tables')->select('name', 'type_show', 'parent_name')->whereIn('type_show', $array)->where('parent_name', $table)->get();
        foreach ($detailTables as $detail) {
            $type = array_values(array_filter($fields, function ($value, $key) use ($detail) {
                return $value['type_show'] == $detail->type_show;
            }, ARRAY_FILTER_USE_BOTH));

            if (count($type) > 0) {
                $detail->type = $type[0]['type'];
            }
        }
        return $detailTables;
    }

    public static function getFieldNeedUpdateConfig()
    {
        $fields = config('sys_type_show.img');
        $array = [];
        foreach ($fields as $field) {
            $array[] = $field['type_show'];
        }
        $detailTables = DB::table('configs')->select('id', 'name', 'type_show')->whereIn('type_show', $array)->get();
        foreach ($detailTables as $detail) {
            $type = array_filter($fields, function ($value, $key) use ($detail) {
                return mb_strtolower($value['type_show']) == mb_strtolower($detail->type_show);
            }, ARRAY_FILTER_USE_BOTH);
            $type = array_values($type);
            if (count($type) > 0) {
                $detail->type = $type[0]['type'];
            }
        }
        MediaTableDetail::where('map_table', 'configs')->whereIn('map_id', $detailTables->pluck('id'))->delete();
        return $detailTables;
    }
}
