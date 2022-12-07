<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;   

class AdminUser extends Authenticatable
{
    use HasFactory, SoftDeletes;


    protected $guarded = ['id'];


    protected $hidden = [
        'password', 'remember_token',
       ];

    protected $fillable = [
        'email',
        'username',
        'password'
    ];


    
}
