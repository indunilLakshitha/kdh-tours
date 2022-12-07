<?php

namespace App\Http\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Validation\ValidationException;

trait ImageTrait
{
    public function uploadThumbnail(Request $request,$folder_name)
    {
        if ($request->hasFile('thumbnail')) {
            $url = $this->upload($request,$folder_name);
            return response()->json(['url' => $url, 'success' => true], 200);
        } else {
            return response()->json(['url' => "", 'success' => false], 200);
        }
    }
    public function upload(Request $request, $folder_name)
    {

        try {
            $uuid = time();

            $file = $request->file('thumbnail');
            $name =  $file->getClientOriginalName();
            $filePath = 'images/' . $folder_name . '/' . $uuid . '/' .  $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file));

            $url = Storage::disk('s3')->url($filePath);

            return $url;
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //'message' => $th->getMessage(),
            ]);
        }
    }
    public function uploadReport($request, $course_id)
    {

        $uuid = $course_id;
        $file = $request['document'];
        $name =  $file->getClientOriginalName();
        $filePath = 'reports/' . $uuid . '/' .  $name;
        Storage::disk('s3')->put($filePath, file_get_contents($file));

        $url = Storage::disk('s3')->url($filePath);

        return $url;
    }

    public function moveDocument($url, $course_id, $name, $folder)
    {
        $uuid = $course_id;
        $filePath = 'documents/' . $folder . '/' . $uuid . '/' . $name;
        Storage::disk('s3')->put($filePath, file_get_contents($url));

        $url_new = Storage::disk('s3')->url($filePath);

        return $url_new;
    }

    public function documentUpolad(Request $request)
    {
        $uuid = time();
        $file = $request['document'];
        $name =  $file->getClientOriginalName();
        $filePath = 'reports/' . 'tempory/' . $uuid . '/' . 'temp/' . $name;
        Storage::disk('s3')->put($filePath, file_get_contents($file));

        $url = Storage::disk('s3')->url($filePath);

        $data = ['key' => $uuid, 'url' => $url];
        return $data;
    }
}
