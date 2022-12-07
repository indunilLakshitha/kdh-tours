<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCourseActivities extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $_table      = 'user_course_activities';
    protected $_primaryKey = 'id';

    protected $_fillable = [
        'course_id',
        'user_id',
        'video_id',
        'total_watch_time',
        'status',
    ];

    public function belongVideo()
    {
        return $this->hasOne(Video::class, 'id', 'video_id')->select('id', 'duration_seconds');
    }
    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id')->select('id', 'title');
    }
}
