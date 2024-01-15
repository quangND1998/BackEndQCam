<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait FileUploadTrait
{

    public function makeFolder($path)
    {
        if (!Storage::directoryExists($path)) {
            Storage::disk('public')->makeDirectory($path, 0777);
        }
    }

    public function uploadImage($file, $savePath)
    {
        $path = Storage::disk('public')->putFile($savePath, $file);
        return  $path;
    }

    public function updateImage($file, $savePath, $oldPath)
    {
        $path =  $this->uploadImage($file, $savePath);
        return $path;
    }

    public function deleteFile($path)
    {
        if ($path &&  Storage::disk('public')->exists($path)) {

            Storage::disk('public')->delete($path);
        }
    }

    public function deleteFolder($path)
    {
        if ($path && is_dir(public_path() . $path)) {
            File::deleteDirectory(public_path() . $path); //xoa dc file nay

        }
    }

    public function updateFile($oldPath, $content)
    {
        if ($oldPath && File::exists(public_path() . $oldPath) && $content) {
            file_put_contents(public_path() . $oldPath, $content);
        }
    }
}
