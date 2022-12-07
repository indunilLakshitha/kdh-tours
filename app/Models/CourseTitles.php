<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseTitles extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'course_titles';
    Protected $primaryKey = 'id';


    protected $fillable = [
        'course_id',
        'content',
        'order',
        'status',
    ];

    public function belongVideos()
    {
        return $this->hasMany(CourseVediosAndTitle::class, 'title_id','id')->where('type', 1);
    }
}
