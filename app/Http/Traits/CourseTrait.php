<?php

namespace App\Http\Traits;

use App\Models\Course;
use App\Models\UserHasCourse;

trait CourseTrait
{
    public function assignCourseToUser($user_id, $course_ids = [], $type = 'ALL')
    {
        if ($type == 'ALL') {
            $courses = Course::where('status', 1)->get()->pluck('id');
            foreach ($courses as $course) {

                $user_has_course = new UserHasCourse();
                $user_has_course->user_id = $user_id;
                $user_has_course->course_id = $course;
                $exist = UserHasCourse::where('user_id', $user_id)->where('course_id', $course)->select('id')->first();
                if (!$exist) {

                    $user_has_course->save();
                }
            }
        } else {
            foreach ($course_ids as $course) {

                $user_has_course = new UserHasCourse();
                $user_has_course->user_id = $user_id;
                $user_has_course->course_id = $course;
                $exist = UserHasCourse::where('user_id', $user_id)->where('course_id', $course)->select('id')->first();
                if (!$exist) {

                    $user_has_course->save();
                }
            }
        }
    }
}
