<?php
namespace vanhenry\manager\controller;
use vanhenry\manager\model\VConfigRegion;
use vanhenry\manager\model\TableProperty;
use DB;
trait ViewTrait{
	public function view($table){
		$tableData = self::__getListTable()[$table];
        $customView = config('sys_view'.'.'.$table.'.view.show');
        if (!is_null($customView)) {
            $class = $customView['class'];
            $method = $customView['method'];
            return (new $class)->$method([$table]);
        }
		$tableDetailData = self::__getListDetailTable($table);
		$type = $tableData->type_show;
		$fnc = 'view'.$type;
        if(!method_exists($this,$fnc)){
            $fnc= 'view_normal';
        }
		return $this->$fnc($table,$tableData,$tableDetailData);
	}
	private function _getTableProperties($table_id){
		$tmp =  TableProperty::cacheTableProperty($table_id);
		foreach ($tmp as $key => $value) {
			$value["is_prop"] = 1;
			$tmp[$key] = (object)$value;
		}
		return $tmp;
	}
	/*
	Lấy thông tin giá trị meta tương ứng với các bản ghi và các trường properties
	**/
	private function _getDataFromTableProperties($table,$detaildata,$listData){
		// $table_meta = $table."_metas";
		// if(Schema::hasTable($table_meta)){
		// 	$detaildata = collect($detaildata);
		// 	$inProp = $detaildata->implode('id', ',');
		// 	$inProp = explode(",", $inProp);
		// 	$inData = $listData->implode("id", ",");
		// 	$inData = explode(",", $inData);
		// 	$arrProp = \DB::table($table_meta)->whereIn("prop_id",$inProp)->whereIn("source_id",$inData)->get();
		// 	$collectProp = collect($arrProp)->groupBy("source_id");
		// 	$tmpListData=$listData->getCollection()->keyBy("id");
		// 	$detaildata = $detaildata->keyBy("id");
		// 	foreach ($collectProp as $kprops => $props) {
		// 		foreach ($props as $kprop => $prop) {
		// 			$_tmp = $prop->meta_key.FCHelper::ep($detaildata->get($prop->prop_id),"name");
		// 			$_out = $tmpListData->get($kprops);
		// 			$_out->$_tmp = $prop->meta_value;
		// 		}
		// 	}
		// }
		return $listData;
	}
	private function _view_trait_getListTrash($table,$data){
		$tableData= $data['tableData'];
		$tableDetailData= $data['tableDetailData'];
		$rpp = $tableData['rpp_admin'];
		$rpp = \vanhenry\helpers\helpers\StringHelper::isNull($rpp)?10:$rpp;
		$query = DB::table($table);
		$fieldSelect = $this->getFieldSelectTable($table,$tableDetailData);
		$query = $query->select($fieldSelect);
		return $query->where("trash",1)->orderBy('id','desc')->paginate($rpp);
	}
	public function trashview($table){
		$tableData = self::__getListTable()[$table];
		$tableDetailData = self::__getListDetailTable($table);
		$data['tableData'] = collect($tableData);
		$tmp = collect($tableDetailData);
		$addDetailData = $this->_getTableProperties($data['tableData']->get("id"));
		$merge = $tmp->merge($addDetailData);
		//Thông tin chi tiết bảng
		$data['tableDetailData'] = $tmp;
		$listData = $this->_view_trait_getListTrash($table,$data);
		$data['tableDetailData'] = $merge;
		$data["listData"] = $this->_getDataFromTableProperties($table,$addDetailData,$listData);
		return view('vh::view.viewtrash',$data);
	}
	public function view_normal($table,$tableData,$tableDetailData){
        //Thông tin bảng
        $data['tableData'] = collect($tableData);
        // Lấy model của bảng
        $model = $data['tableData']['model']; 
        $tmp = collect($tableDetailData);
        $addDetailData = $this->_getTableProperties($data['tableData']->get("id"));
        $merge = $tmp->merge($addDetailData);
        //Thông tin chi tiết bảng
        $data['tableDetailData'] = $tmp;
		if (($customTabs = config('sys_tab'.'.'.$table))) {
            $class = $customTabs['class'];
            $method = $customTabs['method'];
            $listData = (new $class)->$method([$customTabs,$table,$data, $this, $model]);
        }else{
			$listData = $this->getDataTable($model, $data);
		}
        $data['tableDetailData'] = $merge;
		$data["listData"] = $this->_getDataFromTableProperties($table,$addDetailData,$listData);
		$view = \View::exists('vh::view.view'.$tableData->type_show)?'vh::view.view'.$tableData->type_show:'vh::view.view_normal';
		return view($view,$data);
	}

	public function view_user($userid){
		$table="total_orders";
		$tableData = self::__getListTable()[$table];
		$tableDetailData = self::__getListDetailTable($table);
		$type = $tableData->type_show;
		//Thông tin bảng
		$data['tableData'] = collect($tableData);
		$tmp = collect($tableDetailData);
		//Thông tin chi tiết bảng
		$data['tableDetailData'] = $tmp;
		$tableData= $data['tableData'];
		$tableDetailData= $data['tableDetailData'];
		$rpp =$tableData['rpp_admin'];
		$rpp = \vanhenry\helpers\helpers\StringHelper::isNull($rpp)?10:$rpp;
		$query = DB::table($table);
		$query = $query->where("user_id",$userid);
		$listData = $query->orderBy('id','desc')->paginate($rpp);
		$data["listData"] = $listData;
		$data["cuser"] = \App\Models\User::find($userid);
		$view = 'vh::view.view_user';
		return view($view,$data);
	}
	/*#View Config*/
	public function view_config($table,$tableData,$tableDetailData){
		return redirect($this->admincp.'/edit/'.$table."/0");
	}
    
	private function getConfigRegions($table='configs'){
		$regions = VConfigRegion::getConfigRegion($table);
		foreach ($regions as $key => $value) {
            $isParent = false;
			$value->childs = VConfigRegion::getConfigRegion($table,$value,$isParent);
		}
		return $regions;
	}
	/*#View Config*/
	/*#View Menu*/
	public function view_menu($table,$tableData,$tableDetailData){
		$data['tableData'] = collect($tableData);
		$data['groupMenus'] = $this->getGroupMenus($tableData->table_parent);
		$data['menus'] = collect($this->generateMenu($tableData->table_map,0));
		return view('vh::view.view_menu',$data);
	}
	public function view_permis($table,$tableData,$tableDetailData){
		return redirect($this->admincp.'/edit/'.$table."/0");
	}
	private function generateMenu($tableName,$parent){
		$arr = \DB::table($tableName)->where('parent',$parent)->get();
		foreach ($arr as $key => $value) {
			$value -> childs = $this->generateMenu($tableName,$value->id);
		}
		return $arr;
	}
	private function getGroupMenus($nameParent){
		return \DB::table($nameParent)->where('act',1)->get();
	}
	/*#View Menu*/
}
?>
