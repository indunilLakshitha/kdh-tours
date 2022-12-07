<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCompanyDetails extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'industry_id',
        'prefecture_id',
        'tp_no',
        'post_code',
        'address_line_1',
        'address_line_2',
        'building_name',
    ];


}
