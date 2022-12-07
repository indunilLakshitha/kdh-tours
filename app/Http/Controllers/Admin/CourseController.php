<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\CourseTrait;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseHasCategory;
use App\Models\CourseHaseKeyword;
use App\Models\CourseHasPostViewing;
use App\Models\CourseHasReport;
use App\Models\CourseTitles;
use App\Models\CourseVediosAndTitle;
use App\Models\User;
use App\Models\Video;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Image;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    use CourseTrait;

    public function __construct(Redirector $redirect)
    {
        // $this->middleware('auth:admin_users');
        // if (Auth::guard('admin_users')) {
        //     $redirect->to(route('admin.login.index'))->send();
        // }
    }


    public function index()
    {
         $courses = Course::with('courseHasCategories.category:id,name')->orderBy('id', 'DESC')->get();
         $courses = $courses->each->setAppends([]); 
        return Inertia::render('Admin/Course/CourseList', compact('courses'));
    }

    public function create()
    {
        $course_categories = CourseCategory::select('id', 'name')->get();
        $videos = Video::where('status', 1)->select('id', 'title', 'summary', 'thumbnail')->get();

        return Inertia::render('Admin/Course/CourseRegister', [
            'course_categories' => $course_categories,
            'videos' => $videos,
        ]);
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'summery' => 'required',
            'categories' => 'required',
            'keywords' => 'required',
            'thumbnail' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:5120',
            // 'reports' => 'required',
            // 'reports.*.*' => 'required',
        ], [
            'name.required' => '講座名が必須項目です。',
            'thumbnail.required' => 'サムネイルは必須項目です。',
            'thumbnail.image' => '画像形式が無効です。',
            'thumbnail.max' => 'サムネイルを5MB以内にしてください。',
            'categories.required' => 'カテゴリーが必須項目です。',
            'keywords.required' => 'キーワードが必須項目です。',
            'summery.required' => '講座概要が必須項目です。',
            // 'reports.*.required' => 'メールアドレスは必須項目です。',
        ]);

        try {


            DB::beginTransaction();



            $course = new Course();

            if ($request->hasFile('thumbnail')) {
                $course->thumbnail = $this->upload($request);
            }

            $course->title = $request->name;
            $course->summery = $request->summery;
            $course->status = $request->status;

            $reports  = 0;
            $vedios  = 0;

            $course->save();

            if ($course->status == 1) {
                $users = User::where('status', 1)->select('id')->get();
                foreach ($users as $user) {
                    $this->assignCourseToUser($user->id, [$course->id], 'SINGLE');
                }
            }

            $title_order = 1;
            $title_count = 1;
            $course_duration = 0;
            //SAVING videoS AND MAJOR TITLES
            if (isset($request->vedio_components)) {
                $title_id = 0;
                foreach ($request->vedio_components as $key => $dt) {

                    // $content->course_id = $course->id;


                    if ($dt['type'] == 'title') {
                        $title = new CourseTitles();
                        $title->course_id = $course->id;
                        $title->content = $dt['value'];
                        $title->order = $title_order;
                        $title_order++;
                        $title->save();

                        $title_id = $title->id;

                        $content = new CourseVediosAndTitle();
                        // $content->vedio_id = NULL;
                        $content->type = 0;

                        $content->pos = $key;
                        $content->course_id = $course->id;
                        $content->content = $title->content;
                        $content->title_id = $title_id;

                        $content->save();

                    } else {
                        $content = new CourseVediosAndTitle();
                        if ($title_count == 1) {
                            $title = new CourseTitles();
                            $title->course_id = $course->id;
                            $title->content = NULL;
                            $title->order = $title_order;
                            $title_order++;
                            $title->save();
                            $title_id = $title->id;

                            $content_first = new CourseVediosAndTitle();
                            $content_first->vedio_id = NULL;
                            $content_first->type = 0;
                            $content_first->course_id = $course->id;
                            $content_first->content = NULL;
                            $content_first->pos = $key;
                            $content_first->save();

                        }


                        $content->vedio_id = $dt['value'];
                        $vedio = Video::where('id', $dt['value'])->first();
                        if (isset($vedio)) {
                            $course_duration = $course_duration + $vedio->duration_seconds;
                        }
                        $vedios += 1;
                        $content->type = 1;

                        $content->pos = $key;
                        $content->course_id = $course->id;
                        $content->title_id = $title_id;

                        $content->save();
                    }

                    $title_count++;
                }
            }


            $course->total_vedio_count = $vedios;
            $course->total_report_count = 0;
            $course->total_watchtime = (int) $course_duration;
            $course->save();



            // SAVING COURSE CATEGORIES
            if (isset($request->categories)) {
                foreach ($request->categories as $key => $cat) {
                    $content = new CourseHasCategory();
                    $content->course_id = $course->id;
                    $content->category_id = $cat;

                    $content->save();
                }
            }
            // SAVING COURSE KEYWORDS
            if (isset($request->keywords)) {
                foreach ($request->keywords as $key => $keyword) {
                    $content = new CourseHaseKeyword();
                    $content->course_id = $course->id;
                    $content->keyword = $keyword;

                    $content->save();
                }
            }

            $course->has_reports = 0;
            // SAVING REPORTS
            if (isset($request->reports)) {
                foreach ($request->reports as $key => $dt) {
                    $course->total_report_count = 1;
                    $content = new CourseHasReport();
                    $content->course_id = $course->id;
                    $content->title =  $dt['title'];
                    $content->description =  $dt['description'];
                    $content->type = 0;
                    if ($dt['type'] !== 'text') {
                        $content->type = 1;
                        $content->document = $this->moveDocument($dt['document'], $course->id, $dt['file_name'], 'reports');
                    }
                    $content->pos = $key;
                    $content->save();
                    $course->has_reports = 1;
                    $course->save();
                }
            }
            // VIEWINGS REPORTS
            if (isset($request->viewings)) {
                foreach ($request->viewings as $key => $dt) {
                    $content = new CourseHasPostViewing();
                    $content->course_id = $course->id;
                    $content->title =  $dt['title'];
                    if ($dt['type'] == 'sheet') {
                        $content->type = 0;
                    } else {

                        $content->type = 1;
                    }

                    $content->document = $this->moveDocument($dt['document'], $course->id, $dt['file_name'], 'viewings');
                    $content->pos = $key;
                    $content->save();
                }
            }
            DB::commit();
            $success = true;
            return Redirect::route('course.index', compact('success'));
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::route('course.create');
        }
        // return response()->json(['succes' => true], 200);
    }

    public function upload(Request $request)
    {

        try {

            $uuid = time();

            $file = $request->file('thumbnail');
            $name =  $file->getClientOriginalName();
            // $name =  $file->getClientOriginalName().'-'.time() ;
            $filePath = 'images/' . $uuid . '/' .  $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file));

            $url = Storage::disk('s3')->url($filePath);

            return $url;
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //'message' => $th->getMessage(),
            ]);
        }
    }
    public function uploadReport($request, $course_id)
    {

        try {

            $uuid = $course_id;
            $file = $request['document'];
            $name =  $file->getClientOriginalName();
            $filePath = 'reports/' . $uuid . '/' .  $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file));

            $url = Storage::disk('s3')->url($filePath);

            return $url;
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //'message' => $th->getMessage(),
            ]);
        }
    }

    public function moveDocument($url, $course_id, $name, $folder)
    {
        try {

            $uuid = $course_id;
            $filePath = 'documents/' . $folder . '/' . $uuid . '/' . $name;
            Storage::disk('s3')->put($filePath, file_get_contents($url));
            // dd($filePath);

            $url_new = Storage::disk('s3')->url($filePath);

            return $url_new;
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //'message' => $th->getMessage(),
            ]);
        }
    }

    public function documentUpolad(Request $request)
    {
        try {

            $uuid = time();
            $file = $request['document'];
            $name =  $file->getClientOriginalName();
            $filePath = 'reports/' . 'tempory/' . $uuid . '/' . 'temp/' . $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file));

            $url = Storage::disk('s3')->url($filePath);

            $data = ['key' => $uuid, 'url' => $url];
            return $data;
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //'message' => $th->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        try {

             $course = Course::with(
                'courseHasCategories:id,course_id,category_id',
                'courseHasCategories.category:id,name',
                'keywords',
                'vediosAndTitles:id,course_id,vedio_id,type,content',
                'postViewings:id,course_id,pos,title,type,document',
                'reports:id,course_id,pos,title,type,document,description'
            )->where('id', $id)->first();
            $course_categories = CourseCategory::select('id', 'name')->get();
            $videos = Video::where('status', 1)->select('id', 'title', 'summary', 'thumbnail')->get();
            $course = $course->setAppends([]); 
            return Inertia::render('Admin/Course/CourseEdit', [
                'course_categories' => $course_categories,
                'videos' => $videos,
                'course' => $course,
            ]);
        } catch (Exception $e) {

            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //'message' => $th->getMessage(),
            ]);
        }
    }

    public function update(Request $request)
    {

        if ($request->old_thumb == 1 && $request->thumbnail == null) {
            $request->validate([
                'name' => 'required',
                'old_thumb' => 'required',
                'summery' => 'required',
                'categories' => 'required',
                'keywords' => 'required',
            ], [
                'name.required' => '講座名が必須項目です。',
                'categories.required' => 'カテゴリーが必須項目です。',
                'keywords.required' => 'キーワードが必須項目です。',
                'summery.required' => '講座概要が必須項目です。',
            ]);
        } else {
            $request->validate([
                'name' => 'required',
                'old_thumb' => 'required',
                'summery' => 'required',
                'categories' => 'required',
                'keywords' => 'required',
                'thumbnail' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:5120',

            ], [
                'name.required' => '講座名が必須項目です。',
                'thumbnail.required' => 'サムネイルは必須項目です。',
                'thumbnail.image' => '画像形式が無効です。',
                'thumbnail.max' => 'サムネイルを5MB以内にしてください。',
                'categories.required' => 'カテゴリーが必須項目です。',
                'keywords.required' => 'キーワードが必須項目です。',
                'summery.required' => '講座概要が必須項目です。',
            ]);
        }
        try {


            DB::beginTransaction();

            $course =  Course::where('id', $request->course_id)->first();

            if ($request->old_thumb == 0) {

                if ($request->hasFile('thumbnail')) {
                    $course->thumbnail = $this->upload($request);
                }
            }

            $course->title = $request->name;
            $course->summery = $request->summery;
            $course->status = $request->status;

            $course->save();


            $reports  = 0;
            $vedios  = 0;

            if ($course->status == 1) {
                $users = User::where('status', 1)->select('id')->get();
                foreach ($users as $user) {
                    $this->assignCourseToUser($user->id, [$course->id], 'SINGLE');
                }
            }

            



            $title_order = 1;
            $title_count = 1;
            $course_duration = 0;
            //SAVING videoS AND MAJOR TITLES
            if (isset($request->vedio_components)) {

                CourseVediosAndTitle::where('course_id', $course->id)->delete();
                CourseTitles::where('course_id', $course->id)->delete();

                $title_id = 0;
                foreach ($request->vedio_components as $key => $dt) {

                    // $content->course_id = $course->id;


                    if ($dt['type'] == 'title') {
                        $title = new CourseTitles();
                        $title->course_id = $course->id;
                        $title->content = $dt['value'];
                        $title->order = $title_order;
                        $title_order++;
                        $title->save();

                        $title_id = $title->id;

                        $content = new CourseVediosAndTitle();
                        // $content->vedio_id = NULL;
                        $content->type = 0;

                        $content->pos = $key;
                        $content->course_id = $course->id;
                        $content->content = $title->content;
                        $content->title_id = $title_id;

                        $content->save();

                    } else {
                        $content = new CourseVediosAndTitle();
                        if ($title_count == 1) {
                            $title = new CourseTitles();
                            $title->course_id = $course->id;
                            $title->content = $dt['value'];
                            $title->order = $title_order;
                            $title_order++;
                            $title->save();
                            $title_id = $title->id;

                            $content_first = new CourseVediosAndTitle();
                            $content_first->vedio_id = NULL;
                            $content_first->type = 0;
                            $content_first->course_id = $course->id;
                            $content_first->content = NULL;
                            $content_first->pos = $key;
                            $content_first->save();

                        }


                        $content->vedio_id = $dt['value'];
                        $vedio = Video::where('id', $dt['value'])->first();
                        if (isset($vedio)) {
                            $course_duration = $course_duration + $vedio->duration_seconds;
                        }
                        $vedios += 1;
                        $content->type = 1;

                        $content->pos = $key;
                        $content->course_id = $course->id;
                        $content->title_id = $title_id;

                        $content->save();
                    }

                    $title_count++;
                }
            }


            $course->total_vedio_count = $vedios;
            $course->total_report_count = 0;
            $course->total_watchtime = (int) $course_duration;
            $course->save();



            // SAVING COURSE CATEGORIES
            if (isset($request->categories)) {
                CourseHasCategory::where('course_id', $course->id)->delete();
                // return ;
                foreach ($request->categories as $key => $cat) {
                    $content = new CourseHasCategory();
                    $content->course_id = $course->id;
                    $content->category_id = $cat;
                    // if (!CourseHasCategory::where('course_id', $course->id)->where('category_id', $cat)->first()) {

                    $content->save();
                    // }
                }
            }





            // SAVING COURSE KEYWORDS
            if (isset($request->keywords)) {
                CourseHaseKeyword::where('course_id', $course->id)->delete();
                foreach ($request->keywords as $key => $keyword) {
                    $content = new CourseHaseKeyword();
                    $content->course_id = $course->id;
                    $content->keyword = $keyword;
                    // if (!CourseHaseKeyword::where('course_id', $course->id)->where('keyword', $keyword)->first()) {
                    $content->save();
                    // }
                }
            }

            $course->has_reports = 0;
            // SAVING REPORTS
            if (isset($request->reports)) {
                CourseHasReport::where('course_id', $course->id)->delete();
                foreach ($request->reports as $key => $dt) {
                    $course->total_report_count = 1;
                    $content = new CourseHasReport();
                    $content->course_id = $course->id;
                    $content->title =  $dt['title'];
                    $content->description =  $dt['description'];
                    $content->type = 0;
                    if ($dt['type'] !== 'text') {
                        $content->type = 1;
                        $content->document = $this->moveDocument($dt['document'], $course->id, $dt['file_name'], 'reports');
                    }
                    $content->pos = $key;
                    $content->save();
                    $course->has_reports = 1;
                    $reports += 1;
                }
            }

            $course->total_vedio_count = $vedios;
            $course->save();

            // VIEWINGS REPORTS
            if (isset($request->viewings)) {
                CourseHasPostViewing::where('course_id', $course->id)->delete();
                foreach ($request->viewings as $key => $dt) {


                    $content = new CourseHasPostViewing();
                    $content->course_id = $course->id;
                    $content->title =  $dt['title'];
                    if ($dt['type'] == 'sheet') {
                        $content->type = 0;
                    } else {

                        $content->type = 1;
                    }

                    $content->document = $this->moveDocument($dt['document'], $course->id, $dt['file_name'], 'viewings');

                    $content->pos = $key;
                    $content->save();
                    // $course->has_reports = 1;
                    // $course->save();

                }
            }
            DB::commit();

            $success = true;

            return Redirect::route('course.index', compact('success'));
        } catch (Exception $e) {
            DB::rollBack();
            $success = false;
            return Redirect::route('course.edit', compact('success'));
        }
    }
}
