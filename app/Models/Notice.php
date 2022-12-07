<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get all of the categories for the Notice
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(NoticeHasCategory::class, 'notice_id', 'id');
    }


    public function user()
    {
        return $this->hasOne(AdminUser::class, 'id', 'created_by');
    }
}
