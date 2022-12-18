<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ImageTrait;
use App\Models\UploadImage;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    use ImageTrait;

    public function upload(Request $request)
    {
        $res = $this->uploadImage($request, 'image');

        $image = new UploadImage();
        $image->file = $res['file'];
        $url = env('APP_URL').'/storage/app/images/'.$image->file;
        $image->url = $url;
        // $image->url = config('variables.doc_images_root_path') . $url;
        $image->save();
        

        // return response()->json(['url' => '/'.'app'.$image->url, 'file' => $image->file, 'image_id' => $image->id]);
        return response()->json(['url' => $url, 'file' => $image->file, 'image_id' => $image->id]);
    }
}
