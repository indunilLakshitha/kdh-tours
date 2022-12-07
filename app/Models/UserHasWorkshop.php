<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasWorkshop extends Model
{
    use HasFactory;

    protected  $status = [0 => 'hope to apply', 1 => 'applied'];

    
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
