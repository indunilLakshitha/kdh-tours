<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseVediosAndTitle extends Model
{
    use HasFactory;
    protected $table = 'course_vedios_and_titles';
    Protected $primaryKey = 'id';

    protected $fillable = [
        'pos',
        'course_id',
        'type',
        'vedio_id',
        'content',
        'title_id',


    ];

    public function belongVideo()
    {
        return $this->hasOne(Video::class, 'id', 'vedio_id')->select('id','title','duration_seconds', 'videos');
    }
}
