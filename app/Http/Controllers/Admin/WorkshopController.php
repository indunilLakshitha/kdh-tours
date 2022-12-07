<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseForWorkshop;
use App\Models\UserHasWorkshop;
use App\Models\UserHasWorkshopActivity;
use App\Models\Workshop;
use App\Models\WorkshopCategory;
use App\Models\WorkshopHasAssignment;
use App\Models\WorkshopHasCategory;
use App\Models\WorkshopHasCourse;
use App\Models\WorkshopHasDocument;
use App\Models\WorkshopHasReport;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Support\Facades\Redirect;

class WorkshopController extends Controller
{
    public function index()
    {
        $workshops = Workshop::select([])->withCount('userCount')
            ->addSelect('id', 'name', 'summery', 'thumbnail', 'capacity', 'reception_status', 'status')->get();
        return Inertia::render('Admin/Workshop/WorkshopList', compact('workshops'));
    }

    public function participantList($id)
    {
        $participants = UserHasWorkshop::with(
            'user:id,name,email,company_id,name_in_furigana,user_type',
            'user.company:id,name'
        )
            ->where('workshop_id', $id)
            ->where('status', 1)
            ->get();
        return Inertia::render('Admin/Workshop/ParticipantList', compact('participants'));
    }

    public function create()
    {
        $courses = Course::where('status', 1)->select('id', 'title', 'summery', 'thumbnail')->get();
        $courses = $courses->each->setAppends([]);
        $categories = WorkshopCategory::all();

        return Inertia::render('Admin/Workshop/WorkshopCreate', compact('courses', 'categories'));
    }

    public function store(Request $request)
    {


        // dd($request->all());
        // $request->validate([
        //     'name' => 'required',
        //     'summery' => 'required',
        //     // 'categories' => 'required',
        //     // 'keywords' => 'required',
        //     // 'thumbnail' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:5120',

        // ], [
        //     'name.required' => '講座名が必須項目です。',
        //     'thumbnail.required' => 'サムネイルは必須項目です。',
        //     'thumbnail.image' => '画像形式が無効です。',
        //     'thumbnail.max' => 'サムネイルを5MB以内にしてください。',
        //     'categories.required' => 'カテゴリーが必須項目です。',
        //     'keywords.required' => 'キーワードが必須項目です。',
        //     'summery.required' => '講座概要が必須項目です。',
        //     // 'reports.*.required' => 'メールアドレスは必須項目です。',
        // ]);

        // try {
        DB::beginTransaction();
        $workshop = new Workshop();
        $workshop->name   = $request->name;
        $workshop->summery  = $request->summery;
        $workshop->thumbnail  = $request->thumbnail;
        $workshop->detail_description  = $request->detail_description;
        $workshop->opening_date  = $request->opening_date;
        $workshop->opening_time  = $request->opening_time;
        $workshop->closing_date  = $request->closing_date;
        $workshop->closing_time  = $request->closing_time;
        $workshop->venue  = $request->venue;
        $workshop->place_url  = $request->place_url;
        $workshop->place_suppliment  = $request->place_suppliment;
        $workshop->capacity  = $request->capacity;
        $workshop->instructor_profile  = $request->instructor_profile;
        $workshop->status  = $request->status;
        $workshop->reception_status  = $request->reception_status;

        $workshop->save();




        if (isset($request->assignments)) {
            foreach ($request->assignments as $report_detail) {

                $report = new WorkshopHasAssignment();
                $report->title = $report_detail['title'];
                $report->workshop_id = $workshop->id;
                $report->file_name = $report_detail['file_name'] ? $report_detail['file_name'] : NULL;
                $report->description = $report_detail['description'];
                if ($report_detail['type'] == 'document') {
                    $report->url = $report_detail['url'];
                    $report->type = 2;
                } else {
                    $report->type = 1;
                }
                $report->save();
            }
        }

        if (isset($request->reports)) {
            foreach ($request->reports as $report) {

                $wReport = new WorkshopHasReport();
                $wReport->title = $report['title'];
                $wReport->file_name = $report['file_name'] ? $report['file_name'] : NULL;
                $wReport->workshop_id = $workshop->id;
                $wReport->description = $report['description'];
                $wReport->url = $report['url'];

                $wReport->save();
            }
        }

        if (isset($request->documents)) {
            foreach ($request->documents as $document) {

                $wDocument = new WorkshopHasDocument();
                $wDocument->file_name = $document['file_name'] ? $document['file_name'] : NULL;
                $wDocument->title = $document['title'];
                $wDocument->workshop_id = $workshop->id;
                $wDocument->url = $document['url'];
                if ($document['type'] == 'document') {
                    $wDocument->type = 1;
                } else {
                    $wDocument->type = 2;
                }
                $wDocument->save();
            }
        }

        // SAVING WORKSHOP CATEGORIES
        if (isset($request->categories)) {
            foreach ($request->categories as $key => $cat) {
                $content = new WorkshopHasCategory();
                $content->workshop_id = $workshop->id;
                $content->category_id = $cat;

                $content->save();
            }
        }
        if (isset($request->courses)) {
            foreach ($request->courses as $key => $course) {
                $content = new WorkshopHasCourse();
                $content->workshop_id = $workshop->id;
                $content->course_id = $course;

                $content->save();
            }
        }
        $success = true;
        DB::commit();
        return Redirect::route('admin.workshop.index', compact('success'));
        // } catch (Exception $e) {
        // }
    }

    public function edit($id)
    {
        $workshop = Workshop::with(
            'categories.category',
            'courses.workshopCourse:id,title,summery,thumbnail',
            'assignments',
            'reports',
            'documents',
        )->where('id', $id)->first();
        if (!$workshop) {
            return abort(404);
        }

        $user_admited = UserHasWorkshop::where('workshop_id', $workshop->id)->count();

        if ($workshop->opening_date < Carbon::now() && $user_admited > 0) {
            $workshop->blocked = true;
        } else {
            $workshop->blocked = false;
        }

        $courses = Course::where('status', 1)->select('id', 'title', 'summery', 'thumbnail')->get();
        $courses = $courses->each->setAppends([]);
        $categories = WorkshopCategory::all();
        return Inertia::render('Admin/Workshop/WorkshopEdit', compact('workshop', 'id', 'courses', 'categories'));
    }

    public function update(Request $request)
    {

        // try {
        DB::beginTransaction();
        $workshop =  Workshop::where('id', $request->id)->first();

        if (!isset($workshop)) {
            return abort(404);
        }
        $workshop->name   = $request->name;
        $workshop->summery  = $request->summery;
        $workshop->thumbnail  = $request->thumbnail;
        $workshop->detail_description  = $request->detail_description;
        $workshop->opening_date  = $request->opening_date;
        $workshop->opening_time  = $request->opening_time;
        $workshop->closing_date  = $request->closing_date;
        $workshop->closing_time  = $request->closing_time;
        $workshop->venue  = $request->venue;
        $workshop->place_url  = $request->place_url;
        $workshop->place_suppliment  = $request->place_suppliment;
        $workshop->capacity  = $request->capacity;
        $workshop->instructor_profile  = $request->instructor_profile;
        $workshop->status  = $request->status;
        $workshop->reception_status  = $request->reception_status;

        $workshop->save();

        if (sizeof($request->removed_assignments) > 0)
            WorkshopHasAssignment::whereIn('id', $request->removed_assignments)->delete();
            // UserHasWorkshopActivity::whereIn('workshop_has_assignments_id',$request->removed_assignments)->delete();

        if (isset($request->assignments)) {
            foreach ($request->assignments as $report_detail) {

                if ($report_detail['exist']) {
                    $report =  WorkshopHasAssignment::where('id', $report_detail['assignment_id'])->first();
                } else {
                    $report = new WorkshopHasAssignment();
                }
                $report->title = $report_detail['title'];
                $report->workshop_id = $workshop->id;

                $report->file_name = $report_detail['file_name'] ? $report_detail['file_name'] : NULL;

                $report->description = $report_detail['description'];
                if ($report_detail['type'] == 'document') {
                    $report->url = $report_detail['url'];
                    $report->type = 2;
                } else {
                    $report->type = 1;
                }
                $report->save();
            }
        }


        if (isset($request->reports)) {
            WorkshopHasReport::where('workshop_id', $workshop->id)->delete();
            foreach ($request->reports as $report) {

                $wReport = new WorkshopHasReport();
                $wReport->title = $report['title'];
                $wReport->file_name = $report['file_name'] ? $report['file_name'] : NULL;
                $wReport->workshop_id = $workshop->id;
                $wReport->description = $report['description'];
                $wReport->url = $report['url'];

                $wReport->save();
            }
        }

        if (isset($request->documents)) {
            WorkshopHasDocument::where('workshop_id', $workshop->id)->delete();
            foreach ($request->documents as $document) {

                $wDocument = new WorkshopHasDocument();
                $wDocument->file_name = $document['file_name'] ? $document['file_name'] : NULL;
                $wDocument->title = $document['title'];
                $wDocument->workshop_id = $workshop->id;
                $wDocument->url = $document['url'];
                if ($document['type'] == 'document') {
                    $wDocument->type = 1;
                } else {
                    $wDocument->type = 2;
                }
                $wDocument->save();
            }
        }

        // SAVING WORKSHOP CATEGORIES
        if (isset($request->categories)) {
            WorkshopHasCategory::where('workshop_id', $workshop->id)->delete();
            foreach ($request->categories as $key => $cat) {
                $content = new WorkshopHasCategory();
                $content->workshop_id = $workshop->id;
                $content->category_id = $cat;

                $content->save();
            }
        }
        if (isset($request->courses)) {
            WorkshopHasCourse::where('workshop_id', $workshop->id)->delete();
            foreach ($request->courses as $key => $course) {
                $content = new WorkshopHasCourse();
                $content->workshop_id = $workshop->id;
                $content->course_id = $course;

                $content->save();
            }
        }
        $success = true;
        DB::commit();
        return Redirect::route('admin.workshop.index', compact('success'));
        // } catch (Exception $e) {
        // }
    }
    public function uploadThumbnail(Request $request)
    {
        if ($request->hasFile('thumbnail')) {
            $url = $this->upload($request);
            return response()->json(['url' => $url, 'success' => true], 200);
        } else {
            return response()->json(['url' => "", 'success' => false], 200);
        }
    }
    public function uploadDocument(Request $request)
    {
        if ($request->hasFile('document')) {
            $url = $this->uploadFiles($request);
            return response()->json(['url' => $url, 'success' => true], 200);
        } else {
            return response()->json(['url' => "", 'success' => false], 200);
        }
    }

    public function upload(Request $request)
    {

        try {

            $uuid = time();

            $file = $request->file('thumbnail');
            $name =  $file->getClientOriginalName();
            $filePath = 'images/workshop/' . $uuid . '/' .  $name;
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

    public function uploadFiles(Request $request)
    {
        try {

            $uuid = time();
            $file = $request['document'];
            $name =  $file->getClientOriginalName();
            $filePath = 'workshop/reports/' . 'tempory/' . $uuid . '/' . 'temp/' . $name;
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

    public function categoryStore(Request $request)
    {
        $category = WorkshopCategory::where('name', $request->category_name)->first();

        if (!isset($category)) {
            $category = new WorkshopCategory();
            $category->name = $request->category_name;
            $category->save();
        }
        $categories = WorkshopCategory::all();

        return response()->json([$categories], 200);
    }
}
