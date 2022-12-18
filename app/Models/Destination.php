<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

   
    public function imageOne()
    {
        return $this->hasOne(uploadImage::class, 'id', 'img_1')->select('id','url');
    }
   
    public function imageTwo()
    {
        return $this->hasOne(uploadImage::class, 'id', 'img_2')->select('id','url');
    }
   
    public function imageThree()
    {
        return $this->hasOne(uploadImage::class, 'id', 'img_3')->select('id','url');
    }
   
    public function imageFour()
    {
        return $this->hasOne(uploadImage::class, 'id', 'img_4')->select('id','url');
    }
   
    public function imageFive()
    {
        return $this->hasOne(uploadImage::class, 'id', 'img_5')->select('id','url');
    }
}
