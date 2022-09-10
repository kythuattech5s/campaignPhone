<?php
namespace vanhenry\manager\controller;

use DB;
use Illuminate\Database\Eloquent\Collection as Collection;
use Illuminate\Http\Request;
use vanhenry\helpers\helpers\StringHelper as StringHelper;

trait SearchTrait
{
    /**
     * Tìm kiếm dữ liệu từ các giá trị form submit
     * @param  Request $request
     * @param  [type] $table
     * @return [type]
     */
    public function search(Request $request, $table)
    {
        $tableData = static::__getListTable()[$table];
        $tableDetailData = self::__getListDetailTable($table);
        $options['limit'] = $tableData->rpp_admin;
        $options['orderkey'] = "id";
        $options['ordervalue'] = "desc";
        $inputs = $request->input();
        $inputs = array_replace($options, $inputs);
        $clInputs = collect($inputs);
        $data['tableData'] = new Collection($tableData);
        $data['dataSearch'] = $inputs;
        $data['dataReuse'] = $this->dataReuse($inputs);
        $data['tableDetailData'] = new Collection($tableDetailData);
        $arrSearchMore = $this->filterKey($clInputs, 'search');
        $arrSearchRaw = $this->filterKey($clInputs, 'raw');
        $arrSearchControl = $this->filterKey($clInputs, 'type');
        $arrCondition = $this->getSearchArray($inputs, $arrSearchMore, $arrSearchControl);
        $arrConditionRaw = $this->getSearchArrayRaw($arrSearchRaw);
        $q = $this->makeQuery($tableData->model, $table, $inputs, $arrConditionRaw, $arrCondition);
        if (($customTabs = config('sys_tab' . '.' . $table))) {
            $class = $customTabs['class'];
            $method = $customTabs['method'];
            $data['listData'] = (new $class)->$method([$customTabs, $table, $data, $this, $q]);
        } else {
            $data['listData'] = $this->getDataTable($q, $data['tableDetailData'], $inputs['limit'], $table);
        }
        if (!$request->isMethod('post')) {
            if (array_key_exists("trash", $inputs)) {
                return view('vh::view.viewtrash', $data);
            } else {
                return view('vh::view.view_normal', $data);
            }
        } else {
            return view('vh::view.view_normal', $data);
        }
    }
    /**
     * Lọc Array dựa vào key của mảng bắt đầu bằng kí tự $key
     * @param  Collection $clArray Mảng gốc
     * @param  String $key     Từ khóa
     * @return Array          Mảng đã lọc
     */
    public function filterKey($clArray, $key)
    {
        $ret = $clArray->filter(function ($v, $k) use ($key) {
            return \Str::startsWith($k, $key);
        });
        return $ret;
    }
    /**
     * Gộp danh sách mảng post dữ liệu => Array điều kiện
     * @param  Array $def     Mảng post mặc định
     * @param  Array $more    Mảng search-xxx
     * @param  Array $control Mảng type-xxx
     * @param  Array $pivot   Mảng pivot-xxx
     * @return Array          Mảng [id,1,absolute,PRIMARY_KEY]
     */
    private function getSearchArray($def, $more, $control)
    {
        $ret = array();
        foreach ($control as $key => $value) {
            $_key = substr($key, 5);
            if (isset($def['type-' . $_key]) && $def['type-' . $_key] !== null) {
                $ctl = $control['type-' . $_key];
                $tmp = array(
                    'type_search' => $more["search-" . $_key],
                    'control' => $ctl,
                    'key' => $_key,
                );
                if (in_array(StringHelper::normal($ctl), ["custom_date", "datetime"])) {
                    if (isset($def['from-' . $_key])) {
                        $tmp['value'] = $def['from-' . $_key];
                        $tmp['from'] = $def['from-' . $_key];
                    }
                    if (isset($def['to-' . $_key])) {
                        $tmp['value'] = $def['to-' . $_key];
                        $tmp['to'] = $def['to-' . $_key];
                    }
                } else {
                    if (isset($ctl) && isset($def[$_key])) {
                        $tmp = array('key' => $_key, 'value' => $def[$_key], 'type_search' => $more["search-" . $_key], 'control' => $ctl);
                    }
                }
                array_push($ret, $tmp);
            }
        }
        return $ret;
    }
    private function getSearchArrayRaw($raw)
    {
        $ret = array();
        foreach ($raw as $key => $value) {
            $_key = substr($key, 4);
            $ret[$_key] = $value;
        }
        return $ret;
    }
    /**
     * Tạo câu truy vấn dữ liệu từ mảng đầu vào
     * @param  String $table Tên bảng
     * @param  Array $raw   Giá trị ô tìm kiếm
     * @param  Array $more  Array lấy từ hàm getSearchArray
     * @return Query        Thực hiện truy vấn
     */
    private function makeQuery($model, $table, $inputs, $raw, $more)
    {
        $q = $model::query();
        if (is_array($raw)) {
            foreach ($raw as $key => $value) {
                if (strpos($key, '_type_filter') > 0) {
                    continue;
                }
                if ($value !== null) {
                    if (isset($raw[$key . '_type_filter']) && $raw[$key . '_type_filter'] == '~=') {
                        $q = $q->where($key, 'like', "%" . $value . "%");
                    } else {
                        $q = $q->where($key, $value);
                    }
                }
            }
        }
        if (is_array($more)) {
            foreach ($more as $key => $value) {
                if (isset($value['value'])) {
                    $tcf = $value["control"] == "SELECT" ? "TEXT" : $value["control"];
                    $fnc = 'catchTypeWhere' . $tcf;
                    if (method_exists($this, $fnc)) {
                        $q = $this->$fnc($q, $value, $table);
                    } else {
                        $fnc = 'catchTypeWhereBASE';
                        $q = $this->$fnc($q, $value, $table);
                    }
                }
            }
        }

        if (in_array($inputs['orderkey'], ['date_begin', 'date_expired', 'created_at', 'updated_at'])) {
            $q->orderByRaw(DB::raw('MONTH(' . $table . '.' . $inputs['orderkey'] . ') ' . $inputs["ordervalue"]))->orderByRaw(DB::raw('DAY(' . $table . '.' . $inputs['orderkey'] . ') ' . $inputs["ordervalue"]))->orderBy($inputs['orderkey'], $inputs["ordervalue"]);
        } else {
            $column = $table . '.' . $inputs['orderkey'];
            $type = $inputs['ordervalue'];
            $q->orderByRaw("convert($column, decimal)" . ' ' . "$type");
        }
        return $q;
    }
    private function catchTypeWhereTEXT($query, $value)
    {
        switch ($value['type_search']) {
            default:
            case 'absolute':
                $query = $this->catchTypeWhereBASE($query, $value);
                break;
            case 'relative':
                $query = $query->where($value['key'], "like", "%" . $value['value'] . "%");
                $query = $query->orWhere(function ($query) use ($value) {
                    $query->where($value['key'], "like", "%" . htmlentities($value['value']) . "%");
                });
                break;
        }
        return $query;
    }
    private function catchTypeWhereBASE($query, $value)
    {
        $query = $query->where($value['key'], $value['value']);
        return $query;
    }
    private function catchTypeWhereEDITOR($query, $value)
    {
        $query = $query->where($value['key'], "like", "%" . $value['value'] . "%");
        $query = $query->orWhere(function ($query) use ($value) {
            $query->where($value['key'], "like", "%" . htmlentities($value['value']) . "%");
        });
        return $query;
    }
    private function catchTypeWhereTEXTAREA($query, $value)
    {
        $query = $query->where($value['key'], "like", "%" . $value['value'] . "%");
        $query = $query->orWhere(function ($query) use ($value) {
            $query->where($value['key'], "like", "%" . htmlentities($value['value']) . "%");
        });
        return $query;
    }
    private function catchTypeWhereSELECT($query, $value)
    {
        $query = $query->whereRaw('FIND_IN_SET(' . $value['value'] . ',' . $value['key'] . ') > 0');
        return $query;
    }
    private function catchTypeWhereDATETIME($query, $value, $table)
    {
        $from = !isset($value['from']) ? true : \DateTime::createFromFormat('Y-m-d H:i:s', $value['from']);
        $to = !isset($value['to']) ? true : \DateTime::createFromFormat('Y-m-d H:i:s', $value['to']);

        if (!$from) {
            $from = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $value['from'])));
            $query->where($table . '.' . $value['key'], '>=', $from);
        }
        if (!$to) {
            $to = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $value['to'])));
            $query->where($table . '.' . $value['key'], '<=', $to);
        }
        return $query;
    }
    private function catchTypeWhereCUSTOM_DATE($query, $value, $table)
    {
        $from = !isset($value['from']) ? true : \DateTime::createFromFormat('Y-m-d H:i:s', $value['from']);
        $to = !isset($value['to']) ? true : \DateTime::createFromFormat('Y-m-d H:i:s', $value['to']);
        if (!$from) {
            $from = new \DateTime(str_replace('/', '-', $value['from']));
            $data = clone $from;
            $query->whereDay($table . '.' . $value['key'], '>=', $data->format('d'))->whereMonth($table . '.' . $value['key'], '>=', $data->format('m'));
        }
        if (!$to) {
            $to = new \DateTime(str_replace('/', '-', $value['to']));
            $data = clone $to;
            $query->whereDay($table . '.' . $value['key'], '<=', $data->format('d'))->whereMonth($table . '.' . $value['key'], '<=', $data->format('m'));
        }
        return $query;
    }
    private function catchTypeWherePIVOT($query, $value, $table)
    {
        $infoPivot = \DB::table('v_detail_tables')->where(['name' => $value['key'], 'parent_name' => $table])->first();
        $defaultData = json_decode($infoPivot->default_data, true);
        if (!is_array($defaultData)) {
            return $query;
        }
        $pivot_table = $defaultData['pivot_table'];
        $origin_field = $defaultData['origin_field'];
        $target_table = $defaultData['target_table'];
        $target_field = $defaultData['target_field'];
        $query->join($pivot_table, "$table.id", '=', "$pivot_table.$origin_field")->where("$pivot_table.$target_field", $value['value']);
        return $query;
    }
    private function dataReuse($inputs)
    {
        $keySearchs = \Arr::where($inputs, function ($v, $k) {
            return \Str::before($k, '-') == 'type';
        });
        $fieldWheres = [];
        foreach ($keySearchs as $key => $value) {
            $fieldWheres[str_replace('type-', '', $key)] = $value;
        }
        $inputHiddens = '';
        foreach ($fieldWheres as $k => $v) {
            $inputHiddens .= '<input name="' . ('search-' . $k) . '" type="hidden" value="' . $inputs['search-' . $k] . '">';
            $inputHiddens .= '<input name="' . ('type-' . $k) . '" type="hidden" value="' . $inputs['type-' . $k] . '">';
            if ($v == 'DATETIME') {
                if (isset($inputs['from-' . $k]) && isset($inputs['to-' . $k])) {
                    $inputHiddens .= '<input name="' . ('from-' . $k) . '" type="hidden" value="' . $inputs['from-' . $k] . '">';
                    $inputHiddens .= '<input name="' . ('to-' . $k) . '" type="hidden" value="' . $inputs['to-' . $k] . '">';
                }
            } else {
                $inputHiddens .= '<input name="' . $k . '" type="hidden" value="' . ($inputs[$k] ?? '') . '">';
            }
        }
        return $inputHiddens;
    }
}
