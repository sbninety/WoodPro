<?php

namespace App\Helper;

trait ImageManagerTrait
{
    /**
     * @param object $file
     * @param string $path
     * @return array|void
     */
    public function uploads(object $file, string $path = 'public')
    {
        if ($file) {
            $fileName = time() . $file->getClientOriginalName();

            $file->storeAs($path, $fileName);

            $originFileName = $file->getClientOriginalName();
            $fileType = $file->getClientOriginalExtension();
            $filePath = asset(env('APP_URL') . ':' . env('APP_PORT') . '/storage/' . $fileName);

            return [
                'name' => $originFileName,
                'type' => $fileType,
                'path' => $filePath,
                'size' => $this->fileSize($file)
            ];
        }
    }

    /**
     * @param object $file
     * @param int $precision
     * @return mixed|string
     */
    public function fileSize(object $file, int $precision = 2): mixed
    {
        $size = $file->getSize();

        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }

        return $size;
    }
}
