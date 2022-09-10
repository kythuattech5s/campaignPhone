<?php
namespace vanhenry\manager\controller;

use Illuminate\Http\Request;
use Excel;
use DB;
use vanhenry\manager\helpers\ExportView;

trait ExportTrait{
	public function exportExcel(Request $request, $table){
        $fieldList = DB::table('v_detail_tables')->where('parent_name', $table)->where('has_export_excel',1)->get();
        if($fieldList->count() == 0){
            return back()->with('typeNotify','danger')->with('messageNotify','Không có trường nào được export');
        }
        $data = DB::table($table)->select($fieldList->pluck('name')->toArray())->get();
        $note = $fieldList->pluck('note')->toArray();
        $fields = $fieldList->pluck('name')->toArray();

        $data = Excel::download(new ExportView($data, $fields, $note), $table.'.xlsx');
        ob_end_clean();
        return $data;
    }
}