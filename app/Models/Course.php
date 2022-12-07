<?php

namespace App\Models;

use App\Http\Traits\CourseTrait;
use App\Http\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    use HasFactory, GeneralTrait, SoftDeletes;

    protected $appends = [
        'userwatchtime','watchedPercentage'
    ];


    public function getWatchedPercentageAttribute()
    {
        return $this->newCoursePercentage($this->id, Auth::guard('web')->user()->id);
    }

    public function keywords()
    {
        return $this->hasMany(CourseHaseKeyword::class, 'course_id', 'id');
    }
    public function vediosAndTitles()
    {
        return $this->hasMany(CourseVediosAndTitle::class, 'course_id', 'id');
    }
    public function postViewings()
    {
        return $this->hasMany(CourseHasPostViewing::class, 'course_id', 'id');
    }
    public function reports()
    {
        return $this->hasMany(CourseHasReport::class, 'course_id', 'id');
    }

    public function courseHasCategories()
    {
        return $this->hasMany(CourseHasCategory::class, 'course_id', 'id');
    }

    public function courseTitles()
    {
        return $this->hasMany(CourseTitles::class, 'course_id', 'id');
    }

    public function authUserCourse()
    {
        return $this->hasOne(UserHasCourse::class, 'course_id', 'id')->where('user_id', Auth::user()->id);
    }

    public function courseVideosOnly()
    {
        return $this->hasMany(CourseVediosAndTitle::class, 'course_id', 'id')->where('type', 1);
    }

    public function courseActivities()
    {
        return $this->hasOne(UserCourseActivities::class, 'course_id', 'id')->where('user_id', Auth::guard('web')->user()->id);
    }

    public function getUserWatchtimeAttribute()
    {
        return $this->hasOne(UserCourseActivities::class, 'course_id', 'id')->where('user_id', Auth::user()->id)->sum('user_course_activities.total_watch_time');
    }

    public function firstVideo()
    {
        return $this->hasOne(CourseVediosAndTitle::class, 'course_id', 'id')->where('type', 1);
    }

}
