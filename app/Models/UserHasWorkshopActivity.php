<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasWorkshopActivity extends Model
{
    use HasFactory;

    protected  $status = [0 => 'not submitted'];

    public function assignment()
    {
        return $this->hasOne(WorkshopHasAssignment::class, 'id', 'workshop_has_assignments_id');
    }
}
