<?php
namespace vanhenry\manager\listeners;

use vanhenry\manager\jobs\OtherTablePodcast;
use vanhenry\manager\jobs\SitemapPodcast;
use vanhenry\manager\model\HHistory;
class ManagerEventListener
{
    public function subscribe($events)
    {
        $events->listen('vanhenry.manager.update_normal.preupdate', PreUpdate::class);
        $events->listen('vanhenry.manager.insert.preinsert', PreUpdate::class);
        $events->listen('vanhenry.manager.delete.predelete', PreDelete::class);
        $events->listen('vanhenry.manager.delete.predelete', function ($table, $id)
        {
            $tbl = $table;
            if ($table instanceof \vanhenry\manager\model\VTable)
            {
                $tbl = $table->name;
            }

            /* Xóa thông tin phân quyền user */
            $arrIdSp = $id;
            $userAdmin = \Auth::guard('h_users')->user();
            if (is_array($arrIdSp)) {
                \DB::table('h_user_record_maps')->where('table_name',$tbl)->where('h_user_id',$userAdmin->id)->whereIn('target_id',$arrIdSp)->delete();
            }else {
                \DB::table('h_user_record_maps')->where('table_name',$tbl)->where('h_user_id',$userAdmin->id)->where('target_id',$arrIdSp)->delete();
            }
            /* End xóa thông tin phân quyền user */

            $idSp = $id;
            $id = is_array($id) ? implode(",", $id) : $id;
            $name = "Delete " . $tbl;
            $content = "Delete " . $tbl . " id = " . $id;

            $dataAdd = [];
            $dataAdd['table_name'] = $tbl;
            $dataAdd['action'] = 'delete';
            if (is_array($idSp)) {
                foreach ($idSp as $itemId) {
                    $dataAdd['target_id'] = $itemId;
                    $this->insertHistory($name,$content,$dataAdd);
                }
            }else {
                $dataAdd['target_id'] = $idSp;
                $this->insertHistory($name,$content,$dataAdd);
            }

            return array(
                "status" => true
            );
        });
        $events->listen('vanhenry.manager.insert.success', function ($table, $data, $id)
        {
            $tbl = $table;
            if ($table instanceof \vanhenry\manager\model\VTable)
            {
                $tbl = $table->name;
            }

            /* Thêm thông tin phân quyền */
            $userAdmin = \Auth::guard('h_users')->user();
            $dataCreate = [];
            $dataCreate['h_user_id']    = $userAdmin->id;
            $dataCreate['table_name']   = $table->table_map;
            $dataCreate['target_id']    = $id;
            $dataCreate['created_at']   = new \DateTime;
            $dataCreate['updated_at']   = new \DateTime;
            \DB::table('h_user_record_maps')->insert($dataCreate);
            /* End thêm thông tin phân quyền */

            $name = "Insert " . $tbl;
            $content = "Insert " . $tbl . " id = " . $id . (isset($data["name"]) ? " name " . $data["name"] : "");
            $dataAdd = [];
            $dataAdd['table_name'] = $table->table_map;
            $dataAdd['action'] = 'insert';
            $dataAdd['target_id'] = $id;
            $this->insertHistory($name, $content,$dataAdd);
        });
        $events->listen('vanhenry.manager.trash.success', function ($table, $id, $value)
        {
            $tbl = $table;
            if ($table instanceof \vanhenry\manager\model\VTable)
            {
                $tbl = $table->name;
            }
            $idSp = $id;
            $id = is_array($id) ? implode(",", $id) : $id;
            $type = $value == 1 ? "trash" : "restore";
            $name = $type . " " . $tbl;
            $content = $type . " " . $tbl . " id = " . $id;

            $dataAdd = [];
            $dataAdd['table_name'] = $tbl;
            $dataAdd['action'] = $type;
            if (is_array($idSp)) {
                foreach ($idSp as $itemId) {
                    $dataAdd['target_id'] = $itemId;
                    $this->insertHistory($name,$content,$dataAdd);
                }
            }else {
                $dataAdd['target_id'] = $idSp;
                $this->insertHistory($name,$content,$dataAdd);
            }
        });
        $events->listen('vanhenry.manager.update_normal.success', function ($table, $data, $id, $oldData = null)
        {
            $tbl = $table;
            if ($table instanceof \vanhenry\manager\model\VTable)
            {
                $tbl = $table->table_map;
            }
            $name = "Update Normal " . $tbl;
            $dataAdd = [];
            $dataAdd['table_name'] = $tbl;
            $dataAdd['action'] = 'update';
            $dataAdd['target_id'] = $id;
            $arrChangeField = [];
            if (isset($oldData)) {
                foreach ($data as $key => $item) {
                    if (isset($oldData->$key) && $oldData->$key != $item) {
                        array_push($arrChangeField, $key);
                    }
                }
                if (count($arrChangeField)) {
                    $dataAdd['field_change'] = implode(',',$arrChangeField);
                }
                $arrNotCheckChange = ['created_at','updated_at','update_by','create_by','yoast_score'];
                foreach ($arrNotCheckChange as $item) {
                    if (($keyD = array_search($item, $arrChangeField)) !== false) {
                        unset($arrChangeField[$keyD]);
                    }
                }
                if (count($arrChangeField)) {
                    $dataAdd['field_change'] = implode(',',$arrChangeField);
                }
            }
            $content = "Update " . $tbl . " id = " . $id . (isset($data["name"]) ? " name " . $data["name"] : "");
            $this->insertHistory($name, $content , $dataAdd);
        });
        $events->listen('vanhenry.manager.update_config.success', function ($table, $data, $id)
        {
            $tbl = $table;
            if ($table instanceof \vanhenry\manager\model\VTable)
            {
                $tbl = $table->name;
            }
            $name = "Update Configs " . $tbl;
            $content = "Update " . $tbl . " id = " . $id;
            $this->insertHistory($name, $content);
        });
        $events->listen('vanhenry.manager.addtoparent.success', function ($table, $parent, $arrId)
        {
            $tbl = $table;
            if ($table instanceof \vanhenry\manager\model\VTable)
            {
                $tbl = $table->name;
            }
            $name = "Remove From Parent " . $tbl;
            $content = "Update " . $tbl . " remove from parent with parent = " . $parent . " id = " . implode(",", $arrId);
            $this->insertHistory($name, $content);
        });
        $events->listen('vanhenry.manager.removefromparent.success', function ($table, $parent, $arrId)
        {
            $tbl = $table;
            if ($table instanceof \vanhenry\manager\model\VTable)
            {
                $tbl = $table->name;
            }
            $name = "Add To Parent " . $tbl;
            $content = "Update " . $tbl . " add to parent with parent = " . $parent . " id = " . implode(",", $arrId);
            $this->insertHistory($name, $content);
        });
        $events->listen('vanhenry.manager.doassign.success', function ($table, $group_user)
        {
            $tbl = $table;
            if ($table instanceof \vanhenry\manager\model\VTable)
            {
                $tbl = $table->name;
            }
            $name = "Assign " . $tbl;
            $content = "Update " . $tbl . " assign group user  = " . $group_user;
            $this->insertHistory($name, $content);
        });
        $events->listen('vanhenry.manager.media.delete.success', function ($fname, $id)
        {
            $name = "Delete Media";
            $content = "Delete Media id = " . $id . " name = " . $fname;
            $this->insertHistory($name, $content);
        });
        $events->listen('vanhenry.manager.media.createdir.success', function ($folder_name, $id)
        {
            $name = "Create Folder Media";
            $content = "Create Folder Media id = " . $id . " folder = " . $folder_name;
            $this->insertHistory($name, $content);
        });
        $events->listen('vanhenry.manager.media.insert.success', function ($name, $id)
        {
            $name = "Upload Image Media";
            $content = "Upload Image Media id = " . $id . " name = " . $name;
            $this->insertHistory($name, $content);
        });
        $events->listen('vanhenry.manager.media.update.success', function ($name, $id)
        {
            $name = "Update Media";
            $content = "Update Media id = " . $id . " name = " . $name;
            $this->insertHistory($name, $content);
        });
        $events->listen('vanhenry.manager.media.convert.img.via.cron', function ($path, $id)
        {
            \DB::table('custom_media_images')->insert([
                'name' => $path,
                'act' => 0,
                'media_id' => $id,
                'created_at' => new \DateTime,
                'updated_at' => new \DateTime,
            ]);
        });
        $events->listen('vanhenry.sitemap.autorendersitemap',SitemapPodcast::class);
        $events->listen('vanhenry.table.creatDataMapTable', OtherTablePodcast::class);
        $events->listen('vanhenry.table.updateDataMapTable', OtherTablePodcast::class);
    }

    private function insertHistory($name, $content,$addData = null)
    {
        $h = new HHistory;
        $h->name = $name;
        $h->content = $content;
        $h->ip = request()->ip();
        $h->username = \Auth::guard("h_users")->user()->name;
        $h->id_user = \Auth::guard("h_users")->id();
        if (isset($addData) && is_array($addData)) {
            foreach ($addData as $key => $item) {
                $h->$key = $item;
            }
            if (isset($addData['table_name']) && isset($addData['target_id'])) {
                $itemTarget = \DB::table($addData['table_name'])->find($addData['target_id']);
                if (isset($itemTarget)) {
                    $h->target_name = isset($itemTarget->name) ? $itemTarget->name:'';
                }
            }
        }
        $h->save();
    }
}
