<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\ProductKey;
use App\Models\User;
use App\Models\UserCourseActivities;
use App\Models\UserHasCourse;
use App\Models\UserReportActivities;
use App\Models\UserReportActivityDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MyPageController extends Controller
{

    use GeneralTrait;

    public function index(Request $request)
    {
        $auth_user = Auth::guard('web')->user();
        $auth_id = $auth_user->id;

        $user = User::leftjoin('occupations', 'occupations.id', 'users.occupation')
            ->leftjoin('user_company_details', 'user_company_details.id', 'users.company_id')
            ->leftjoin('positions', 'positions.id', 'users.position')
            ->leftjoin('product_keys', 'product_keys.user_id', 'users.id')
            ->leftjoin('user_course_activities', 'user_course_activities.user_id', 'users.id')
            ->select(
                'users.id',
                'users.user_type',
                'users.company_id as user_company_id',
                'positions.id as pos_id',
                'user_company_details.id as company_id',
                'users.name',
                'users.username',
                'users.name_in_furigana as name_in_furi',
                'users.email',
                'users.avatar',
                'users.nick_name',
                'user_company_details.name as company_name',
                'positions.name as position',
                'product_keys.expired_at as contract_end',
                'product_keys.created_at as contract_start',
                DB::raw("COUNT(user_course_activities.video_id) as total_views")
            )
            // ->where('user_course_activities.status', 0)
            ->where('users.id', $auth_id)->first();

        $user->achived_reports = $this->getAchivementReportDetails($user->id);
        $user->achived_videos = $this->getAchivementVedioDetails($user->id);


        $courses = UserHasCourse::with(
            'course:id,total_watchtime,total_vedio_count,total_report_count',
        )->whereHas('course')
            ->where('user_id', $user->id)->get();

        $total_vedio_count = 0;
        $total_report_count = 0;

        // counting total vedios and reports
        if (isset($courses)) {
            foreach ($courses as $course) {
                $total_vedio_count += $course->course->total_vedio_count;
                if ($course->course->total_report_count > 0) {
                    $total_report_count += 1;
                }
                // $total_report_count += $course->course->total_report_count;
            }
        }
        $achieved_report_count = UserReportActivities::where('user_id', $user->id)->count();
        $achieved_report_count = $achieved_report_count ? $achieved_report_count : 0;
        $target_v = 0;
        $target_r = 0;
        if ($user->user_type == 1) {
            $other_users = User::with(
                'userCourses:id,user_id,course_id',
                'userCourses.course:id,total_watchtime,total_vedio_count,total_report_count',
            )
                // ->leftJoin('user_course_activities', 'user_course_activities.user_id', 'users.id')
                ->select('users.id', 'users.company_id', 'users.name')
                // ->select('users.id', 'users.company_id', 'users.name', DB::raw("COUNT(user_course_activities.video_id) as total_views"))
                ->where('users.company_id', $user->company_id)
                ->where('users.status', 1)
                ->get();

            $other_user_ids = [];

            if (isset($other_users)) {
                foreach ($other_users as $u) {
                    $u->achived_reports = $this->getAchivementReportDetails($u->id);
                    $u->achived_videos = $this->getAchivementVedioDetails($u->id);
                    array_push($other_user_ids, $u->id);    //getting other use id's

                    if (sizeof($u->userCourses) != 0) {
                        foreach ($u->userCourses as $user_course) {
                            if (isset($user_course->course)) {

                                $u->target_vedio_count += $user_course->course->total_vedio_count;
                                $u->target_report_count += $user_course->course->total_report_count;
                                $target_v = $u->target_vedio_count;
                                $target_r = $u->target_report_count;
                            } else {
                                $u->target_vedio_count += 0;
                                $u->target_report_count += 0;
                                $target_v = $u->target_vedio_count;
                                $target_r = $u->target_report_count;
                            }
                        }
                    } else {
                        $u->target_vedio_count = 0;
                        $u->target_report_count = 0;
                    }
                }
            }

            $company_users_ids = User::where('company_id', $auth_user->company_id)->pluck('id');

            $product_keys = ProductKey::with('user:id,name', 'user.reportActivities.course', 'user.userCoursesLimited.course')
                ->whereIn('user_id', $company_users_ids)
                ->select('id', 'user_id', 'product_key', 'expired_at', 'created_at')
                ->orderBy('updated_at', 'DESC')
                ->limit(6)
                ->get();
            // $product_keys = ProductKey::with('user:id,name')
            //     ->whereIn('user_id', $other_user_ids)
            //     ->select('id', 'user_id', 'product_key', 'expired_at','created_at')->get();
        } else {
            $other_users = [];
            $product_keys = ProductKey::with('user:id,name', 'user.reportActivities.course', 'user.userCoursesLimited.course')
                ->where('user_id', $user->id)
                ->select('id', 'user_id', 'product_key', 'expired_at', 'created_at')->get();
        }

        $total_watch_time  = UserCourseActivities::where('user_id', $auth_id)->sum('total_watch_time');
        $total_watch_time = round($total_watch_time / (60 * 60), 2);
        $total_views = $user->total_views;

        $contract_end = $user->contract_end;
        $contract_start = $user->contract_start;


        return Inertia::render('HR/MyPage/Index', with([
            'user' => $user,
            'total_vedio_count' => $total_vedio_count,
            'contract_end' => $contract_end,
            'total_views' => $total_views,
            'total_watch_time' => $total_watch_time,
            'contract_start' => $contract_start,
            'total_report_count' => $total_report_count,
            'achieved_report_count' => $achieved_report_count,
            'other_users' => $other_users,
            'product_keys' => $product_keys,
            'target_v' => $target_v,
            'target_r' => $target_r,
            'message' => $request->message,
            'alertStatus' => 'visible'
        ]));
    }
}
