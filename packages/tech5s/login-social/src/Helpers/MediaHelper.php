<?php

namespace Tech5s\LoginSocial\Helpers;

use Tech5s\LoginSocial\Models\Media;
use ZipArchive;

class MediaHelper
{
    const ROOT = 'uploads/';

    /**
     * @param string $folder 'san-pham' => upload to folder 'upload/san-pham'
     * @param string $url 'https://www.google.com/logo.png'
     */
    public static function insertFileFromUrl($folder, $url, $checkFileExist = false)
    {
        try {
            $fileContent = file_get_contents($url);
        } catch (\Exception $e) {
            $fileContent = null;
        }
        if (is_null($fileContent)) {
            return false;
        }

        return self::insertFileFromBase64($folder, $fileContent, $url, $checkFileExist);
    }

    public static function insertFileFromBase64($folder, $base64, $name, $checkFileExist = false)
    {
        $name = mb_strtolower($name, 'UTF-8');
        $info = pathinfo($name);
        $fileExtension = isset($info['extension']) ? explode('?', $info['extension'])[0] : 'png';
        $filename = $info['filename'];
        $filePathAbsolute = self::ROOT . $folder;
        $parent = self::getOrSetParent($filePathAbsolute);
        $folder = $parent->path . $parent->file_name . '/';
        $media = self::findMedia($name, $parent->id);
        if ($checkFileExist && file_exists(public_path($folder . $name)) && $media != null) {
            return $media;
        }
        $fileName = self::generatorFileName($filename, $folder, $fileExtension);
        $fileFullName = $fileName . '.' . $fileExtension;
        $fileFullPath = $filePathAbsolute . '/' . $fileFullName;
        file_put_contents(public_path($fileFullPath), $base64);
        $media = self::insertMedia($fileFullPath, $filePathAbsolute, $fileFullName, $parent->id);
        self::createDataCron($media);
        return $media;
    }
    /**
     * @param string $name 'test.jpg'
     * @param string $folder 'uploads/'
     * @param string $fileExtentions 'jpg'
     * @return string
     */
    public static function generatorFileName($name, $folder, $fileExtension)
    {
        $fileName = \Str::slug($name);

        $checkSeparator = explode('-', $fileName);
        $checkHasNumberFile = is_numeric($checkSeparator[count($checkSeparator) - 1]) ? count($checkSeparator) - 1 : null;
        if (is_numeric($checkHasNumberFile)) {
            unset($checkSeparator[$checkHasNumberFile]);
        }
        $fileName = implode('-', $checkSeparator);
        $fileNameNew = $fileName;
        $fullName = $folder . $fileNameNew . '.' . $fileExtension;
        $number = 1;
        while (file_exists(public_path($fullName))) {
            $fileNameNew = $fileName . '-' . $number;
            $fullName = $folder . $fileNameNew . '.' . $fileExtension;
            $number++;
        }
        return $fileNameNew;
    }

    /**
     * @param string $filePathAbsolute 'uploads/test'
     */
    public static function getOrSetParent($filePathAbsolute)
    {
        $info = pathinfo(public_path($filePathAbsolute));
        $dir = $info['dirname'];
        $folder = $info['filename'];
        $parent = Media::where('name', $folder)->where('path', $dir . '/')->where('is_file', 0)->first();
        if ($parent == null) {
            $dir =  ($pos = strpos($filePathAbsolute, self::ROOT)) !== false ? substr_replace($filePathAbsolute, '', $pos, strlen(self::ROOT)) : $filePathAbsolute;
            $folders = explode('/', $dir);
            $parentId = 0;
            $dir = self::ROOT;
            foreach ($folders as $folder) {
                $parent = Media::where('path', $dir)->where('file_name', $folder)->where('is_file', 0)->first();
                if ($parent !== null) {
                    self::createFolderServe($parent->path . $parent->name);
                    $parentId = $parent->id;
                    $dir = $dir . $folder . '/';
                } else {
                    $parent = self::createFolder($folder, $parentId);
                    $parentId = $parent->id;
                }
            }
            return $parent;
        } else {
            return $parent;
        }
    }

    /**
     * @param string $fileFullPath 'uploads/test.jpg'
     * @param string $filePathAbsolute 'uploads'
     * @param string $fileFullName 'test.jpg'
     * @param int $parent '0'
     * @return \Tech5s\LoginSocial\Models\Media
     */
    public static function insertMedia($fileFullPath, $filePathAbsolute, $fileFullName, $parent)
    {
        $m = new Media;
        $m->name = $fileFullName;
        $m->file_name = $fileFullName;
        $m->is_file = 1;
        $m->parent = $parent;
        $m->path = $filePathAbsolute . '/';
        $m->extra = json_encode(self::getInfoFile($fileFullName, $fileFullPath));
        $m->title = $m->name;
        $m->caption = $m->name;
        $m->alt = $m->name;
        $m->description = $m->name;
        $m->trash = 0;
        $m->save();
        return $m;
    }

    public static function getInfoFile($fileFullName, $fileFullPath)
    {
        $extimgs = config("manager.ext_img");
        $extvideos = config("manager.ext_video");
        $extfiles = config("manager.ext_file");
        $extmusic = config("manager.ext_music");
        $extmisc = config("manager.ext_misc");
        $publicPath = config("manager.public_path");
        $obj = new \stdClass();
        $obj->extension = mb_strtolower(substr(strrchr($fileFullName, '.'), 1), "UTF-8");
        $obj->size = self::human_filesize(filesize(public_path($fileFullPath)));
        $obj->date = filemtime(public_path($fileFullPath));
        $obj->isfile = is_file(public_path($fileFullPath)) ? 1 : 0;
        $onlyDir = substr($fileFullPath, 0, strrpos($fileFullPath, "/") + 1);
        $obj->dir = $onlyDir;
        $obj->path = $onlyDir;
        $obj->file_name = $fileFullName;
        if ($obj->isfile) {
            if (in_array($obj->extension, $extimgs)) {
                try {
                    $imagedetails = getimagesize(public_path($fileFullPath));
                    $obj->width = $imagedetails[0];
                    $obj->height = $imagedetails[1];
                } catch (\Exception $err) {
                    dd($fileFullPath);
                }

                if (file_exists(public_path($onlyDir . 'thumbs/def/' . $fileFullName))) {
                    $obj->thumb = $onlyDir . 'thumbs/def/' . $fileFullName;
                } else {
                    $obj->thumb = $onlyDir . $fileFullName;
                }
            } else if (in_array($obj->extension, $extvideos)) {
                if (file_exists(public_path($publicPath . "admin/images/ico/" . $obj->extension . ".jpg"))) {
                    $obj->thumb = $publicPath . "admin/images/ico/" . $obj->extension . ".jpg";
                } else {
                    $obj->thumb = $publicPath . "admin/images/ico/file.jpg";
                }
            } else if (in_array($obj->extension, $extfiles)) {
                if (file_exists(public_path($publicPath . "admin/images/ico/" . $obj->extension . ".jpg"))) {
                    $obj->thumb = $publicPath . "admin/images/ico/" . $obj->extension . ".jpg";
                } else {
                    $obj->thumb = $publicPath . "admin/images/ico/file.jpg";
                }
            } else if (in_array($obj->extension, $extmusic)) {
                if (file_exists(public_path($publicPath . "admin/images/ico/" . $obj->extension . ".jpg"))) {
                    $obj->thumb = $publicPath . "admin/images/ico/" . $obj->extension . ".jpg";
                } else {
                    $obj->thumb = $publicPath . "admin/images/ico/file.jpg";
                }
            } else if (in_array($obj->extension, $extmisc)) {
                if (file_exists(public_path($publicPath . "admin/images/ico/" . $obj->extension . ".jpg"))) {
                    $obj->thumb = $publicPath . "admin/images/ico/" . $obj->extension . ".jpg";
                } else {
                    $obj->thumb = $publicPath . "admin/images/ico/file.jpg";
                }
            } else {
                $obj->thumb = $publicPath . "admin/images/ico/file.jpg";
            }
        }
        return $obj;
    }

    public static function findMedia($fileFullName, $parent)
    {
        $media = Media::where('name', $fileFullName)->where('parent', $parent)->first();
        if ($media) {
            return $media;
        }
        return null;
    }

    /**
     * @param string $name - 'test' => Tên file zip tải xuống
     * @param array  $files => Mảng files ['path','name']
     * @param string $folderDownload => folder tạm để lựu file cần tải xuông sau khi tải sẽ xóa
     */

    public static function downLoadMultipleFileToZip(string $name, array $files, $folderDownload = 'zip-download')
    {
        if (!is_array($files)) {
            return [
                'code' => 100,
                'message' => 'Không phải nhiều file',
            ];
        }
        $filePath = "$name.zip";
        $folderDownload = public_path($folderDownload);

        if (!is_dir($folderDownload)) {
            mkdir($folderDownload, 0777, true);
        }

        $fullPath = $folderDownload . '/' . $filePath;
        if (!file_exists($fullPath)) {
            file_put_contents($fullPath, '');
        }

        try {
            $zip = new ZipArchive;
            $zip->open($fullPath, ZipArchive::CREATE);
            foreach ($files as $img) {
                if (file_exists(public_path($img['path']))) {
                    $zip->addFile(public_path($img['path']), $img['name']);
                }
            }
            $zip->close();
            ob_clean();
            ob_end_flush();
            $filename = basename($fullPath);
            header("Content-type: application/zip");
            header("Content-Length: " . filesize($fullPath));
            header('Content-Disposition: attachment; filename=' . $filename);
            readfile($fullPath);
            unlink($fullPath);
            rmdir($folderDownload);
        } catch (\Exception $err) {
            return [
                'code' => 100,
                'message' => 'File không tồn tại hoặc không đúng dạng mảng files',
            ];
        }
    }

    public static function uploadZipToMultipleFile(string $nameInputFile, string $folder, $getIds = false)
    {
        if (!request()->hasFile($nameInputFile)) {
            return [
                'code' => 100,
                'message' => 'Không tồn tại file này!',
            ];
        }
        $file = request()->file($nameInputFile);
        $name = $file->getClientOriginalName();
        $code = pathinfo($name, PATHINFO_FILENAME);
        $path = $file->getPathname();
        $zip = new ZipArchive;
        $zip->open($path, ZipArchive::RDONLY);
        $entries = $zip->count();
        $arrayMedia = [];
        $parentId = 0;
        for ($i = 0; $i < $entries; $i++) {
            if ($i == 0) {
                $folder = self::ROOT . $folder . '/' . $code;
                $parent = self::getOrSetParent($folder);
                $parentId = $parent->id;
            }
            $start = $zip->statIndex($i);
            $infoByName = pathinfo($start['name']);
            $extention = $infoByName['extension'];
            $name = $infoByName['filename'];
            $content = $zip->getFromIndex($i);
            $nameNotExtention = self::generatorFileName($name, $folder . '/', $extention);
            $fullName = $nameNotExtention . '.' . $extention;
            $fullPathName = $folder . '/' . $fullName;
            $file = file_put_contents(public_path($fullPathName), $content);
            $media = self::insertMedia($fullPathName, $folder, $fullName, $parentId);
            self::createDataCron($media);
            if ($getIds) {
                $arrayMedia[] = $media->id;
            } else {
                $arrayMedia[] = $media;
            }
        }
        $zip->close();

        return $arrayMedia;
    }

    /**
     * @param string $inputName 'image'
     * @param string $saveFrom 'test'
     * @param boolean $checkFileExist
     * @return \Roniejisa\Custom\Models\Media
     */
    public static function uploadFile($inputName, $saveFrom, $checkFileExist = false)
    {
        if (!request()->hasFile($inputName)) {
            return '';
        }

        $file = request()->file($inputName);
        if (is_array($file)) {
            return 'Vui lòng sử dụng method uploadMultiple';
        }

        $folder = self::ROOT . $saveFrom;
        $parent = self::getOrSetParent($folder);
        $parentId = $parent->id;

        $media = self::insertUpload($file, $folder, $parentId, $checkFileExist);
        return $media;
    }

    /**
     * @param string $inputName 'image'
     * @param string $saveFrom 'test'
     * @param boolean $checkFileExist
     * @return array Roniejisa\Custom\Model
     */
    public static function uploadMultiple($inputName, $saveFrom, $checkFileExist = false)
    {
        if (!request()->hasFile($inputName)) {
            return null;
        }
        $medias = [];
        $files = request()->file($inputName);
        if (!is_array($files) || count($files) == 0) {
            return [
                'code' => 100,
                'message' => 'Tệp không phải dạng mảng hoặc không có file nào',
            ];
        }
        $folder = self::ROOT . $saveFrom;
        $parent = self::getOrSetParent($folder);
        $parentId = $parent->id;
        foreach ($files as $key => $file) {
            if ($file == null) {
                continue;
            }
            $medias[] = self::insertUpload($file, $folder, $parentId, $checkFileExist);
        }
        return $medias;
    }

    public static function insertUpload($file, $folder, $parentId, $checkFileExist)
    {
        $name = $file->getClientOriginalName();
        $pathInfo = pathinfo($name);
        $fileName = $pathInfo['filename'];
        $fileName = mb_strtolower($fileName, 'UTF-8');
        $fileExtension = $pathInfo['extension'];
        $pathFileOrigin = $folder . '/' . $name;
        if (($checkFileExist && !file_exists(public_path($pathFileOrigin))) || !$checkFileExist) {
            $fileNameNotExtention = self::generatorFileName($fileName, $folder . '/', $fileExtension);
            $fullName = $fileNameNotExtention . '.' . $fileExtension;
            $fileFullPath = $folder . '/' . $fullName;
            $file->move(public_path($folder), $fullName);
            $media = self::insertMedia($fileFullPath, $folder, $fullName, $parentId);
            self::createDataCron($media);
        } else {
            $media = self::findMedia($name, $parentId);
        }
        return $media;
    }

    private static function createDataCron($media)
    {
        \DB::table('custom_media_images')->insert([
            'name' => $media->path . $media->file_name,
            'media_id' => $media->id,
            'act' => 0,
        ]);
    }
    private static function createFolderServe($fullPath)
    {
        if (!file_exists(public_path($fullPath))) {
            mkdir(public_path($fullPath), 0755, true);
        }
    }

    private static function createFolder($name, $parentId = 0)
    {
        $path = self::ROOT;

        if ($parentId != 0) {
            $parentMedia = Media::find($parentId);
            $path = $parentMedia->path . $parentMedia->name . '/';
        }

        $fullPath = $path . $name;
        self::createFolderServe($fullPath);
        $parent = new Media;
        $parent->name = $name;
        $parent->file_name = $name;
        $parent->is_file = 0;
        $parent->parent = $parentId;
        $parent->path = $path;
        $parent->file_name = $name;
        $extra['extension'] = '';
        $extra['size'] = self::human_filesize(filesize(public_path($path . $name)));
        $extra['date'] = filemtime(public_path($path . $name));
        $extra['isfile'] = 0;
        $extra['dir'] = self::ROOT;
        $extra['path'] = $path;
        $parent->extra = json_encode($extra);
        $parent->save();
        return $parent;
    }

    private static function human_filesize($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . (" " . @$sz[$factor] . "B");
    }
}
