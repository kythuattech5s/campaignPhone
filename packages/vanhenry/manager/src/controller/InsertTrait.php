<?php

namespace vanhenry\manager\controller;

use App\Helpers\MediaHelper;
use DateTime;
use DB;
use FCHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use vanhenry\helpers\helpers\JsonHelper as JsonHelper;
use vanhenry\manager\model\Media;
use vanhenry\manager\model\TableProperty;
use vanhenry\manager\model\VTable;
use \Illuminate\Http\Request;

trait InsertTrait
{
    public function insert($table)
    {
        $tableDetailData = self::__getListDetailTable($table);
        $data['dataItem'] = array();
        $tableData = self::__getListTable()[$table];
        $data['tableData'] = new Collection($tableData);

        // Thêm hành động xử lý view
        $customView = config('sys_view' . '.' . $table . '.view.add');
        if (!is_null($customView)) {
            $class = $customView['class'];
            $method = $customView['method'];
            return (new $class)->$method(['table' => $table]);
        }

        $addDetailData = $this->_edittrait_getTableEditProperties($data['tableData']->get("id"), $tableData);
        $tableDetailData = collect($tableDetailData);
        $tableDetailData = $tableDetailData->merge($addDetailData);
        $tableDetailData = $this->__groupByRegion($tableDetailData);
        $tmpTableDetailData = array();
        foreach ($tableDetailData as $key => $value) {
            $tmpTableDetailData[$key] = $this->__groupByGroup($value)->toArray();
        }
        $data['groupControl'] = $this->__getInfoGroup();
        $data['tableDetailData'] = $tmpTableDetailData;
        $data['actionType'] = "insert";
        return view('vh::edit.view' . $data['tableData']->get("type_show"), $data);
    }
    public function store(Request $request, $table)
    {
        $ret = $this->__insert($request, $table);
        $returnurl = $request->get('returnurl');
        $returnurl = isset($returnurl) && trim($returnurl) != "" ? base64_decode($returnurl) : $this->admincp;
        return Redirect::to($returnurl);
    }
    public function storeAjax(Request $request, $table)
    {
        $data = $request->post();
        $ret = $this->__insert($request, $table);
        switch ($ret) {
            case 100:
                return JsonHelper::echoJson(100, "Thiếu thông tin dữ liệu");
                break;
            case 200:
                return JsonHelper::echoJson(200, "Thêm mới thành công");
                break;
            default:
                return JsonHelper::echoJson(150, "Thêm mới không thành công");
                break;
        }
    }
    public function viewDetail($table, $id)
    {

        $tableDetailData = self::__getListDetailTable($table);
        $dataItem = DB::table($table)->where('id', $id)->get();
        if ($table == 'promotions') {
            $dataItem = DB::table($table)->where('promotions.id', $id)->get();
        }
        if (count($dataItem) > 0) {
            $data['dataItem'] = $dataItem[0];
            $tableData = self::__getListTable()[$table];
            $data['tableData'] = new Collection($tableData);
            $tableDetailData = $this->__groupByRegion($tableDetailData);
            $data['transTable'] = \FCHelper::getTranslationTable($table);
            $tmpTableDetailData = array();
            foreach ($tableDetailData as $key => $value) {
                $tmpTableDetailData[$key] = $this->__groupByGroup($value)->toArray();
            }
            $data['groupControl'] = $this->__getInfoGroup();
            $data['tableDetailData'] = $tmpTableDetailData;
            $data['actionType'] = "copy";
            return view('vh::edit.view_detail', $data);
        } else {
            return redirect($this->admincp . '/view/' . $table);
        }
    }

    public function copy($table, $id)
    {
        $tableDetailData = self::__getListDetailTable($table);
        $transTable = \FCHelper::getTranslationTable($table);

        if ($transTable == null) {
            $dataItem = DB::table($table)->where('id', $id);
        } else {
            $langChoose = FCHelper::langChooseOfTable($table);
            $dataItem = DB::table($table)->join($transTable->table_map . ' as t', 't.map_id', '=', $table . '.id')->where('language_code', $langChoose)->where('id', $id);
        }

        $hidden = config('sys_hidden');
        if (!is_null($hidden) && isset($data[$table]) && $hidden[$table]['where']) {
            foreach ($hidden[$table]['where'] as $itemFilter) {
                $dataItem->where($itemFilter['field'], $itemFilter['operator'], $itemFilter['value']);
            }
        }

        $dataItem = $dataItem->get();

        if (count($dataItem) > 0) {
            $data['dataItem'] = $dataItem[0];
            $tableData = self::__getListTable()[$table];
            $data['tableData'] = new Collection($tableData);

            $customView = config('sys_view' . '.' . $table . '.view.copy');
            if (!is_null($customView)) {
                $class = $customView['class'];
                $method = $customView['method'];
                return (new $class)->$method([
                    'table' => $table,
                    'id' => $id,
                ]);
            }

            $tableDetailData = $this->__groupByRegion($tableDetailData);
            $tmpTableDetailData = array();
            foreach ($tableDetailData as $key => $value) {
                $tmpTableDetailData[$key] = $this->__groupByGroup($value)->toArray();
            }
            $data['groupControl'] = $this->__getInfoGroup();
            $data['tableDetailData'] = $tmpTableDetailData;
            $data['actionType'] = "copy";

            return view('vh::edit.view_copy', $data);
        } else {
            return redirect($this->admincp . '/view/' . $table);
        }
    }
    private function _inserttrait_getPropertiesNormal($table, $post)
    {
        $tablename = $table->table_map;
        $table_meta = $tablename . "_metas";
        $ret = array();
        if (Schema::hasTable($table_meta)) {
            $tableData = new Collection(self::__getListTable()[$tablename]);
            $addDetailData = TableProperty::where("act", 1)->where("parent", $tableData->get("id"))->orderBy("ord")->get()->toArray();
            $arrAdd = collect($addDetailData)->implode("name", ",");
            $arrAdd = explode(",", $arrAdd);
            $lang = $table->lang;
            $lang = explode(",", $lang);
            foreach ($post as $key => $value) {
                if (strpos($key, "_") == 2) {
                    $l = substr($key, 0, 2);
                    if (in_array($l, $lang)) {
                        $ret[$key] = $value;
                        unset($post[$key]);
                        continue;
                    }
                }
                if (in_array($key, $arrAdd)) {
                    $ret[$key] = $value;
                    unset($post[$key]);
                    continue;
                }
            }
        }
        return array("rawpost" => $post, "properties" => $ret);
    }
    public function __insert(Request $request, $table)
    {
        if ($request->isMethod('post')) {
            $action = 'insert';
            $table = VTable::where('table_map', $table)->take(1)->get()->get(0);
            $model = $table->model;
            $data = $request->post();
            if (isset($data['_token'])) {
                unset($data['_token']);
            }

            $x = \Event::dispatch('vanhenry.manager.insert.preinsert', array($table, $data, 0));
            if (count($x) > 0) {
                foreach ($x as $kx => $vx) {
                    if (!$vx['status']) {
                        return $vx["code"];
                    }
                }
            }
            $_d = $this->_inserttrait_getPropertiesNormal($table, $data);

            // Tìm các trường có liên quan đến ảnh để thay đổi title và alt

            $data = $_d["rawpost"];
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = implode(',', $value);
                }
            }
            $tech5s_controller = $data['tech5s_controller'];
            unset($data['tech5s_controller']);
            $transTable = \FCHelper::getTranslationTable($table->table_map);
            /*nếu table có bảng dịch thì insert bảng dịch*/
            if ($transTable != null) {
                /*Tách dữ liệu bảng dịch nếu có*/
                [$data, $transData] = FCHelper::filterData($transTable, $data);
            }

            $configMapTables = FCHelper::getConfigMapTableRewrite($table);

            $dataOtherTables = [];
            foreach ($configMapTables as $type_data) {
                foreach ($data as $key => $value) {
                    if (strpos($key, $type_data['key_catch']) === 0) {
                        $dataOtherTables[$key] = $value;
                        unset($data[$key]);
                    }
                }
            }

            if (isset($data['slug'])) {
                if (strlen($data['slug']) == 0) {
                    $slug = \Str::slug($data['name'], '_');
                } else {
                    $slug = $data['slug'];
                }
                $data['slug'] = FCHelper::generateSlug('v_routes', $slug);
                $item = new $model;
                foreach ($data as $key => $value) {
                    $item->$key = $value;
                }
                $item->save();
                $_id = $item->id;
                if ($_id <= 0) {
                    DB::rollBack();
                    return 150;
                }
                $dataRoutes = array(
                    'controller' => $tech5s_controller,
                    'vi_link' => $data['slug'],
                    'table' => $table->table_map,
                    'vi_name' => isset($data['name']) ? $data['name'] : "",
                    'map_id' => $_id,
                    'updated_at' => new \DateTime(),
                    'created_at' => new \DateTime(),
                    'is_static' => 0,
                );
                DB::table('v_routes')->insert($dataRoutes);
            } else {
                $item = new $model;
                foreach ($data as $key => $value) {
                    $item->$key = $value;
                }
                $item->save();
                $_id = $item->id;
            }
            \DB::beginTransaction();
            if ($_id > 0) {
                /*insert translation table*/
                if (isset($transData)) {
                    $t = $this->__insertTranslationTable($table, $transTable, $transData, $_id);
                    if ($t == false) {
                        \DB::rollback();
                        return 150;
                    }
                }
                \DB::commit();
                $dataTableProperties = $_d["properties"];
                $dataTableProperties["id"] = $_id;
                $this->_edittrait_updatePropertiesNormal($table, $dataTableProperties);
                //update out reference table
                \Event::dispatch('vanhenry.manager.insert.success', array($table, $data, $_id));

                if (isset($data['slug'])) {
                    //Cập nhật lại sitemap mỗi khi update
                    \Event::dispatch('vanhenry.sitemap.autorendersitemap', array($table));
                }
                // Thêm dữ liệu cho các bảng ngoài
                \Event::dispatch('vanhenry.table.creatDataMapTable', array(
                    [
                        'data' => $dataOtherTables,
                        'item' => $item,
                        'action' => $action,
                        'config' => $configMapTables,
                    ],
                ));

                $this->insertMediaTableDetail($item);

                // Kiểm tra nếu có bảng dịch
                if (isset($transData)) {
                    $this->_insertMediaTableDetailHasTranslation($table, $transData, $item->id);
                }
                return 200;
            } else {
                return 150;
            }
        } else {
            return 100;
        }
    }

    private function _insertMediaTableDetailHasTranslation($table, $data, $id)
    {

        $transTable = \FCHelper::getTranslationTable($table->table_map);
        $locales = \Config::get('app.locales', []);

        /*nếu table có bảng dịch thì update bảng dịch*/
        if ($transTable == null) {
            return $data;
        }
        /*Tách data của bảng gốc và bảng dịch ra*/
        [$originData, $transData] = FCHelper::filterData($transTable, $data);
        //Xóa dữ liêu cũ trước khi lưu mới
        $table = $transTable->table_map;
        $arrayInserts = [];
        $configFieldNeedChangeUploadMedia = FCHelper::getFieldNeedUpdateTable($transTable->table_map);

        //Xóa trước khi cập nhật
        foreach ($configFieldNeedChangeUploadMedia as $field) {
            $field_name = $field->name;
            $value = $transData[$field_name];
            $imgs = [];
            switch ($field->type) {
                case 'single':
                    $img = json_decode($value, true);
                    if ($img != null) {
                        $imgs[] = $img;
                    }
                    break;
                case 'multiple':
                    $img = json_decode($value, true);
                    if ($img != null) {
                        $imgs = $img;
                    }
                    break;
                case 'string':
                    if ($value != '') {
                        include_once base_path('plugins/simple_html_dom/simple_html_dom.php');
                        $dom = str_get_html($value);
                        $listImgs = $dom->find('img');
                        foreach ($listImgs as $key => $itemImg) {
                            $hrefImage = str_replace(url('/'), "", $itemImg->attr['src']);
                            if (strpos($hrefImage, 'http') === false) {
                                $info = pathinfo($hrefImage);
                                $dir = $info['dirname'];
                                $name = $info['basename'];
                                $media = Media::where('file_name', $name)->where('path', $dir . '/')->first();
                            } else {
                                $media = MediaHelper::insertFileFromUrl((new DateTime)->format('Y/m'), $hrefImage);
                            }
                            $imgs[] = [
                                'id' => $media->id,
                            ];
                            $itemImg->setAttribute('src', '/' . $media->path . $media->file_name);
                        }
                        foreach ($locales as $locale_key => $valueLocale) {
                            DB::table($table)->where('map_id', $id)->where('language_code', $locale_key)->update([
                                $field_name => $dom->outertext,
                            ]);
                        }
                    }
                    break;
            }

            foreach ($imgs as $img) {
                foreach ($locales as $locale_key => $valueLocale) {
                    $dataItem = [
                        'map_table' => $table,
                        'map_id' => $id,
                        'field' => $field_name,
                        'type_show' => $field->type_show,
                        'media_id' => $img['id'],
                        'language_code' => $locale_key,
                    ];
                    $arrayInserts[] = $dataItem;
                }
            }
        }

        if (count($arrayInserts) > 0) {
            DB::table('media_table_details')->insert($arrayInserts);
        }
    }

    private function insertMediaTableDetail($item)
    {
        $table = $item->getTable();
        $configFieldNeedChangeUploadMedia = FCHelper::getFieldNeedUpdateTable($table);
        $arrayInserts = [];
        foreach ($configFieldNeedChangeUploadMedia as $key => $field) {
            $field_name = $field->name;
            $value = $item->$field_name;
            $imgs = [];
            switch ($field->type) {
                case 'single':
                    $img = json_decode($value, true);
                    if ($img != null) {
                        $imgs[] = $img;
                    }
                    break;
                case 'multiple':
                    $img = json_decode($value, true);
                    if ($img != null) {
                        $imgs = $img;
                    }
                    break;
                case 'string':
                    if ($value != '') {
                        include_once base_path('plugins/simple_html_dom/simple_html_dom.php');
                        $dom = str_get_html($value);
                        $listImgs = $dom->find('img');
                        foreach ($listImgs as $key => $itemImg) {
                            $hrefImage = str_replace(url('/'), "", $itemImg->attr['src']);
                            if (strpos($hrefImage, 'http') === false) {
                                $info = pathinfo($hrefImage);
                                $dir = $info['dirname'];
                                $name = $info['basename'];
                                $media = Media::where('file_name', $name)->where('path', $dir . '/')->first();
                            } else {
                                $media = MediaHelper::insertFileFromUrl((new DateTime())->format('Y/m'), $hrefImage);
                            }
                            $imgs[] = [
                                'id' => $media->id,
                            ];
                            $itemImg->setAttribute('src', '/' . $media->path . $media->file_name);
                        }
                        $item->$field_name = $dom->outertext;
                        $item->save();
                    }
                    break;
            }
            foreach ($imgs as $img) {
                $dataItem = [
                    'map_table' => $table,
                    'map_id' => $item->id,
                    'field' => $field_name,
                    'type_show' => $field->type_show,
                    'media_id' => $img['id'],
                ];
                $arrayInserts[] = $dataItem;
            }
        }
        DB::table('media_table_details')->insert($arrayInserts);
    }

    private function __insertTranslationTable($table, $transTable, $transData, $map_id)
    {
        /*danh sách các ngôn ngữ website đang sử dụng*/
        $locales = \Config::get('app.locales', []);
        $transData['map_id'] = $map_id;
        $insRoutes = [];
        foreach ($locales as $localeCode => $value) {
            if (isset($transData['slug'])) {
                if (strlen($transData['slug']) == 0) {
                    $slugWithLang = \Str::slug($transData['name'], '_');
                } else {
                    $slugWithLang = $transData['slug'];
                }
                $transData['slug'] = FCHelper::generateSlugWithLanguage($slugWithLang, $localeCode, $map_id);
            }
            $transData['language_code'] = $localeCode;
            if (isset($transData['seo_title']) && $transData['seo_title'] == '') {
                $transData['seo_title'] = $transData['name'];
            }
            if (isset($transData['seo_key']) && $transData['seo_key'] == '') {
                $transData['seo_title'] = $transData['name'];
            }
            if (isset($transData['seo_des']) && $transData['seo_des'] == '') {
                $transData['seo_title'] = $transData['name'];
            }
            $ins = \DB::table($transTable->table_map)->insert($transData);
            if ($ins == false) {
                return false;
            }
            if (isset($transData['slug'])) {
                $insRoutes[$localeCode . '_name'] = $transData['name'];
                $insRoutes[$localeCode . '_link'] = $transData['slug'];
                $insRoutes[$localeCode . '_seo_title'] = $transData['seo_title'];
                $insRoutes[$localeCode . '_seo_key'] = $transData['seo_key'];
                $insRoutes[$localeCode . '_seo_des'] = $transData['seo_des'];
            }
        }
        if (isset($insRoutes)) {
            if (count($insRoutes) !== 0) {
                $insRoutes['controller'] = $table->controller;
                $insRoutes['table'] = $table->table_map;
                $insRoutes['map_id'] = $map_id;
                $insRoutes['is_static'] = 0;
                $insRoutes['created_at'] = new \DateTime;
                $insRoutes['updated_at'] = new \DateTime;
                \DB::table('v_routes')->insert($insRoutes);
            }
        }
        return true;
    }
}
