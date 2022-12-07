<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductKey extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded=[];

    protected  $statusList = [1 => 'Active', 0 => 'Not-Active', 2 => 'Expired',3=>'Force-Stopped',4=>'Suspend-HRM'];

    public function getStatusAttribute($status)
    {
        return $this->statusList[$status];
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
