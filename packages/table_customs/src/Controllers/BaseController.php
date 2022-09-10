<?php
namespace CustomTable\Controllers;

use DB;
use vanhenry\manager\model\VDetailTable;

class BaseController
{
    public function __insertPivots($pivots, $item)
    {
        $table = $item->getTable();
        foreach ($pivots as $key => $pivot) {
            $vdetail = VDetailTable::where(['parent_name' => $table, 'name' => $key])->first();
            if ($vdetail == null) {
                continue;
            }
            $relationship = json_decode($vdetail->relationship, true);
            $pivotValues = array_filter(explode(',', $pivot));

            foreach ($relationship['data'] as $relation) {
                $name = $relation['name'];
                $item->$name()->sync($pivotValues);
            }
        }
    }

    public function __updatePivots($pivots, $item)
    {
        $table = $item->getTable();
        foreach ($pivots as $key => $pivot) {
            $vdetail = VDetailTable::where(['parent_name' => $table, 'name' => $key])->first();
            if ($vdetail == null) {
                continue;
            }
            $relationship = json_decode($vdetail->relationship, true);
            $pivotValues = array_filter(explode(',', $pivot));

            foreach ($relationship['data'] as $relation) {
                $name = $relation['name'];
                $item->$name()->sync($pivotValues);
            }
        }
    }

    public function __updateDataMapTable($rs_create, $id, $table)
    {
        $table = $table->table_map;

        if (!empty($rs_create) && count($rs_create) > 0) {
            $data = request()->all();
            $vdetail = VDetailTable::where(['parent_name' => $table, 'name' => array_key_first($rs_create)])->first();
            $defaultData = json_decode($vdetail->default_data, true);
            $dataCreate = [];
            foreach ($data as $key => $value) {
                if (in_array($key, $defaultData['field_duplicate'])) {
                    $dataCreate[$key] = $value;
                }
            }
            $tableInsert = $defaultData['table'];
            $dataCreate[$defaultData['field']] = reset($rs_create);
            $dataCreate[$defaultData['field_relationship']] = $id;
            $dataOld = DB::table($tableInsert)->where($defaultData['field_relationship'], $id)->first();
            if ($dataOld !== null) {
                DB::table($tableInsert)->update($dataCreate);
            } else {
                DB::table($tableInsert)->insert($dataCreate);
            }
        }
    }

    public function __insertCreateDataMapTable($rs_create, $id, $table)
    {
        $table = $table->table_map;
        if (!empty($rs_create) && count($rs_create) > 0) {
            $data = request()->all();
            $vdetail = VDetailTable::where(['parent_name' => $table, 'name' => array_key_first($rs_create)])->first();
            $defaultData = json_decode($vdetail->default_data, true);
            $dataCreate = [];
            foreach ($data as $key => $value) {
                if (in_array($key, $defaultData['field_duplicate'])) {
                    $dataCreate[$key] = $value;
                }
            }
            $tableInsert = $defaultData['table'];
            $dataCreate[$defaultData['field']] = reset($rs_create);
            $dataCreate[$defaultData['field_relationship']] = $id;
            DB::table($tableInsert)->insert($dataCreate);
        }
    }

    public function _updateOutRefernce($outs, $table, $id)
    {
        foreach ($outs as $k => $out) {
            if (is_array($out)) {
                $map = VDetailTable::where("parent_name", $table)->where("name", $k)->first();
                if ($map != null) {
                    $tableRef = $map->more_note;
                    $tableMap = $table . "_" . $tableRef;
                    if (!\Schema::hasTable($tableMap)) {
                        $tableMap = $tableRef . "_" . $table;
                    }
                    if (\Schema::hasTable($tableMap)) {
                        \DB::table($tableMap)->where(\Str::singular($table) . "_id", $id)->delete();
                        foreach ($out as $o) {
                            \DB::table($tableMap)->insert([\Str::singular($table) . "_id" => $id, \Str::singular($tableRef) . "_id" => $o]);
                        }
                    }
                }
            } else {
                $map = VDetailTable::where("parent_name", $table)->where("name", $k)->first();
                if ($map != null) {
                    $tableRef = $map->more_note;
                    $tableMap = $table . "_" . $tableRef;
                    if (!\Schema::hasTable($tableMap)) {
                        $tableMap = $tableRef . "_" . $table;
                    }
                    if (\Schema::hasTable($tableMap)) {
                        \DB::table($tableMap)->where(\Str::singular($table) . "_id", $id)->delete();
                        \DB::table($tableMap)->insert([\Str::singular($table) . "_id" => $id, \Str::singular($tableRef) . "_id" => $out]);
                    }
                }
            }
        }
    }
}
