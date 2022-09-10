<?php

namespace vanhenry\manager\controller;

use App\Helpers\MediaHelper;
use DateTime;
use DB;
use FCHelper;
use Illuminate\Support\Collection as Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use vanhenry\helpers\helpers\JsonHelper as JsonHelper;
use vanhenry\manager\helpers\GlobalHelper;
use vanhenry\manager\model\Media;
use vanhenry\manager\model\MediaTableDetail;
use vanhenry\manager\model\TableProperty;
use vanhenry\manager\model\VDetailTable;
use vanhenry\manager\model\VTable;
use \Illuminate\Http\Request as Request;

trait EditTrait
{
    private function _edittrait_getTableEditProperties($table_id, $tableData)
    {
        $tmp = TableProperty::where("act", 1)->where("parent", $table_id)->orderBy("ord")->get()->toArray();
        $lang = FCHelper::ep($tableData, "lang");
        $lang = explode(",", $lang);
        $ret = array();
        foreach ($tmp as $key => $value) {
            $value["is_prop"] = 1;
            $t = (object) $value;
            array_push($ret, $t);
            foreach ($lang as $k => $v) {
                $value["name"] = $v . "_" . $value["name"];
                $value["note"] = $value["note"] . " (" . strtoupper($v) . ")";
                array_push($ret, (object) $value);
            }
        }
        return $ret;
    }

    private function _edittrait_getDataFromTableProperties($table, $detaildata, $dataitem)
    {
        $table_meta = $table . "_metas";
        if (Schema::hasTable($table_meta)) {
            $detaildata = collect($detaildata);
            $inProp = $detaildata->implode('id', ',');
            $inProp = explode(",", $inProp);
            $arrProp = DB::table($table_meta)->whereIn("prop_id", $inProp)->where("source_id", $dataitem->id)->get();
            $listData = array();
            foreach ($arrProp as $key => $value) {
                $desired_object = $detaildata->filter(function ($item) use ($value) {
                    return $item->id == $value->prop_id;
                })->first();
                $_k = $value->meta_key . $desired_object->name;
                $_v = $value->meta_value;
                $dataitem->$_k = $_v;
            }
        }
        return $dataitem;
    }

    public function edit($table, $id)
    {
        // Check không cho sửa khi đang có 1 người đang chỉnh sửa\
        $configCheck = config("sys_table.checkEdit");
        $config = array_filter($configCheck, function ($key) use ($table) {
            return $key === $table;
        }, ARRAY_FILTER_USE_KEY);
        if (count($config) > 0 && isset($config[$table]) && isset($config[$table]['class']) && isset($config[$table]['method']) && isset($config[$table]['time'])) {
            $class = $config[$table]['class'];
            $method = $config[$table]['method'];
            if (($messageError = (new $class)->$method($config, $table, $id))) {
                return $messageError;
            }
        }

        $tableData = self::__getListTable()[$table];

        // Thay đổi config view
        $customView = config('sys_view' . '.' . $table . '.view.edit');
        if (!is_null($customView)) {
            $class = $customView['class'];
            $method = $customView['method'];
            return (new $class)->$method([
                'table' => $table,
                'id' => $id,
            ]);
        }
        $type = $tableData->type_show;
        $fnc = 'edit' . $type;
        if (!method_exists($this, $fnc)) {
            $fnc = 'edit_normal';
        }
        return $this->$fnc($table, $tableData, $id);
    }

    public function edit_normal($table, $tableData, $id)
    {
        $tableDetailData = self::__getListDetailTable($table);
        $transTable = \FCHelper::getTranslationTable($tableData->table_map);
        /*nếu table không có bảng dịch*/
        if ($transTable == null) {
            $dataItem = DB::table($table)->where('id', $id);
        } else {
            $langChoose = FCHelper::langChooseOfTable($tableData->table_map);
            $dataItem = DB::table($table)->join($transTable->table_map, 'id', '=', 'map_id')->where(['id' => $id, 'language_code' => $langChoose]);
        }

        $hidden = config('sys_hidden');
        if (!is_null($hidden) && isset($data[$table]) && $data[$table]['where']) {
            foreach ($data[$table]['where'] as $itemFilter) {
                $dataItem->where($itemFilter['field'], $itemFilter['operator'], $itemFilter['value']);
            }
        }

        $dataItem = $dataItem->get();

        if (count($dataItem) > 0) {
            $data['tableData'] = new Collection($tableData);
            $addDetailData = $this->_edittrait_getTableEditProperties($data['tableData']->get("id"), $tableData);
            $data['dataItem'] = $this->_edittrait_getDataFromTableProperties($table, $addDetailData, $dataItem[0]);
            $tableDetailData = collect($tableDetailData);
            $tableDetailData = $tableDetailData->merge($addDetailData);
            $tableDetailData = $this->__groupByRegion($tableDetailData);
            $tmpTableDetailData = array();
            foreach ($tableDetailData as $key => $value) {
                $tmpTableDetailData[$key] = $this->__groupByGroup($value)->toArray();
            }
            $data['groupControl'] = $this->__getInfoGroup();
            $data['tableDetailData'] = $tmpTableDetailData;
            $data['actionType'] = "edit";
            return view('vh::edit.view' . $tableData->type_show, $data);
        } else {
            return redirect($this->admincp . '/view/' . $table);
        }
    }

    public function edit_config($table, $tableData, $id)
    {
        $regions = $this->getConfigRegions($table);
        $data['tableData'] = collect($tableData);
        $data['listRegions'] = $regions;
        $data['listConfigs'] = collect(\DB::table($table)->where("act", 1)->orderBy("ord", "asc")->get());
        $data['actionType'] = 'edit';
        $data['table'] = new VDetailTable;
        return view('vh::edit.view_config', $data);
    }

    public function save(Request $request, $table, $id)
    {
        $table = VTable::where('table_map', $table)->take(1)->get();
        $fnc = "__update_normal";
        if ($table->count() > 0) {
            $fnc = "__update" . $table->first()->type_show;
            if (!method_exists($this, $fnc)) {
                $fnc = "__update_normal";
            }
            $ret = $this->$fnc($request, $table->get(0), $id);
        }
        $returnurl = $request->get('returnurl');
        $returnurl = isset($returnurl) && trim($returnurl) != "" ? base64_decode($returnurl) : $this->admincp;
        return Redirect::to($returnurl);
    }

    public function update(Request $request, $table, $id)
    {
        $ret = $this->__update($request, $table, $id);
        $table = VTable::where('table_map', $table)->take(1)->get();
        $fnc = "__update_normal";
        $ret = 0;
        if ($table->count() > 0) {
            $fnc = "__update" . $table->get(0)->type_show;
            if (!method_exists($this, $fnc)) {
                $fnc = "__update_normal";
            }
            $ret = $this->$fnc($request, $table->get(0), $id);
        }
        switch ($ret) {
            case 100:
                return JsonHelper::echoJson(100, "Thiếu thông tin dữ liệu");
                break;
            case 200:
                return JsonHelper::echoJson(200, "Cập nhật thành công");
                break;
            default:
                return JsonHelper::echoJson(150, "Cập nhật không thành công");
                break;
        }
    }

    public function deleteChooseNow($id, $chooses, $table)
    {
        foreach ($chooses as $key => $value) {
            $vdetail = VDetailTable::where(['parent_name' => $table, 'name' => $key])->first();
            if ($vdetail == null) {
                continue;
            }
            $default_data = json_decode($vdetail->default_data, true);
            $table_main = $default_data['table'];
            $origin_field_target = $default_data['origin_field_target'];
            $pivot = DB::table($table_main)->where($origin_field_target, $id)->delete();
        }
    }

    public function addAllToParent(Request $request, $table)
    {
        $ret = 100;
        if ($request->isMethod('post')) {
            $post = $request->post();
            $arrId = json_decode($post['groupid']);
            $parent = $post['parent'];
            $type = $post['type'];
            $ret = $this->__addToParent($table, $arrId, $parent, strtolower($type));
        }
        switch ($ret) {
            case 100:
                return JsonHelper::echoJson(100, "Thiếu thông tin dữ liệu");
                break;
            case 200:
                return JsonHelper::echoJson(300, "Cập nhật thành công");
                break;
            default:
                return JsonHelper::echoJson(150, "Cập nhật không thành công");
                break;
        }
    }

    public function removeFromParent(Request $request, $table)
    {
        $ret = 100;
        if ($request->isMethod('post')) {
            $post = $request->post();
            $arrId = json_decode($post['groupid']);
            $parent = $post['parent'];
            $type = $post['type'];
            $ret = $this->__removeFromParent($table, $arrId, $parent, strtolower($type));
        }
        switch ($ret) {
            case 100:
                return JsonHelper::echoJson(100, "Thiếu thông tin dữ liệu");
                break;
            case 200:
                return JsonHelper::echoJson(300, "Cập nhật thành công");
                break;
            default:
                return JsonHelper::echoJson(150, "Cập nhật không thành công");
                break;
        }
    }

    private function __removeFromParent($table, $arrId, $parent, $type)
    {
        if ($type == 'select') {
            $ret = DB::table($table)->whereIn('id', $arrId)->update(array('parent' => null));
        } elseif ($type == 'multiselect') {
            $arr = DB::table($table)->whereIn('id', $arrId)->get();
            foreach ($arr as $key => $value) {
                $tmp = $value->parent;
                $tmp = explode(',', $tmp);
                if (!in_array($parent, $tmp)) {
                    unset($tmp[$parent]);
                    DB::table($table)->where('id', $value->id)->update(array('parent' => implode(',', $tmp)));
                }
            }
        } else {
            return 150;
        }

        \Event::dispatch('vanhenry.manager.removefromparent.success', array($table, $parent, $arrId));
        return 200;
    }

    private function __addToParent($table, $arrId, $parent, $type)
    {
        if ($type == 'select') {
            $ret = DB::table($table)->whereIn('id', $arrId)->update(array('parent' => $parent));
        } elseif ($type == 'multiselect') {
            $arr = DB::table($table)->whereIn('id', $arrId)->get();
            foreach ($arr as $key => $value) {
                $tmp = $value->parent;
                $tmp = explode(',', $tmp);
                if (!in_array($parent, $tmp)) {
                    array_push($tmp, $parent);
                    DB::table($table)->where('id', $value->id)->update(array('parent' => implode(',', $tmp)));
                }
            }
        } else {
            return 150;
        }
        \Event::dispatch('vanhenry.manager.addtoparent.success', array($table, $parent, $arrId));
        return 200;
    }
    private function _edittrait_updatePropertiesNormal($table, $post)
    {
        $tablename = $table->table_map;
        $table_meta = $tablename . "_metas";
        if (Schema::hasTable($table_meta)) {
            $tableData = new Collection(self::__getListTable()[$tablename]);
            $addDetailData = TableProperty::where("act", 1)->where("parent", $tableData->get("id"))->orderBy("ord")->get()->toArray();
            $arrAdd = collect($addDetailData)->implode("name", ",");
            $arrAdd = explode(",", $arrAdd);
            $lang = $table->lang;
            $lang = explode(",", $lang);
            $ret = array();
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
            $total = array();
            foreach ($ret as $key => $value) {
                $_key = $key;
                if (strpos($key, "_") == 2) {
                    $_key = substr($key, 2 + 1);
                }
                if (array_key_exists($_key, $total)) {
                    continue;
                }
                $tmp = array();
                if (array_key_exists($_key, $ret)) {
                    $tmp["vi"] = $ret[$_key];
                }
                foreach ($lang as $k => $v) {
                    if (array_key_exists($v . "_" . $key, $ret)) {
                        $tmp[$v] = $ret[$v . "_" . $key];
                    }
                }
                $total[$_key] = $tmp;
            }
            DB::table($table_meta)->where("source_id", $post["id"])->delete();
            $arrInsert = array();
            foreach ($addDetailData as $key => $value) {
                if (array_key_exists($value["name"], $total)) {
                    foreach ($total[$value["name"]] as $k => $v) {
                        $tmp = array("source_id" => $post["id"], "meta_key" => $k == "vi" ? "" : $k . "_", "meta_value" => ($v == $value["name"] || $v == $k . "_" . $value["name"]) ? "" : $v, "prop_id" => $value["id"]);
                        array_push($arrInsert, $tmp);
                    }
                }
            }
            DB::table($table_meta)->insert($arrInsert);
        }
        return $post;
    }
    private function __update_normal(Request $request, $table, $id)
    {
        if ($request->isMethod('post')) {
            $action = 'update';
            $tbl = $table;
            if ($table instanceof \vanhenry\manager\model\VTable) {
                $tbl = $table->table_map;
            }
            $model = $table->model;
            $item = $model::find($id);
            $oldData = clone $item;
            $data = $request->post();
            if (isset($data['_token'])) {
                unset($data['_token']);
            }
            $x = \Event::dispatch('vanhenry.manager.update_normal.preupdate', array($table, $data, $id));
            if (count($x) > 0) {
                foreach ($x as $kx => $vx) {
                    if (!$vx['status']) {
                        return $vx["code"];
                    }
                }
            }
            $data = $this->_edittrait_updatePropertiesNormal($table, $data);
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = implode(',', $value);
                }
            }
            $tech5s_controller = $data['tech5s_controller'];
            unset($data['tech5s_controller']);

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
                $_arrSlug = DB::table('v_routes')->where(array('table' => $table->table_map, 'map_id' => $id))->get();
                if (count($_arrSlug) > 0) {
                    $data['slug'] = FCHelper::generateSlug('v_routes', $data['slug'], $id);
                    DB::table('v_routes')->where('id', $_arrSlug[0]->id)->update([
                        'updated_at' => new \DateTime(),
                        'vi_link' => $data['slug'],
                        'controller' => $table->controller,
                    ]);
                } else {
                    $dataRoutes = array(
                        'controller' => $tech5s_controller,
                        'vi_link' => $data['slug'],
                        'table' => $table->table_map,
                        'vi_name' => isset($data['name']) ? $data['name'] : "",
                        'map_id' => $data['id'],
                        'updated_at' => new \DateTime(),
                        'created_at' => new \DateTime(),
                        'is_static' => 0,
                    );
                    $ret = DB::table('v_routes')->insert($dataRoutes);
                }
            }

            /*update bảng dịch nếu có*/
            $dataFull = $data;
            $data = $this->__updateTranslationTable($table, $data, $id);

            if (isset($data['updated_at'])) {
                $data["updated_at"] = new \DateTime();
            }

            foreach ($data as $key => $value) {
                $item->$key = $value;
            }
            $item->save();
            \Event::dispatch('vanhenry.manager.update_normal.success', array($table, $data, $id, $oldData));
            if (isset($data['slug'])) {
                //Cập nhật lại sitemap mỗi khi update
                \Event::dispatch('vanhenry.sitemap.autorendersitemap', array($table));
            }

            \Event::dispatch('vanhenry.table.updateDataMapTable', array(
                [
                    'data' => $dataOtherTables,
                    'item' => $item,
                    'action' => $action,
                    'config' => $configMapTables,
                ],
            ));
            // Cấp nhật bảng map hình ảnh
            $this->updateMediaTableDetail($item);

            // Kiểm tra nếu có bảng dịch
            $this->_updateMediaTableDetailHasTranslation($table, $dataFull, $id);

            return 200;
        } else {
            return 100;
        }
    }
    private function _updateMediaTableDetailHasTranslation($table, $data, $id)
    {

        $transTable = \FCHelper::getTranslationTable($table->table_map);
        /*nếu table có bảng dịch thì update bảng dịch*/
        if ($transTable == null) {
            return $data;
        }
        /*Tách data của bảng gốc và bảng dịch ra*/
        [$originData, $transData] = FCHelper::filterData($transTable, $data);
        $langChoose = FCHelper::langChooseOfTable($table->table_map);
        //Xóa dữ liêu cũ trước khi lưu mới
        DB::table('media_table_details')->where('language_code', $langChoose)->where('map_table', $table)->where('map_id', $id)->delete();

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
                                $media = MediaHelper::insertFileFromUrl((new DateTime())->format('Y/m'), $hrefImage);
                            }
                            $imgs[] = [
                                'id' => $media->id,
                            ];
                            $itemImg->setAttribute('src', '/' . $media->path . $media->file_name);
                        }
                        DB::table($table)->where('map_id', $id)->where('language_code', $langChoose)->update([
                            $field_name => $dom->outertext,
                        ]);
                    }
                    break;
            }

            foreach ($imgs as $img) {
                $dataItem = [
                    'map_table' => $table,
                    'map_id' => $id,
                    'field' => $field_name,
                    'type_show' => $field->type_show,
                    'media_id' => $img['id'],
                    'language_code' => $langChoose,
                ];
                $arrayInserts[] = $dataItem;
            }
        }

        if (count($arrayInserts) > 0) {
            DB::table('media_table_details')->insert($arrayInserts);
        }
    }

    private function updateMediaTableDetail($item)
    {
        $arrayInserts = [];
        $table = $item->getTable();
        MediaTableDetail::where('map_table', $table)->where('map_id', $item->id)->delete();
        $configFieldNeedChangeUploadMedia = FCHelper::getFieldNeedUpdateTable($table);
        //Xóa trước khi cập nhật
        foreach ($configFieldNeedChangeUploadMedia as $field) {
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
                            } elseif (strpos($hrefImage, 'http') >= 0) {
                                $media = MediaHelper::insertFileFromUrl((new DateTime())->format('Y/m'), $hrefImage);
                            }
                            if (isset($media)) {
                                $imgs[] = [
                                    'id' => $media->id,
                                ];
                                $itemImg->setAttribute('src', '/' . $media->path . $media->file_name);
                            }
                            unset($media);
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

        if (count($arrayInserts) > 0) {
            DB::table('media_table_details')->insert($arrayInserts);
        }
    }

    private function __updateTranslationTable($table, $data, $map_id, $langChoose = 'vi')
    {

        $transTable = \FCHelper::getTranslationTable($table->table_map);
        /*nếu table có bảng dịch thì update bảng dịch*/
        if ($transTable == null) {
            return $data;
        }
        /*Tách data của bảng gốc và bảng dịch ra*/
        [$originData, $transData] = FCHelper::filterData($transTable, $data);
        /*danh sách các ngôn ngữ website đang sử dụng*/
        $locales = \Config::get('app.locales', []);
        /*Ngôn ngữ đang thao tác với bảng*/
        $langChoose = FCHelper::langChooseOfTable($table->table_map);
        if (!array_key_exists($langChoose, $locales)) {
            return $originData;
        }
        /*update translation table*/
        $transDb = \DB::table($transTable->table_map)->where(['map_id' => $map_id, 'language_code' => $langChoose])->first();
        if ($transDb == null) {
            return $originData;
        }

        if (isset($transData['slug'])) {
            if (strlen($transData['slug']) == 0) {
                $slugWithLang = \Str::slug($transData['name'], '_');
            } else {
                $slugWithLang = $transData['slug'];
            }
            $transData['slug'] = FCHelper::generateSlugWithLanguage($slugWithLang, $langChoose, $map_id);
            //     // update route
            if ($table->controller !== null) {
                $vRoute = \DB::table('v_routes')->where('table', $table->table_map)->where('map_id', $map_id)->first();
                $insOrUpVroute = [
                    $langChoose . '_name' => $data['name'],
                    $langChoose . '_link' => $data['slug'],
                    $langChoose . '_seo_title' => $data['seo_title'] ?? '',
                    $langChoose . '_seo_key' => $data['seo_key'] ?? '',
                    $langChoose . '_seo_des' => $data['seo_des'] ?? '',
                    'updated_at' => new \DateTime,
                ];
                if ($vRoute == null) {
                    $insOrUpVroute['controller'] = $table->controller;
                    $insOrUpVroute['table'] = $table->table_map;
                    $insOrUpVroute['map_id'] = $map_id;
                    $insOrUpVroute['is_static'] = 0;
                    \DB::table('v_routes')->insert($insOrUpVroute);
                } else {
                    \DB::table('v_routes')->where('table', $table->table_map)->where('map_id', $map_id)->update($insOrUpVroute);
                }
            }
            return $data;
        }

        \DB::table($transTable->table_map)->where(['map_id' => $map_id, 'language_code' => $langChoose])->update($transData);
        return $originData;
    }

    private function __update_config($request, $table, $id)
    {
        if ($request->isMethod('post')) {
            $data = $request->post();
            if (isset($data['_token'])) {
                unset($data['_token']);
            }
            $pureDataKey = $this->__groupConfigs($data);
            $tableData = self::__getListTable()[$table->table_map];
            $multilang = json_decode($tableData->default_data, true);
            $multilang = isset($multilang) ? $multilang : array();
            $localTable = $table->table_map;

            $configFieldNeedChangeUploadMedia = FCHelper::getFieldNeedUpdateConfig();
            $ret = DB::transaction(function () use ($pureDataKey, $data, $multilang, $localTable, $configFieldNeedChangeUploadMedia) {
                foreach ($pureDataKey as $key => $value) {
                    $c = GlobalHelper::getModel($localTable);
                    $_realkey = substr($value, 3);

                    $c1 = $c->find(strtoupper($_realkey));

                    $mediaTable = $configFieldNeedChangeUploadMedia->first(function ($value, $key) use ($_realkey) {
                        return $value->id == $_realkey;
                    });

                    foreach ($multilang as $klang => $vlang) {
                        $_reallang = $klang . "_value";
                        if (array_key_exists($klang . "_" . $_realkey, $data)) {
                            $tmp = $data[$klang . "_" . $_realkey];
                            $c1->$_reallang = is_array($tmp) ? implode(",", $tmp) : $tmp;
                        } else if (array_key_exists("vi_" . $_realkey, $data)) {
                            $tmp = $data["vi_" . $_realkey];
                            $c1->$_reallang = is_array($tmp) ? implode(",", $tmp) : $tmp;
                        }
                    }

                    $r = $c1->save();
                    if ($mediaTable !== null) {
                        $this->__insertMediaTableDetailConfig($mediaTable, $c1, $multilang);
                    }
                }
                return 200;
            });
            if ($ret == 200) {
                \Event::dispatch('vanhenry.manager.update_config.success', array($table, $data, $id));
            }
            return $ret;
        } else {
            return 100;
        }
    }

    private function __insertMediaTableDetailConfig($mediaTable, $item, $languages)
    {
        $arrInsert = [];
        foreach ($languages as $lang => $value) {
            $field = $lang . "_value";
            $value = $item->$field;
            $imgs = [];
            if ($value != null) {
                switch ($mediaTable->type) {
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
                        $item->$field = $dom->outertext;
                        $item->save();
                    }
                    break;  
                }
            }
            foreach ($imgs as $img) {
                $arrInsert[] = [
                    'map_id' => $item->id,
                    'media_id' => $img['id'],
                    'field' => $field,
                    'map_table' => 'configs',
                    'type_show' => $item->type_show,
                ];
            }
        }
        DB::table('media_table_details')->insert($arrInsert);
    }

    private function __groupConfigs($data)
    {
        $pureDataKey = array();
        foreach ($data as $key => $value) {
            if (\Str::startsWith($key, 'vi_')) {
                array_push($pureDataKey, $key);
            }
        }
        return $pureDataKey;
    }
}
