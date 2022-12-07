<?php

use App\Models\ProductKey;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

function generateEmailToken($table){
    $string =\Illuminate\Support\Str::random(30); // better than rand()

    // call the same function if the barcode exists already
    if (tokenExists($table,$string)) {
        return tokenExists($table);
    }

    // otherwise, it's valid and can be used
    return $string;
}

function tokenExists($table,$number) {
    // query the database and return a boolean
    // for instance, it might look like this in Laravel
    return DB::table($table)->where('token',$number)->exists();
}

function generateLicennseKey($table){
    $string =$number = mt_rand(100000000000, 999999999999); // better than rand()

    // call the same function if the barcode exists already
    if (licenseExists($table,$string)) {
        return licenseExists($table);
    }

    // otherwise, it's valid and can be used
    return $string;
}
function licenseExists($table,$number) {
    // query the database and return a boolean
    // for instance, it might look like this in Laravel
    return DB::table($table)->where('product_key',$number)->exists();
}

function uploadImage(Request $request){
    $uuid = \Str::uuid()->toString();

    $file = $request->file('image');
    $name =  $file->getClientOriginalName();
    // $name =  $file->getClientOriginalName().'-'.time() ;
    $filePath = 'images/user/' . $uuid . '/' .  $name;
    Storage::disk('s3')->put($filePath, file_get_contents($file));

    $url = Storage::disk('s3')->url($filePath);

    return $url;
}

function userProductkeyExpiredCheck($userId){
    $productKey=ProductKey::whereUserId($userId)->first();

    if($productKey){
        if(\Carbon\Carbon::parse($productKey->expired_at)->isPast()){
            $productKey->update([
               'status'=>2
            ]);
        }
    }
}
