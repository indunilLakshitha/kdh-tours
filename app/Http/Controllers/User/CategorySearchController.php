<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\UserHasFavourite;
use Exception;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;

class CategorySearchController extends Controller
{

    use GeneralTrait;

    public function index()
    {

        try {
            $user = Auth::guard('web')->user();
            $categories  = CourseCategory::all();
            $courses = CourseCategory::with(
                'courses.keywords',
                'courses.courseActivities:id,course_id,total_watch_time',
                'courses.firstVideo',
                'courses.courseHasCategories:id,course_id,category_id',
                'courses.courseHasCategories.category:id,name',
            )->get();
            if (isset($courses)) {

                foreach ($courses as $course) {
                    foreach ($course->courses as $c) {
                        $c->percentage = $this->newCoursePercentage($c->id, 0);
                    }
                }
            }
            $favs = UserHasFavourite::where('user_id', $user->id)->pluck('course_id');


            return Inertia::render('User/Category/Category', compact('categories', 'courses', 'favs'));
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //'message' => $th->getMessage(),
            ]);
        }
    }

    public function search($id)
    {
        try {
            $categories  = CourseCategory::all();
            $type = '';

            $user = Auth::guard('web')->user();
            $favs = UserHasFavourite::where('user_id', $user->id)->pluck('course_id');
            if ($id != 'all') {
                $type = 'single';
                $courses = CourseCategory::with(
                    'courses.keywords',
                    'courses.courseHasCategories.category:id,name',
                    'courses.courseActivities:id,course_id,total_watch_time',
                    'courses.firstVideo'
                )->where('id', $id)->first();
                if (isset($courses)) {

                    foreach ($courses->courses as $course) {
                        $course->percentage = $this->newCoursePercentage($course->id, 0);
                    }
                }
            } else {
                $courses = Course::with('courseHasCategories.category:id,name', 'courseActivities:id,course_id,total_watch_time', 'firstVideo')->where('status', 1)->get();
                $type = 'all';
                if (isset($courses)) {

                    foreach ($courses as $course) {
                        $course->percentage = $this->newCoursePercentage($course->id, 0);
                    }
                }
            }
            return Inertia::render('User/Category/CategoryView', compact('courses', 'categories', 'type', 'favs'));
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //'message' => $th->getMessage(),
            ]);
        }
    }

    public function searchByKeyword($keyword)
    {
        try {
            $categories  = CourseCategory::all();

            $courses = Course::with('keywords', 'courseHasCategories.category:id,name', 'firstVideo')->where('status', 1);

            $courses = $courses->whereHas('keywords', function ($q) use ($keyword) {
                $q->where('keyword', 'like', '%' . $keyword . '%');
            });

            $courses = $courses->get();
            $type = 'all';
            $favs = UserHasFavourite::where('user_id',Auth::user()->id)->pluck('course_id');
            return Inertia::render('User/Category/CategoryView', compact('courses', 'categories', 'type','favs'));
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //'message' => $th->getMessage(),
            ]);
        }
    }
}
