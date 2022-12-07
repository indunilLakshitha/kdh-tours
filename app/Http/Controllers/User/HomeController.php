<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Course;
use App\Models\Notice;
use App\Models\User;
use App\Models\UserCourseActivities;
use App\Models\UserHasCourse;
use App\Models\UserHasFavourite;
use App\Models\UserReportActivities;
use App\Models\UserReportActivityDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session as FacadesSession;

class HomeController extends Controller
{
    use GeneralTrait;

    public function index()
    {

        $user = Auth::guard('web')->user();
        $notices = Notice::with(
            'categories:id,category_id,notice_id',
            'categories.category:id,category',
        )
            ->where('status', 2)
            ->orderBy('id', 'DESC')->take(4)
            ->get();

        $courses = UserHasCourse::with(
            'course:id,title,thumbnail,total_watchtime,total_vedio_count,total_report_count',
            'course.courseHasCategories:id,course_id,category_id',
            'course.courseHasCategories.category:id,name',
            'course.courseActivities:id,course_id,total_watch_time',
            'course.firstVideo'
        )
            ->whereHas('course')
            ->where('user_id', $user->id)->orderBy('id', 'DESC')->get();

        $courses_watching = UserHasCourse::with(
            'course:id,title,thumbnail,total_watchtime,total_vedio_count,total_report_count',
            'course.courseHasCategories:id,course_id,category_id',
            'course.courseHasCategories.category:id,name',
            'course.courseActivities:id,course_id,total_watch_time',
            'course.firstVideo'
        )
            ->whereHas('course.courseActivities')
            ->where('user_id', $user->id)->orderBy('id', 'DESC')->take(4)->get();

        $total_vedio_count = 0;
        $total_report_count = 0;

        $user = User::leftjoin('user_course_activities', 'user_course_activities.user_id', 'users.id')
            ->select(
                'users.id',
                'users.name',
                DB::raw("COUNT(user_course_activities.video_id) as total_views")
            )
            // ->where('user_course_activities.status', 0)
            ->where('users.id', $user->id)->first();



        $total_watch_time  = UserCourseActivities::where('user_id', $user->id)->sum('total_watch_time');
        $total_watch_time = round($total_watch_time / (60 * 60), 2);
        $total_views = $this->getAchivementVedioDetails($user->id);


        $achieved_report_count = $this->getAchivementReportDetails($user->id);
        $achieved_report_count = $achieved_report_count ? $achieved_report_count : 0;

        // counting total vedios and reports
        if (isset($courses)) {
            foreach ($courses as $course) {
                $total_vedio_count += $course->course->total_vedio_count;
                if($course->course->total_report_count > 0){
                    $total_report_count += 1;
                }
                // $total_report_count += $course->course->total_report_count;

                $course->course->percentage = $this->newCoursePercentage($course->course->id, $user->id);

            }
            // return $courses;
        }
        if (isset($courses_watching)) {
            foreach ($courses_watching as $course) {
                $course->percentage = $this->newCoursePercentage($course->course_id, $user->id);
            }
        }
        $courses = $courses->take(4);
        // return $course;

         $favs = UserHasFavourite::where('user_id',$user->id)->pluck('course_id');

        return Inertia::render('User/Home/Home', [
            'user' => $user,
            'notices' => $notices,
            'courses' => $courses,
            'total_vedio_count' => $total_vedio_count,
            'total_watch_time' => $total_watch_time,
            'total_views' => $total_views,
            'achieved_report_count' => $achieved_report_count,
            'total_report_count' => $total_report_count,
            'courses_watching' => $courses_watching,
            'favs' => $favs,
        ]);
    }

    public function logout()
    {

        FacadesSession::flush();
        Auth::guard('web')->logout();

        return redirect()->route('hr.login');
    }
}
