<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasFavourite extends Model
{
    use HasFactory;

    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id')->where('status',1);
    }
}
