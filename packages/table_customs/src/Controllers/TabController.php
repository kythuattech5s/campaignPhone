<?php
namespace CustomTable\Controllers;

use FCHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use vanhenry\manager\controller\Admin;

class TabController
{   
    private $admin;

    public function __construct()
    {
        $this->admin = new Admin;    
    } 

     public function getDataTab($request)
    {
        list($tab, $table, $data, $admin, $query) = $request;
        // Lấy trường show
        $dataTable = $data['tableData'];
        $tableDetailData = $data['tableDetailData'];
        $tabs = $tab['tabs'];
        $query = is_string($query) ? $query::query() : $query;
        $paginate = request()->input('limit', 0) !== 0 ? request()->input('limit', 0) : $dataTable['rpp_admin'];

        $filterShow = $data['tableDetailData']->filter(function ($q) {
            return $q->show == 1 || $q->type_show == 'PRIMARY_KEY';
        });

        $configOtherTables = FCHelper::getConfigMapTableRewrite($table);
        foreach ($configOtherTables as $type_data) {
            $filterShow = $filterShow->filter(function ($v, $k) use ($type_data) {
                return !\Str::startsWith($v->name, $type_data['key_catch']);
            });
        }

        $nameFiledShow = [];

        foreach ($filterShow as $item) {
            $nameFiledShow[] = $item->name;
        }
        
        $query->select($nameFiledShow);

        $relationShipShow = $this->admin->getRelationShipTable($tableDetailData);
        if ($relationShipShow->count() > 0) {
            $arrayRelationShip = $this->admin->createRelationShip($relationShipShow);
            $query->with($arrayRelationShip);
        }

        $dataList = [];
        $query->orderBy('id', 'desc');

        foreach ($tabs as $key => $item) {
            $queryNew = clone $query;
            foreach ($item['where'] as $q) {
                $queryNew->where($q['field'], (isset($q['operator']) ? $q['operator'] : '='), $q['value']);
            }
            $dataList[$item['name']] = $queryNew->paginate($paginate);
        }
        return $dataList;
    }
}