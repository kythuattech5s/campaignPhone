<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use WebPConvert\WebPConvert;
use vanhenry\manager\model\Media;

class CronImg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron Image';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        file_put_contents(public_path('cron-img-lock.txt'), 'start', FILE_APPEND);
        $this->convertImg();
    }

    public function convertImg()
    {
        ini_set('memory_limit', -1);
        set_time_limit(0);
        $hLock = fopen("cronimg.lock", "w+");
        if (!flock($hLock, LOCK_EX | LOCK_NB)) {
            die("Already running. Exiting...");
        }
        $imgs = \DB::table('custom_media_images')->where('act', 0)->take(10)->get();
        foreach ($imgs as $key => $item) {
            $media = \DB::table('media')->find($item->media_id);
            if ($media == null) {
                \DB::table('custom_media_images')->where('id', $item->id)->update(['act' => 2]);
                continue;
            }
            $item->name = $media->path . $media->file_name;
            $item->name = str_replace("public/", "", $item->name);
            $fileName = substr($item->name, strrpos($item->name, '/') + 1, strlen($item->name));
            $baseName = substr($fileName, 0, strrpos($fileName, '.'));
            $fileExtension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
            $pathMove = substr($item->name, 0, strrpos($item->name, '/'));
            $pathMove = str_replace("public/", "", $pathMove);
            $pathMove = public_path($pathMove . '/');
            $status = 1;
            if (file_exists($pathMove) && file_exists(public_path($item->name)) && !file_exists($pathMove . $baseName . '.webp') && in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
                try {
                    //resize ảnh gốc trước
                    list($width, $height) = getimagesize($pathMove . $fileName);

                    if ($width > 1080) {
                        $height = 1080 / ($width / $height);
                        $this->resizeImage($pathMove, $baseName, $fileName, 1080, $height,  80, "", $pathMove);
                    }
                    // try {
                    WebPConvert::convert(public_path($item->name), $pathMove . $baseName . '.webp', []);

                    // } catch (\Exception $err) {
                    //     file_put_contents(public_path('cron_img_lock.txt'), $err->getMessage());
                    // }
                    $arrSizes = @$this->getSizes($pathMove . $fileName);
                    $listThumbs = [];
                    if (count($arrSizes) > 0) {
                        foreach ($arrSizes as $size) {
                            $new_image = $this->resizeImage($pathMove, $baseName, $fileName, $size["width"], $size["height"], $size["quality"], $size["name"]);
                            $thumb = $size['width'] . 'x' . $size['height'];
                            $listThumbs[$thumb] = $this->getFileInfo($new_image);
                        }
                    }
                    $itemMedia = Media::find($item->media_id);
                    if ($itemMedia !== null) {
                        $classExtra = json_decode($itemMedia->extra);
                        $classExtra->resizes = $listThumbs;
                        $itemMedia->extra = json_encode($classExtra, JSON_UNESCAPED_UNICODE);
                        $itemMedia->save();
                    }

                    //Cập nhật ảnh liên quan
                    $rowUseFile = $itemMedia->getAllRow;

                    if ($rowUseFile != null && $rowUseFile->count() > 0) {
                        foreach ($rowUseFile as $row) {
                            $type = array_values(array_filter(config('sys_type_show.img'), function ($value, $key) use ($row) {
                                return $value['type_show'] == $row->type_show;
                            }, ARRAY_FILTER_USE_BOTH));
                            if (count($type) > 0) {
                                $type = $type[0]['type'];
                                switch ($type) {
                                    case 'single':
                                        \DB::table($row->map_table)->where('id', $row->map_id)->update([
                                            $row->field => json_encode($itemMedia, JSON_UNESCAPED_UNICODE),
                                        ]);
                                        break;
                                    case 'multiple':
                                        $field = $row->field;
                                        $oldData = \DB::table($row->map_table)->where('id', $row->map_id)->first();
                                        $data = $oldData->$field;
                                        $dataOld = json_decode($data, true);
                                        $newArray = [];
                                        foreach ($dataOld as $itemOld) {
                                            if ($itemOld['id'] == $itemMedia->id) {
                                                $newArray[] = $itemMedia;
                                            } else {
                                                $newArray[] = $itemOld;
                                            }
                                        }
                                        \DB::table($row->map_table)->where('id', $row->map_id)->update([
                                            $field => json_encode($newArray, JSON_UNESCAPED_UNICODE),
                                        ]);
                                        break;
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $status = 2;
                }
            }
            \DB::table('custom_media_images')->where('id', $item->id)->update(['act' => $status]);
        }
        flock($hLock, LOCK_UN);
        fclose($hLock);
        unlink('cronimg.lock');
    }

    private function getFileInfo($file_path)
    {

        $pathinfo = pathinfo($file_path);
        $extimgs = \Config::get("manager.ext_img");
        $extvideos = \Config::get("manager.ext_video");
        $extfiles = \Config::get("manager.ext_file");
        $extmusic = \Config::get("manager.ext_music");
        $extmisc = \Config::get("manager.ext_misc");
        $pathuploads = \Config::get("manager.path_uploads");
        $basepath = \Config::get("manager.base_path");
        $obj = new \stdClass();
        $dataSizeFile = $this->getImageSize($file_path);
        $obj->attr = $dataSizeFile['attr'];
        $obj->extension = $pathinfo['extension'];
        $obj->size = $this->human_filesize(filesize($file_path));
        $obj->date = filemtime($file_path);
        $obj->isfile = is_file($file_path) ? 1 : 0;
        $file_path = substr(str_replace(public_path(), '', $file_path), 1, -1);
        $onlyDir = substr($file_path, 0, strrpos($file_path, "/") + 1);
        $obj->dir = $onlyDir;
        $obj->path = $onlyDir . $pathinfo['basename'];
        $obj->file_name = $pathinfo['basename'];
        if ($obj->isfile) {
            if (in_array($obj->extension, $extimgs)) {
                $obj->width = $dataSizeFile['width'];
                $obj->height = $dataSizeFile['height'];
                if (file_exists($onlyDir . 'thumbs/def/' . $pathinfo['basename'])) {
                    $obj->thumb = $basepath . $onlyDir . 'thumbs/def/' . $pathinfo['basename'];
                } else {
                    $obj->thumb = $basepath . $onlyDir . $pathinfo['basename'];
                }
            } else if (in_array($obj->extension, $extvideos)) {
                if (file_exists("admin/images/ico/" . $obj->extension . ".jpg")) {
                    $obj->thumb = $basepath . "admin/images/ico/" . $obj->extension . ".jpg";
                } else {
                    $obj->thumb = $basepath . "admin/images/ico/file.jpg";
                }
            } else if (in_array($obj->extension, $extfiles)) {
                if (file_exists("admin/images/ico/" . $obj->extension . ".jpg")) {
                    $obj->thumb = $basepath . "admin/images/ico/" . $obj->extension . ".jpg";
                } else {
                    $obj->thumb = $basepath . "admin/images/ico/file.jpg";
                }
            } else if (in_array($obj->extension, $extmusic)) {
                if (file_exists("admin/images/ico/" . $obj->extension . ".jpg")) {
                    $obj->thumb = $basepath . "admin/images/ico/" . $obj->extension . ".jpg";
                } else {
                    $obj->thumb = $basepath . "admin/images/ico/file.jpg";
                }
            } else if (in_array($obj->extension, $extmisc)) {
                if (file_exists("admin/images/ico/" . $obj->extension . ".jpg")) {
                    $obj->thumb = $basepath . "admin/images/ico/" . $obj->extension . ".jpg";
                } else {
                    $obj->thumb = $basepath . "admin/images/ico/file.jpg";
                }
            } else {
                $obj->thumb = $basepath . "admin/images/ico/file.jpg";
            }
        }
        return $obj;
    }

    private function human_filesize($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . (" " . @$sz[$factor] . "B");
    }

    private function getSizes($file)
    {
        if (file_exists($file)) {
            $sizes = \DB::table("v_configs")->select("value")->where("name", "SIZE_IMAGE")->get();

            if ($sizes != null && count($sizes) > 0) {
                $json = $sizes[0]->value;
                $arr = json_decode($json, true);
                $arr = @$arr ? $arr : array();
                $s = getimagesize($file);
                $w = count($s) > 0 ? $s[0] : 1;
                $h = count($s) > 1 ? $s[1] : 1;
                array_push($arr, array("name" => "def", "width" => 100, "height" => (int) ($h * 100 / $w), "quality" => 80));
                return $arr;
            }
        }
        return array();
    }

    private function getImageSize($file)
    {
        list($width, $height, $type, $attr) = getimagesize($file);
        return compact('width', 'height', 'type', 'attr');
    }

    private function resizeImage($upload_path, $baseName, $filename, $widthImage, $heightImage, $quality, $name, $customPath = false)
    {
        $img = \Image::make($upload_path . $filename);
        if ($heightImage <= 0) {
            $img->resize($widthImage, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else if ($widthImage <= 0) {
            $img->resize(null, $heightImage, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $img->resize($widthImage, $heightImage);
        }
        $newpath = $customPath ? $customPath : $upload_path . "thumbs/" . $name . "/";
        if (!is_dir($newpath)) {
            mkdir($newpath, 0777, 1);
        }
        $img->save($newpath . $filename, $quality);
        /*convert image to webp*/
        WebPConvert::convert($newpath . $filename, $newpath . $baseName . '.webp', []);
        return $newpath . $filename;
    }
}
