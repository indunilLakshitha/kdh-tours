<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopHasCourse extends Model
{
    use HasFactory;

    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }

    public function workshopCourse()
    {
        return $this->hasOne(CourseForWorkshop::class, 'id', 'course_id');
    }
}
