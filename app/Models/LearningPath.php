<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearningPath extends Model
{
    use HasFactory, SoftDeletes;

    protected  $status = [0 => 'tempory', 1 => 'published'];
    protected  $company_typ = [ '1' => 'ALL', 2 => 'SELECTED'];

    
    public function category()
    {
        return $this->hasOne(LearningPathHasCategory::class, 'learning_path_id', 'id');
    }
}
