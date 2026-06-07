<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\StorageHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function image(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $path = StorageHelper::putFile('uploads/content', $request->file('image'));

        return response()->json([
            'url' => StorageHelper::url($path),
        ]);
    }
}
