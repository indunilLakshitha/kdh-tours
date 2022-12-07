<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ProductKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CourseHistoryController extends Controller
{
    public function index()
    {
        $auth_user = Auth::guard('web')->user();
        $auth_id = $auth_user->id;
        if ($auth_user->user_type == 1) {
            $company_users_ids = User::where('company_id', $auth_user->company_id)->pluck('id');

            $product_keys = ProductKey::with(
                'user:id,name',
                'user.reportActivities.course',
                'user.userCoursesLimited.course:id,thumbnail,total_watchtime,title'
            )
                ->whereIn('user_id', $company_users_ids)
                ->select('id', 'user_id', 'product_key', 'expired_at', 'created_at')->get();
        } else {
            $other_users = [];
            $product_keys = ProductKey::with(
                'user:id,name',
                'user.reportActivities.course',
                'user.userCoursesLimited.course:id,thumbnail,total_watchtime,title'
            )
                ->where('user_id', $auth_user->id)
                ->select('id', 'user_id', 'product_key', 'expired_at', 'created_at')->get();
        }

        return Inertia::render('HR/Course/CourseHistory', compact('product_keys'));
    }
}
