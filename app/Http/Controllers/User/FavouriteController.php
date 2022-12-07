<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\UserHasFavourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FavouriteController extends Controller
{

    use GeneralTrait;

    public function index(){
        $user = Auth::guard('web')->user();
        $favs = UserHasFavourite::with('course.courseHasCategories.category', 'course.firstVideo')->where('user_id',$user->id)->get();

        if(isset($favs)){
            foreach($favs as $fav){
                $fav->percent = $this->newCoursePercentage($fav->course->id,$user->id);
            }
        }

        return Inertia::render('User/Favorite/FavoriteList',compact('favs'));
    }

    public function addToFav(Request $request){
        $user = Auth::guard('web')->user();
        $type = '';
        $fav = UserHasFavourite::where('user_id',$user->id)->where('course_id',$request->course_id)->first();
        if(isset($fav)){
            $fav->delete();
            $type = 'REMOVED';
        }else{
            $fav = new UserHasFavourite();
            $fav->user_id = $user->id;
            $fav->course_id = $request->course_id;
            $fav->save();
            $type = 'ADDED';
        }

        return response()->json(['course_id'=>$fav->course_id,'type'=>$type],200);
    }

    public function getMyFAvList(){
        $user = Auth::user();
        $favs = UserHasFavourite::where('user_id',$user->id)->pluck('course_id');
        return response()->json(['favs'=>$favs],200);
    }

}
