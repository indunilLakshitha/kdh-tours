<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'videos';
    Protected $primaryKey = 'id';

    protected $fillable = [
        'summary',
        'title',
        'vedio',
        'status',
        'thumbnail',
        'duration_seconds'
    ];
}
