<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Workshop extends Model
{
    use HasFactory;

    protected  $reception_status = [1 => '受付中', 0=> 'not 受付中'];
    protected  $status = [0 => 'tempory', 1 => 'published'];
    protected  $venues = [0=>'',1 => 'オンライン(zoom)',2 => '会場']; // 1 = offline 2= online
    

    protected $appends = ['venue_d'];

    public function getVenueDAttribute()
    {
        if($this->venue == 0){
            return  '';
        }
        if($this->venue == 1){
            return  'オンライン(zoom)';
        }
        if($this->venue == 2){
            return  '会場';
        }
    }

    public function categories()
    {
        return $this->hasMany(WorkshopHasCategory::class, 'workshop_id', 'id');
    }
    public function courses()
    {
        return $this->hasMany(WorkshopHasCourse::class, 'workshop_id', 'id');
    }

    public function assignments()
    {
        return $this->hasMany(WorkshopHasAssignment::class, 'workshop_id', 'id');
    }

    public function reports()
    {
        return $this->hasMany(WorkshopHasReport::class, 'workshop_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany(WorkshopHasDocument::class, 'workshop_id', 'id');
    }

    public function userCount()
    {
        return $this->hasOne(UserHasWorkshop::class, 'workshop_id', 'id')->where('status',1);
    }

    public function currentUser()
    {
        return $this->hasOne(UserHasWorkshop::class, 'workshop_id', 'id')->where('user_id',Auth::user()->id);
    }
    
    public function workshopActivity()
    {
        return $this->hasMany(UserHasWorkshopActivity::class, 'workshop_id', 'id')->where('user_id',Auth::user()->id);
    }
}
