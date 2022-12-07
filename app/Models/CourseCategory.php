<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    use HasFactory;

    /**
     * Get all of the comments for the CourseCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courses()
    {
        return $this->hasManyThrough(Course::class, CourseHasCategory::class,'category_id','id','id','course_id')->where('status',1);
    }

    

    
}
