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
        $url = $this->uploadImage($request, 'image', config('variables.doc_images_root_path'));

        $image = new UploadImage();
        $image->file = $url;
        $image->url = env('APP_URL').'/storage/app/public/images/document/' . $url;
        // $image->url = config('variables.doc_images_root_path') . $url;
        $image->save();

        return response()->json(['url' => $image->url, 'image_id' => $image->id]);
    }
}
