<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class UserReportActivities extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $_table      = 'user_report_activities';
    protected $_primaryKey = 'id';

    protected $_fillable = [
        'course_id',
        'user_id',
        'status',
    ];

    public function reportActivityDetails()
    {
        return $this->hasMany(UserReportActivityDetails::class, 'report_activity_id', 'id')->where('user_id', Auth::user()->id);
    }

    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id')->select('id', 'title');
    }
}
