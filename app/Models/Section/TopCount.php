<?php

namespace App\Models\Section;

use App\Models\UploadImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopCount extends Model
{
    use HasFactory;

    /**
     * Get the user associated with the TopCount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function file()
    {
        return $this->hasOne(UploadImage::class, 'id', 'image')->select('url','id');
    }
}
