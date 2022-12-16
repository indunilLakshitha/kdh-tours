<?php

namespace App\Http\Traits;

trait ImageTrait
{
    public function uploadImage($request,$file_name,$path = null)
    {
        $image = $request->file($file_name);
        $path = (is_null($path))?public_path('/images/document/'):$path;
        $uploading_file_name = time() . rand() . '.' . $image->extension();
        // $image->move(public_path($path), $existFormat->introduction_file);
        $image->move($path, $uploading_file_name);
        return $uploading_file_name;


        // $this->uploadImage($request, $image_name, config('variables.doc_images_root_path'));
        // config('variables.doc_images_root_url') . $stepMarketFormat->introduction_file;
    }
}
