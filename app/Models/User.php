<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nick_name',
        'name_in_furigana',
        'company_id',
        'industry_id',
        'occupation',
        'position',
        'dob',
        'user_type',
        'status',
        'username',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    protected  $statusList = [1 => 'Active', 0 => 'Disabled', 2 => 'Not-Registered'];
    protected  $userType = [1 => 'Training Manager', 2 => 'Employee'];

    public function getStatusAttribute($status)
    {
        return $this->statusList[$status];
    }

    protected function userCompany()
    {
        return $this->hasOne(UserCompanyDetails::class, 'id', 'company_id');
    }

    protected function position()
    {
        return $this->hasOne(Position::class, 'id', 'position');
    }

    public function company()
    {
        return $this->hasOne(UserCompanyDetails::class, 'id', 'company_id');
    }
    public function userCourses()
    {
        return $this->hasMany(UserHasCourse::class, 'user_id', 'id');
    }
    public function userCoursesLimited()
    {
        return $this->hasMany(UserHasCourse::class, 'user_id', 'id')->where('progress_status','!=',0);
    }


    public function occupation()
    {
        return $this->hasOne(Occupation::class, 'id', 'occupation');
    }


    public function courseActivities()
    {
        return $this->hasMany(UserCourseActivities::class, 'user_id', 'id');
    }

    public function reportActivities()
    {
        return $this->hasMany(UserReportActivities::class, 'user_id', 'id');
    }

    public function courseActivityDetails()
    {
        return $this->hasMany(UserReportActivityDetails::class, 'user_id', 'id');
    }

    protected function productKey()
    {
        return $this->hasOne(ProductKey::class, 'user_id', 'id');
    }
}
