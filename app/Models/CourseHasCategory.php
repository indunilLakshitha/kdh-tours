<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseHasCategory extends Model
{
    use HasFactory;

    /**
     * Get the user associated with the CourseHasCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->hasOne(CourseCategory::class, 'id', 'category_id');
    }

    /**
     * Get the user associated with the CourseHasCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }
}
