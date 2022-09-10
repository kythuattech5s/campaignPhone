<?php

namespace CustomTable\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use DateTime;

class CheckController{
    public function checkEditing(Request $request){
        $checkOld = DB::table('h_check_edits')->where('map_table',$request->input('table'))->where('map_id', $request->input('id'))->first();
        if(is_null($checkOld)){
            $data = [
                'map_table' => $request->input('table'),
                'map_id' => $request->input('id'),
                'h_user_id' => Auth::guard('h_users')->id(),
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime()
            ];
            DB::table('h_check_edits')->insert($data);
        }else{
            DB::table('h_check_edits')->where('map_table',$request->input('table'))->where('map_id', $request->input('id'))->update([
                'updated_at' => new \DateTime()
            ]);
        }
        return response(200);
    }
    public function removeEditing(Request $request){
        $dataEditing = DB::table('h_check_edits')->where('map_table',$request->input('table'))->where('map_id', $request->input('id'))->delete();
        return response(200);
    }

    public function checkTimeEdit($config, $table, $id){
        // Lấy thời gian cài đặt + thêm 1s so với thời gian hiện tại
        $timeDelay = 10000;
        $time = ($config[$table]['time'] + $timeDelay)/1000;
        $dataEditing = DB::table('h_check_edits')->where('map_table',$table)->where('map_id',$id)->first();
        
        if($dataEditing !== null){
            $timeUpdating = (new DateTime($dataEditing->updated_at))->modify("+ $time seconds");
            if($timeUpdating < new DateTime()){
                DB::table('h_check_edits')->where('map_table',$table)->where('map_id',$id)->delete();
                return false;
            }else{
                return back()->with('typeNotify','danger')->with('messageNotify','Đang có người chỉnh sửa nội dung này');
            }
        }else{
            return false;
        }
    }

    
}