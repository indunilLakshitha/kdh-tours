<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeHasCategory extends Model
{
    use HasFactory;

    protected $table ='notice_has_categories';

    public function category()
    {
        return $this->hasOne(NoticeCategory::class, 'id', 'category_id');
    }
}
