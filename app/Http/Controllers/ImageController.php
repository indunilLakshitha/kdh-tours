<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    // Simple run composer require league/flysystem-aws-s3-v3
    public function upload(Request $request)
    {

        // dd($request->all());

        $uuid = \Str::uuid()->toString();

        $file = $request->file('image');
        $name =  $file->getClientOriginalName();
        // $name =  $file->getClientOriginalName().'-'.time() ;
        $filePath = 'images/' . $request->type . '/' . $uuid . '/' .  $name;
        Storage::disk('s3')->put($filePath, file_get_contents($file));

        $url = Storage::disk('s3')->url($filePath);


        return response()->json([
            'url' => $url,
        ]);
    }
}
