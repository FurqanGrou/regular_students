<?php

namespace App;

use Illuminate\{Support\Facades\Storage, Support\Str};

trait FilesHelper
{
    private $fileName;

    /**
     * @param array $files
     * @param string $location
     * @return array
     */
    protected function uploadMultiFiles(array $files, string $location): array
    {
        $uploadedFiles = [];
        foreach ($files as $key => $file) {
            $uploadedFiles[$key]['url'] = $this->fileUpload($file, $location);
            $uploadedFiles[$key]['name'] = $this->fileName;
        }

        return $uploadedFiles;
    }

    /**
     * @param object|null $file
     * @param string $location
     * @param string|null $disk
     * @param bool $returnFullUrl
     * @return string|null
     */
    protected function fileUpload(?object $file, string $location, ?string $disk = null, bool $returnFullUrl = false): ?string
    {
        if (!is_file($file)) {
            return null;
        }

        $fileOriginalExtension = $file->getClientOriginalExtension();
        $fileUniqueName = $this->uniqueName($fileOriginalExtension);
        $upload = $file->storeAs($location, $fileUniqueName);

        if ($returnFullUrl) {
            return Storage::url($upload);
        }
        return $upload;
    }

    /**
     * @param string $extension
     * @return string
     */
    private function uniqueName(string $extension): string
    {
        return time() . '_' . Str::random(6) . '.' . $extension;
    }
}
