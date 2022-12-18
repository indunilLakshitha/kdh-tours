<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageTrait
{
    public function uploadImage($request,$file_name,$path = null,$folder = 'images')
    {
        $image = $request->file($file_name);
        // $path = (is_null($path))?public_path('/images/document/'):$path;
        $file_name = time() . rand() . '.' . $image->extension();
        $uploading_file_name = $folder . '/' . $file_name;
        // // $image->move(public_path($path), $existFormat->introduction_file);
        // $image->move($path, $uploading_file_name);
        // return $uploading_file_name;

        $file = file_get_contents($image);

        $uuid = \Str::uuid()->toString();
        $filePath = 'meta/' . $uuid .'/';
        Storage::disk('local')->put($uploading_file_name, $file);

        $url_thumb = Storage::disk('local')->url($uploading_file_name);

        return ['url'=>$url_thumb,'file'=> $file_name];

        // $this->uploadImage($request, $image_name, config('variables.doc_images_root_path'));
        // config('variables.doc_images_root_url') . $stepMarketFormat->introduction_file;
        
    }
}
