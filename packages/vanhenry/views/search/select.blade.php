@php
$dataDefault = json_decode($search->default_data, true);
$config = $dataDefault['config'];
$data = $dataDefault['data'];
$source = $config['source'];
$isAjax = $config['ajax'];
$table = $search->parent_name;
$tableMap = $data['table'];
$where = $data['where'] ?? [];
$dataSelect = explode(',', $data['select']);
$lang = App::getLocale();
$name = strtolower($search->type_show) == 'pivot' ? '' : 'raw_' . $search->name;

$buildDataDefault = collect();
if (isset($data['default'])) {
    $defaultDatas = $data['default'];
    foreach ($defaultDatas as $key => $data) {
        $buildDataDefault[] = [
            'id' => $key,
            'text' => $data[$lang . '_value'],
        ];
    }
}

$buildDataDefault = $buildDataDefault->toJson();

if (isset($dataDefault['data']) && isset($dataDefault['data']['table']) && !$isAjax) {
    $dataValues = DB::table($dataDefault['data']['table'])->select(explode(',', $dataDefault['data']['select']));

    foreach ($where as $whereGroup) {
        foreach ($whereGroup as $keyWhere => $valueWhere) {
            $dataValues->where($keyWhere, $valueWhere);
        }
    }
    $dataValues = $dataValues->get();
} elseif (isset($dataDefault['data']) && !$isAjax) {
    $dataValues = $dataDefault['data'];
} else {
    $dataValues = [];
}
$value = isset($dataSearch['raw_' . $search->name]) ? $dataSearch['raw_' . $search->name] : false;
if ($value) {
    $defaultValue = collect(
        DB::table($tableMap)
            ->select($dataSelect)
            ->where('id', $value)
            ->first(),
    );
}
preg_match('/(.*?)(::)(.+)/', $source, $matches);
$viewSelect = isset($matches[1], $matches[2], $matches[3]) && $matches[2] == '::' ? $matches[1] . $matches[2] . 'ctsearch.select.' . $matches[3] : 'tv::ctsearch.select.' . StringHelper::normal($source);
$viewSelect = View::exists($viewSelect) ? $viewSelect : 'tv::ctsearch.select.normal';
@endphp
<div class="filter-group">
    @include($viewSelect)
</div>
