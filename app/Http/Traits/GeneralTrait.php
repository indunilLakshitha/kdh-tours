<?php

namespace App\Http\Traits;

use App\Models\Course;
use App\Models\UserCourseActivities;
use App\Models\UserReportActivities;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait GeneralTrait
{
    public function coursePercentage($course_id, $user_id)
    {
        $course = Course::where('id', $course_id)->select('total_watchtime', 'has_reports', 'total_report_count', 'id')->first();

        if (isset($course)) {

            $target_watchtime = $course->total_watchtime;
            $target_reports = 0;
            $achieved_report_count = 0;
            if ($course->total_report_count > 0) {
                $target_reports = 1;
                $achieved_report_count = UserReportActivities::where('user_id', $user_id)->where('course_id', $course_id)->where('status', 1)->count();
            }

            $total_watch_time  = UserCourseActivities::where('user_id', $user_id)->where('course_id', $course_id)->sum('total_watch_time');
            if ($target_watchtime > 0) {

                $watchtime_to_one = round($total_watch_time / $target_watchtime, 2);
            } else {
                $watchtime_to_one = 1;
            }

            $percent = (($watchtime_to_one + $achieved_report_count) / ($target_reports + 1)) * 100;
            error_log($total_watch_time);

            if ($achieved_report_count == 0 && $total_watch_time == null) {
                $percent = 0;
            }
            return $percent;
        } else {
            return 0;
        }
    }

    public function newCoursePercentage($course_id, $user_id)
    {

        $totalPercentage = 0;
        $checkCompletedVideo = 0;
        $checkCompletedReport = 0;
        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'authUserCourse.reportActivities.reportActivityDetails'
        )->where('id', $course_id)->first();

        if ($courseDetails) {

            if (($courseDetails->total_vedio_count + $courseDetails->has_reports) == 0) {
                return 0;
            }

            $perTaskPercentage = 100 / ($courseDetails->total_vedio_count + $courseDetails->has_reports);

            $courseActivities = $courseDetails->authUserCourse->courseActivities;

            foreach ($courseActivities as $value) {
                if ($value->status == 0) {
                    $checkCompletedVideo = $checkCompletedVideo + 1;
                    $totalPercentage = $totalPercentage + $perTaskPercentage;
                } else {
                    $totalPercentage = $totalPercentage +
                        (($perTaskPercentage / $value->belongVideo->duration_seconds) * $value->total_watch_time);
                }
            }

            if ($courseDetails->authUserCourse->reportActivities) {
                if ($courseDetails->authUserCourse->reportActivities->status == 1) {
                    $checkCompletedReport = $checkCompletedReport + 1;
                    $totalPercentage = $totalPercentage + $perTaskPercentage;
                }
            }

            if (($checkCompletedReport + $checkCompletedVideo) == ($courseDetails->total_vedio_count + $courseDetails->has_reports)) {
                $totalPercentage = 100;
            }

            if ($totalPercentage > 100) {
                $totalPercentage = 100;
            }
            return round($totalPercentage, 1);
        } else {
            return 0;
        }
    }


    public function getAchivementReportDetails($user_id)
    {
        $achieved_report_count = UserReportActivities::where('user_id', $user_id)->where('status', 1)->count();
        if ($achieved_report_count > 0) {
            return $achieved_report_count;
        } else {
            return 0;
        }
    }
    public function getAchivementVedioDetails($user_id)
    {
        $achieved_video_count = UserCourseActivities::where('user_id', $user_id)->where('status', 0)->count();
        if ($achieved_video_count > 0) {
            return $achieved_video_count;
        } else {
            return 0;
        }
    }
}
