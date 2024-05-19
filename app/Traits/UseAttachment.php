<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait UseAttachment
{
    public function uploadFile($file, $path, $defaultName = null)
    {
        $fileName = $defaultName;

        if (! $file->isValid()) {
            return $fileName;
        }

        $fileName = time().'.'.$file->getClientOriginalExtension();

        Storage::disk('public')->putFileAs($path, $file, $fileName);

        return $fileName;
    }

    public function updateFile($newFile, $oldFile, $path, $defaultName = null)
    {
        if ($newFile == $oldFile || ! $newFile->isValid()) {
            return $oldFile;
        }

        $fileName = time().'.'.$newFile->getClientOriginalExtension();

        Storage::disk('public')->putFileAs($path, $newFile, $fileName);

        if ($oldFile && $oldFile != $defaultName) {
            Storage::disk('public')->delete($path.'/'.$oldFile);
        }

        return $fileName;
    }
}
