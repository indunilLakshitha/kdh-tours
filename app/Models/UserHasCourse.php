<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserHasCourse extends Model
{
    use HasFactory;
    

    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id')->where('status',1);
    }

    public function courseActivities()
    {
        return $this->hasMany(UserCourseActivities::class, 'course_id', 'course_id')->where('user_id', Auth::user()->id);
    }

    public function reportActivities()
    {
        return $this->hasOne(UserReportActivities::class, 'course_id', 'course_id')->where('user_id', Auth::user()->id);
    }
}
