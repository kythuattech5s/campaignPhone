<?php
namespace vanhenry\manager\controller;

use DB;
use FCHelper;
use Hash;
use Illuminate\Database\Eloquent\Collection as Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache as Cache;
use Illuminate\Support\Facades\Schema;
use vanhenry\helpers\helpers\JsonHelper as JsonHelper;
use vanhenry\helpers\helpers\SettingHelper;
use vanhenry\helpers\helpers\StringHelper as StringHelper;
use vanhenry\manager\helpers\CT as CT;
use vanhenry\manager\helpers\DetailTableHelper;
use vanhenry\manager\helpers\ModelHelper;
use vanhenry\manager\model\MediaTableDetail;

class Admin extends BaseAdminController
{
    use TableTrait, SearchTrait, EditPermissionTrait, EditTrait, InsertTrait, ViewTrait, ImportTrait, MenuTrait, ExportTrait;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('h_users', ['except' => ['termService', 'getRecursive']]);
    }

    private function curlDataGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function index()
    {
        // return view('vh::index');
        $gaViewKey = SettingHelper::getSetting('ga_view_key');
        return view('vh::dashboard', compact('gaViewKey'));
    }

    public function dashboard(Request $request)
    {
        $gaViewKey = SettingHelper::getSetting('ga_view_key');
        return view('vh::dashboard', compact('gaViewKey'));
    }

    public function changeLanguage($lang)
    {
        if (StringHelper::isNull($lang)) {
            $lang = 'vi';
        }
        CT::setLanguage($lang);
        return redirect()->back();
    }

    public function changePass(Request $request)
    {
        if ($request->isMethod('post')) {
            $post = $request->post();
            $data['password'] = bcrypt($post['password']);
            $oldpass = $post['current_password'];
            $id = $this->retreiveUser('id');
            $password = $this->retreiveUser('password');
            if (Hash::check($oldpass, $password)) {
                $ret = ModelHelper::update('h_users', $id, $data);
                return JsonHelper::echoJson($ret, trans('db::edit') . " " . trans('db::success'));
            } elseif ($post['password'] !== $post['password_confirmation']) {
                return JsonHelper::echoJson(150, trans('db::edit') . " " . trans('db::success'));
            } else {
                return JsonHelper::echoJson(150, trans('db::wong_current_pass'));
            }
        } else {
            return JsonHelper::echoJson(100, trans('db::missing_field'));
        }
    }

    public function __call($method, $args)
    {
        switch ($method) {
            case 'getDataTable':
                switch (count($args)) {
                    case 4:
                        return call_user_func_array(array($this, 'getDataTableThreeArg'), $args);
                    case 2:
                        return call_user_func_array(array($this, 'getDataTableTwoArg'), $args);
                }
                break;
        }
    }

    public function view404()
    {
        return view('vh::404');
    }

    public function noPermission()
    {
        return view('vh::no_permission');
    }

    private function redirect404()
    {
        return redirect()->route('404');
    }

    private function getDataTableTwoArg($model, $data)
    {
        $tableData = $data['tableData'];
        //L???y ra t??n b???ng
        $table = $tableData['table_map'];
        $tableDetailData = $data['tableDetailData'];
        $rpp = $tableData['rpp_admin'];
        $rpp = StringHelper::isNull($rpp) ? 10 : $rpp;
        $query = $model::query();
        return $this->getDataTableThreeArg($query, $tableDetailData, $rpp, $table);
    }

    private function getDataTableThreeArg($query, $tableDetailData, $rpp, $table)
    {
        $fieldSelect = $this->getFieldSelectTable($table, $tableDetailData);
        $ctrash = $tableDetailData->filter(function ($value, $key) {
            return $value->name == "trash";
        });
        // Th??m relationship c???a c??c tr?????ng ???????c hi???n th???
        // $query
        $relationShipShow = $this->getRelationShipTable($tableDetailData);
        if ($relationShipShow->count() > 0) {
            $arrayRelationShip = $this->createRelationShip($relationShipShow);
            $query->with($arrayRelationShip);
        }
        /*N???u c?? b???ng d???ch th?? l???y th??m d??? li???u c???a b???ng n??y*/
        $transTable = FCHelper::getTranslationTable($table);
        if ($transTable == null) {
            $query = $query->select($fieldSelect);
        } else {
            $langChoose = FCHelper::langChooseOfTable($table);
            $query = $query->select($fieldSelect)->join($transTable->table_map, "$table.id", '=', "$transTable->table_map.map_id")->where("$transTable->table_map.language_code", $langChoose);
        }
        if ($ctrash->count() > 0) {
            $query = $query->whereRaw("($table.trash <> 1 or $table.trash is null)");
        }

        /* Check quy???n user ch??? xem nh???ng b???n ghi c???a group con ho???c do m??nh t???o th??m */
        list($checkId, $listInteractiveId) = \vanhenry\manager\helpers\RoleHelper::getListRecordCanInteractive($table);
        if ($checkId) {
            $query->whereIn("$table.id", $listInteractiveId);
        }
        /* Check quy???n user */

        return $query->orderBy("$table.id", 'desc')->paginate($rpp);
    }

    public function createRelationShip($relationship, $arrayRelationShip = [])
    {
        foreach ($relationship as $listItem) {
            $listItem = isset($listItem['data']) ? $listItem['data'] : [];
            foreach ($listItem as $item) {
                $func = function ($query) use ($item) {
                    $select = preg_filter('/^/', $item['table'] . '.', explode(',', $item['select']));
                    if (isset($item['data']) && is_array($item['data']) && count($item['data']) > 0) {
                        $arrayFunc = $this->createRelationShip($item);
                        if (count($arrayFunc) > 0) {
                            $query->select($select)->with(00);
                        }
                    } else {
                        $query->select($select);
                    }
                };
                $arrayRelationShip[$item['name']] = $func;
            }
        }
        return $arrayRelationShip;
    }

    private function createCollectionDetailData($tableDetailData)
    {
        if (!$tableDetailData instanceof Collection) {
            $tableDetailData = new Collection($tableDetailData);
        }
        return $tableDetailData;
    }

    public function getRelationShipTable($tableDetailData)
    {
        $tableDetailData = $this->createCollectionDetailData($tableDetailData);
        $raw_relationships = $tableDetailData->filter(function ($q) {
            return !is_null($q->relationship) && $q->show == 1;
        })->pluck('relationship');

        $relationships = collect();

        foreach ($raw_relationships as $relationship) {
            $relationships[] = json_decode($relationship, true);
        }

        return $relationships;
    }

    /**
     * Danh s??ch tr?????ng active hi???n th??? c???a t???ng b???ng
     */
    protected function getFieldSelectTable($table, $tableDetailData, $force = false)
    {
        $tableDetailData = $this->createCollectionDetailData($tableDetailData);
        $fieldSelect = Cache::remember(CT::$KEY_CACHE_QUERY . $table, CT::$TIME_CACHE_QUERY, function () use ($tableDetailData, $force, $table) {
            $filterShow = DetailTableHelper::filterDataShow($tableDetailData, $force);
            $fieldSelect = array();

            $configOtherTables = FCHelper::getConfigMapTableRewrite($table);
            foreach ($configOtherTables as $type_data) {
                $filterShow = $filterShow->filter(function ($v, $k) use ($type_data) {
                    return !\Str::startsWith($v->name, $type_data['key_catch']);
                });
            }

            foreach ($filterShow as $key => $value) {
                array_push($fieldSelect, $value->parent_name . '.' . $value->name);
            }
            return $fieldSelect;
        });
        return $fieldSelect;
    }

    public function getData(Request $request, $table)
    {
        $inputs = $request->input();
        $target = $request->target;
        $arrSelect = $arrTarget = explode(',', $target);
        $arrSelect[1] = $arrSelect[1] . ' as text';
        if (($key = array_search('id', $arrTarget)) !== false) {
            unset($arrTarget[$key]);
        }
        $arr = DB::table($table)->select($arrSelect);
        $count = 0;
        foreach ($arrTarget as $key => $itemTarget) {
            $arr->where(function ($q) use ($itemTarget, $inputs, $count) {
                if ($count == 0) {
                    $q->where($itemTarget, 'like', '%' . $inputs['q'] . '%');
                } else {
                    $q->orWhere($itemTarget, 'like', '%' . $inputs['q'] . '%');
                }
            });
            $count++;
        }
        
        unset($inputs['q']);
        unset($inputs['_token']);
        unset($inputs['target']);

        foreach ($inputs as $key => $value) {
            $arr->where($key, $value);
        }
        $obj = new \stdClass();
        $obj->results = $arr->get();
        echo json_encode($obj);
    }

    public function getDataPivot()
    {
        $inputs = request()->input();
        if (!\FCHelper::checkStr($inputs['origin_table'])) {
            return;
        }
        $perpage = 10;
        $page = $inputs['page'];
        $target_table = request()->table;
        /*n???u mu???n l???y field # name th?? ph???i name_field as text*/
        $results = \DB::table($target_table)->select('id', 'name as text');
        $transTable = FCHelper::getTranslationTable($target_table);
        $langChoose = FCHelper::langChooseOfTable($inputs['origin_table']);
        if ($transTable != null) {
            $results->join($transTable->table_map, 'id', '=', 'map_id');
        }
        if (\FCHelper::checkStr($inputs['q'])) {
            // $results->where('name', 'like', '%'.$inputs['q'].'%')->where('language_code', $langChoose);
            $results->where('name', 'like', '%' . $inputs['q'] . '%');
        }
        $results = $results->where('act', 1)->groupBy('id')->paginate($perpage);
        $arr = ['results' => $results, 'pagination' => ['more' => $perpage * $page < $results->total()]];
        return response()->json($arr);
    }

    public function getRecursive(Request $request, $table)
    {
        $inputs = $request->input();
        $defaultData = $inputs["data"];
        $arrKey = json_decode($defaultData, true);
        $data = FCHelper::er($arrKey, 'data');
        $config = FCHelper::er($arrKey, 'config');
        $isAjax = $config["ajax"];
        $table = $data["table"];
        $dataMapDefault = $data["default"];
        $arrData = DetailTableHelper::recursiveDataTable($data);
        DetailTableHelper::printOptionRecursiveData($arrData, 0, $dataMapDefault, array(), array());
    }

    public function delete(Request $request, $table)
    {
        $inputs = $request->input();
        if (@$inputs['id']) {
            /* Check th??m quy???n ch??? ???????c x??a nh???ng b???n ghi do user ho???c user trong group con t???o ra */
            $check = \vanhenry\manager\helpers\RoleHelper::checkHUserDeletePermission($table, $inputs['id']);
            if (!$check) {
                return JsonHelper::echoJson(100, 'B???n kh??ng c?? quy???n x??a b???n ghi n??y');
            }
            $id = is_array($inputs['id']) ? $inputs['id'] : [$inputs['id']];
            // T??m ki???m trong b???ng media trong b???ng
            MediaTableDetail::deleteLinked($table, $id);

            // Ki???m tra c?? b???ng d???ch hay kh??ng
            $transTable = \FCHelper::getTranslationTable($table);
            if ($transTable != null) {
                MediaTableDetail::deleteLinked($transTable->table_map, $id);
            }
            /* End check */

            // DB::beginTransaction();
            // try {
            $x = \Event::dispatch('vanhenry.manager.delete.predelete', array($table, $inputs['id']));
            if (count($x) > 0) {
                foreach ($x as $kx => $vx) {
                    if (!$vx['status']) {
                        return JsonHelper::echoJson(100, trans('db::delete') . " " . trans('db::fail'));
                    }
                }
            }
            $ret = ModelHelper::delete($inputs, $table);
            \Event::dispatch('vanhenry.manager.delete.success', array($table, $inputs['id']));
            DB::commit();
            if ($ret == 200) {
                $this->deletePivot([$inputs['id']], $table);
                $this->deleteTranslation([$inputs['id']], $table);
                \DB::table('v_routes')->where(['map_id' => $inputs['id'], 'table' => $table])->delete();
                return JsonHelper::echoJson($ret, trans('db::delete') . " " . trans('db::success'));
            } else {
                return JsonHelper::echoJson($ret, trans('db::missing_field'));
            }
            // } catch (\Exception $e) {
            //       DB::rollBack();
            //   return JsonHelper::echoJson($e,trans('db::missing_field'));
            // }
        } else {
            return JsonHelper::echoJson(100, trans('db::missing_field'));
        }
    }
    public function deletePivot($ids, $table)
    {
        /*list field of table*/
        $tableDetailData = self::__getListDetailTable($table);
        $pivots = $tableDetailData->filter(function ($v, $k) {
            return \Str::before($v->name, '_') == 'pivot';
        });
        foreach ($pivots as $key => $value) {
            $defaultData = json_decode($value->default_data, true);
            if (!is_array($defaultData)) {
                continue;
            }
            $pivot_table = $defaultData['pivot_table'];
            $target_field = $defaultData['target_field'];
            $origin_field = $defaultData['origin_field'];
            if ($defaultData['target_table'] === "combo") {}
            \DB::table($pivot_table)->whereIn($origin_field, $ids)->delete();
        }
    }

    public function deleteTranslation($mapIds, $table)
    {
        $transTable = FCHelper::getTranslationTable($table);
        if ($transTable != null) {
            \DB::table($transTable->table_map)->whereIn('map_id', $mapIds)->delete();
        }
    }
    public function trash(Request $request, $table)
    {
        $ret = ModelHelper::trash($request->input(), $table);
        if ($ret == 200) {
            return JsonHelper::echoJson($ret, trans('db::trash') . " " . trans('db::success'));
        } else {
            return JsonHelper::echoJson($ret, trans('db::missing_field'));
        }
    }
    public function backtrash(Request $request, $table)
    {
        $ret = ModelHelper::trash($request->input(), $table, 0);
        if ($ret == 200) {
            return JsonHelper::echoJson($ret, trans('db::restore') . " " . trans('db::success'));
        } else {
            return JsonHelper::echoJson($ret, trans('db::missing_field'));
        }
    }

    public function deleteAll(Request $request, $table)
    {
        $inputs = $request->input();
        if (@$inputs['id']) {
            $id = json_decode($inputs['id'], true);
            $id = $id == null ? array() : $id;
            /* Check th??m quy???n ch??? ???????c x??a nh???ng b???n ghi do user ho???c user trong group con t???o ra */
            $check = \vanhenry\manager\helpers\RoleHelper::checkHUserDeletePermission($table, $id);
            if (!$check) {
                return JsonHelper::echoJson(100, 'C?? 1 ho???c nhi???u b???n ghi b???n kh??ng c?? quy???n x??a');
            }
            /* End check */

            // T??m ki???m trong b???ng media trong b???ng
            MediaTableDetail::deleteLinked($table, $id);

            // Ki???m tra c?? b???ng d???ch hay kh??ng
            $transTable = \FCHelper::getTranslationTable($table);
            if ($transTable != null) {
                MediaTableDetail::deleteLinked($transTable->table_map, $id);
            }

            $x = \Event::dispatch('vanhenry.manager.delete.predelete', array($table, $id));
            if (count($x) > 0) {
                foreach ($x as $kx => $vx) {
                    if (!$vx['status']) {
                        return JsonHelper::echoJson(100, trans('db::delete') . " " . trans('db::fail'));
                        return;
                    }
                }
            }
            $ret = DB::table($table)->whereIn('id', $id)->delete();
            $this->deletePivot($id, $table);
            $this->deleteTranslation($id, $table);
            \DB::table('v_routes')->whereIn('map_id', $id)->where('table', $table)->delete();
            \Event::dispatch('vanhenry.manager.delete.success', array($table, $id));
            return JsonHelper::echoJson(200, $ret > 0 ? trans('db::delete') . " " . trans('db::success') : trans('db::delete') . " " . trans('db::fail'));
        } else {
            return JsonHelper::echoJson(100, trans('db::missing_field'));
        }
    }

    public function trashAll(Request $request, $table)
    {
        $inputs = $request->input();
        if (@$inputs['id']) {
            $id = json_decode($inputs['id'], true);
            $id = $id == null ? array() : $id;
            /* Check th??m quy???n ch??? ???????c x??a nh???ng b???n ghi do user ho???c user trong group con t???o ra */
            $check = \vanhenry\manager\helpers\RoleHelper::checkHUserDeletePermission($table, $id);
            if (!$check) {
                return JsonHelper::echoJson(100, 'C?? 1 ho???c nhi???u b???n ghi b???n kh??ng c?? quy???n x??a');
            }
            $ret = DB::table($table)->whereIn('id', $id)->update(['trash' => 1]);

            return response(json_encode([
                'code' => 200,
                'message' => 'X??a t???m th??nh c??ng',
            ], JSON_UNESCAPED_UNICODE));
        }
        return response(json_encode([
            'code' => 100,
            'message' => 'X??a t???m kh??ng th??nh c??ng',
        ], JSON_UNESCAPED_UNICODE));
        /* End check */
    }

    public function backTrashAll(Request $request, $table)
    {
        $inputs = $request->input();
        if (@$inputs['id']) {
            $id = json_decode($inputs['id'], true);
            $id = $id == null ? array() : $id;
            /* Check th??m quy???n ch??? ???????c x??a nh???ng b???n ghi do user ho???c user trong group con t???o ra */
            $check = \vanhenry\manager\helpers\RoleHelper::checkHUserDeletePermission($table, $id);
            if (!$check) {
                return JsonHelper::echoJson(100, 'C?? 1 ho???c nhi???u b???n ghi b???n kh??ng c?? quy???n x??a');
            }
            $ret = DB::table($table)->whereIn('id', $id)->update(['trash' => 0]);

            return response(json_encode([
                'code' => 200,
                'message' => 'Kh??i ph???c th??nh c??ng',
            ], JSON_UNESCAPED_UNICODE));
        }
        return response(json_encode([
            'code' => 100,
            'message' => 'X??a t???m kh??ng th??nh c??ng',
        ], JSON_UNESCAPED_UNICODE));
    }

    public function activeAll(Request $request, $table)
    {
        $inputs = $request->input();
        if (@$inputs['id']) {
            $id = json_decode($inputs['id'], true);
            $id = $id == null ? array() : $id;
            /* Check th??m quy???n ch??? ???????c x??a nh???ng b???n ghi do user ho???c user trong group con t???o ra */
            $check = \vanhenry\manager\helpers\RoleHelper::checkHUserDeletePermission($table, $id);
            if (!$check) {
                return JsonHelper::echoJson(100, 'C?? 1 ho???c nhi???u b???n ghi b???n kh??ng c?? quy???n x??a');
            }
            $ret = DB::table($table)->whereIn('id', $id)->update(['act' => 1]);

            return response(json_encode([
                'code' => 200,
                'message' => 'K??ch ho???t th??nh c??ng',
            ], JSON_UNESCAPED_UNICODE));
        }
        return response(json_encode([
            'code' => 100,
            'message' => 'H???y k??ch ho???t kh??ng th??nh c??ng',
        ], JSON_UNESCAPED_UNICODE));
    }

    public function unActiveAll(Request $request, $table)
    {
        $inputs = $request->input();

        if (@$inputs['id']) {
            $id = json_decode($inputs['id'], true);
            $id = $id == null ? array() : $id;

            /* Check th??m quy???n ch??? ???????c x??a nh???ng b???n ghi do user ho???c user trong group con t???o ra */
            $check = \vanhenry\manager\helpers\RoleHelper::checkHUserDeletePermission($table, $id);

            if (!$check) {
                return JsonHelper::echoJson(100, 'C?? 1 ho???c nhi???u b???n ghi b???n kh??ng c?? quy???n x??a');
            }
            $ret = DB::table($table)->whereIn('id', $id)->update(['act' => 0]);
            return response(json_encode([
                'code' => 200,
                'message' => 'H???y k??ch ho???t th??nh c??ng',
            ], JSON_UNESCAPED_UNICODE));
        }
        return response(json_encode([
            'code' => 100,
            'message' => 'H???y k??ch ho???t kh??ng th??nh c??ng',
        ], JSON_UNESCAPED_UNICODE));
    }

    public function getCrypt(Request $request)
    {
        $post = $request->input();
        $pass = $post["pass"];
        echo bcrypt($pass);
        return;
    }

    public function editableAjax(Request $request, $table)
    {
        if ($request->isMethod('post')) {
            $post = $request->input();
            $id = isset($post['id']) ? $post["id"] : 0;

            $tbl = $table;
            if ($table instanceof \vanhenry\manager\model\VTable) {
                $tbl = $table->table_map;
            }
            $oldData = \DB::table($tbl)->find($id);

            /* Check th??m quy???n ch??? ???????c s???a nh???ng b???n ghi do user ho???c user trong group con t???o ra */
            $check = \vanhenry\manager\helpers\RoleHelper::checkIdHUserCanInteractive($table, $id);
            if (!$check) {
                return JsonHelper::echoJson(100, 'B???n kh??ng c?? quy???n s???a b???n ghi n??y');
            }
            /* End check */

            $prop = isset($post["prop"]) ? $post["prop"] : 0;
            $propid = isset($post["prop_id"]) ? $post["prop_id"] : 0;
            unset($post['id']);
            unset($post['_token']);
            unset($post['prop']);
            unset($post['prop_id']);
            if ($prop == 1) {
                $table_meta = $table . "_metas";
                if (Schema::hasTable($table_meta)) {
                    $count = count(DB::table($table_meta)->where('source_id', $id)->where('prop_id', $propid)->get());
                    $meta_value = count($post) > 0 ? array_values($post)[0] : "";
                    if ($count > 0) {
                        $ret = DB::table($table_meta)->where('source_id', $id)->where('prop_id', $propid)->where("meta_key", "")->update(array("meta_value" => $meta_value));
                    } else {
                        $tableData = self::__getListTable()[$table];
                        $lang = $tableData->lang;
                        $lang = explode(",", $lang);
                        $arrInsert = array(array('source_id' => $id, 'prop_id' => $propid, "meta_key" => "", "meta_value" => $meta_value));
                        foreach ($lang as $k => $v) {
                            array_push($arrInsert, array('source_id' => $id, 'prop_id' => $propid, "meta_key" => $v . "_", "meta_value" => $meta_value));
                        }
                        $ret = DB::table($table_meta)->insert(
                            $arrInsert
                        );
                    }
                    $ret = $ret ? 200 : 100;
                } else {
                    $ret = 150;
                }
            } else {
                $transTable = FCHelper::getTranslationTable($table);
                if ($transTable != null) {
                    /*l???y all field c???a b???ng d???ch*/
                    $fields = \DB::select('SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`= database() AND `TABLE_NAME`="' . $transTable->table_map . '"');
                    $arrField = [];
                    foreach ($fields as $key => $field) {
                        $arrField[] = $field->COLUMN_NAME;
                    }
                    $locales = \Config::get('app.locales', []);
                    $langChoose = FCHelper::langChooseOfTable($table);
                    foreach ($post as $key => $value) {
                        if (in_array($key, $arrField) && array_key_exists($langChoose, $locales)) {
                            if ($key == 'slug') {
                                $value = \FCHelper::generateSlugWithLanguage($table, $value, $langChoose, $id);
                            }
                            \DB::table($transTable->table_map)->where(['map_id' => $id, 'language_code' => $langChoose])->update([$key => $value]);
                            unset($post[$key]);
                            $ret = 200;
                        }
                    }
                }
                if (count($post) > 0) {
                    $ret = ModelHelper::update($table, $id, $post);
                }
                \Event::dispatch('vanhenry.manager.update_normal.success', array($table, $post, $id, $oldData));
            }
            return JsonHelper::echoJson($ret, trans('db::edit') . " " . trans('db::success'));
        } else {
            return JsonHelper::echoJson(100, trans('db::missing_field'));
        }
    }

    public function getDataNotId(Request $request, $table)
    {
        $inputs = $request->input();
        $arr = DB::table($table);
        if (isset($inputs['ids'])) {
            $arr->whereNotIn('id', $inputs['ids']);
        }

        $arr = $arr->orderBy('id', 'DESC')->get();
        $obj = new \stdClass();
        $obj->results = $arr;
        echo json_encode($obj);
    }

    public function getDistrictByProvince()
    {
        $data = \DB::table('districts')->where('province_id', request()->province_id)->get();
        $output = '<option value="">Vui l??ng ch???n Qu???n/ huy???n</option>';
        foreach ($data as $item) {
            $output .= '<option value="' . $item->id . '">' . $item->name . '</option>';
        }
        return response()->json([
            'html' => $output,
        ]);
    }

    public function getWardByDistrict()
    {
        $data = \DB::table('wards')->where('district_id', request()->district_id)->get();
        $output = '<option value="">Vui l??ng ch???n Ph?????ng/ x??</option>';
        foreach ($data as $item) {
            $output .= '<option value="' . $item->id . '">' . $item->name . '</option>';
        }
        return response([
            'html' => $output,
        ]);
    }

    public function getDataTableSelect()
    {
        $request = request();
        $data = DetailTableHelper::getDataTable($request->table, explode(',', $request->select));
        return response([
            'data' => $data,
        ]);
    }
}
