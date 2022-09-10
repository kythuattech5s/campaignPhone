<?php 
namespace vanhenry\manager\helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Schema;
use \vanhenry\manager\model\{HUser,HGroupUser};
class RoleHelper
{
	private static function getChildGroupUser($childGroups){
		$ret = [];
		if (count($childGroups)) {
			$listIdParentGroup = [];
			foreach ($childGroups as $itemChild) {
				array_push($ret, $itemChild->id);
				array_push($listIdParentGroup,$itemChild->id);
			}
			$listChildGroup = HGroupUser::whereIn('parent',$listIdParentGroup)->get()->all();
			while (count($listChildGroup) > 0) {
				$listIdParentGroup = [];
			    foreach ($listChildGroup as $item) {
			    	array_push($ret, $item->id);
					array_push($listIdParentGroup,$item->id);
			    }
			    $listChildGroup = HGroupUser::whereIn('parent',$listIdParentGroup)->get()->all();
			}
			return $ret;
		}
		return [];
	}
	public static function getListRecordCanInteractive($table)
	{
		$listTableCheck = \DB::table('h_user_table_checks')->select('table_name')->get()->pluck('table_name')->all();
		$userAdmin = \Auth::guard('h_users')->user();
		if ($userAdmin->group == 1 || !in_array($table,$listTableCheck)) {
			return [false,[]];
		}else{
			$groupUser = HGroupUser::find($userAdmin->group);
			$childGroups = HGroupUser::where('parent',$groupUser->id)->get()->all();
			$arrChildGroupId = static::getChildGroupUser($childGroups);
			$listHUserChildId = HUser::select('id')->whereIn('group',$arrChildGroupId)->get()->pluck('id')->all();
			array_push($listHUserChildId,$userAdmin->id);
			$listIdTarget = \DB::table('h_user_record_maps')->select('target_id')->where('table_name',$table)->whereIn('h_user_id',$listHUserChildId)->get()->pluck('target_id')->all();
			return [true,$listIdTarget];
		}
	}
	public static function checkIdHUserCanInteractive($table,$id)
	{
		list($checkId,$listInteractiveId) = self::getListRecordCanInteractive($table);
		if ($checkId) {
			if (!in_array($id,$listInteractiveId)) {
				return false;
			}
		}
		return true;
	}
	public static function checkHUserDeletePermission($table,$id)
	{
		list($checkId,$listInteractiveId) = self::getListRecordCanInteractive($table);
		if ($checkId) {
			if (is_array($id)) {
				foreach ($id as $itemId) {
					if (!in_array($itemId,$listInteractiveId)) {
						return false;
					}
				}
			}else{
				if (!in_array($id,$listInteractiveId)) {
					return false;
				}
			}
		}
		return true;
	}
}