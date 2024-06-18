<?php

namespace App\Http\Controllers;

use App\Helper\ImageManagerTrait;
use App\Services\Image\ImageService;
use Illuminate\Http\Request;

class CkeditorController extends Controller
{
    use ImageManagerTrait;

    public function upload(Request $request)
    {
        if ($file = $request->file('upload')) {
            $fileData = $this->uploads($file);
            return response()->json([
                'fileName' => $fileData['name'],
                'uploaded' => 1,
                'url' => $fileData['path']
            ]);
        }
    }
}
