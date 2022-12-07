<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserReportActivityDetails extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $_table      = 'user_report_activity_details';
    protected $_primaryKey = 'id';

    protected $_fillable = [
        'report_activity_id',
        'course_id',
        'user_id',
        'course_report_id',
        'pos',
        'type',
        'content',
        'document'
    ];
}
