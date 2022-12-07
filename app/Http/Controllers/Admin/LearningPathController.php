<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ImageTrait;
use App\Models\Course;
use App\Models\CourseForWorkshop;
use App\Models\LearningPath;
use App\Models\LearningPathCategory;
use App\Models\LearningPathHasCategory;
use App\Models\LearningPathHasCourse;
use App\Models\LearningPathHasWorkshop;
use App\Models\UserCompanyDetails;
use App\Models\Workshop;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LearningPathController extends Controller
{
    use ImageTrait;

    public function index($msg = 'F')
    {
         $paths = LearningPath::with(
            'category:id,category_id,learning_path_id',
            'category.category:id,name',
        )->select('id', 'created_at', 'thumbnail', 'name', 'summary', 'company_type', 'status')
        ->get();
        return Inertia::render('Admin/LearningPath/LearningPathList', compact('msg', 'paths'));
    }

    public function create()
    {
        $categories = LearningPathCategory::all();
        $courses = CourseForWorkshop::where('status', 1)->select('id', 'title', 'thumbnail')->get();
        $workshops = Workshop::where('status', 1)->select('id', 'name', 'thumbnail')->get();
        $companies = UserCompanyDetails::select('id', 'name')->get();


        return Inertia::render('Admin/LearningPath/LearningPathCreate', compact('categories', 'courses', 'workshops', 'companies'));
    }

    public function store(Request $request)
    {
        // return $request->all();
        $learning_path = new LearningPath();
        $learning_path->name = $request->name;
        $learning_path->summary = $request->summary;
        $learning_path->status = $request->status;
        $learning_path->thumbnail = $request->thumbnail;
        $learning_path->save();

        if (isset($request->courses)) {
            foreach ($request->courses as $course) {
                $lpc = new LearningPathHasCourse();
                $lpc->learning_path_id = $learning_path->id;
                $lpc->course_id = $course;
                $lpc->save();
            }
        }
        if (isset($request->workshops)) {
            foreach ($request->workshops as $workshop) {
                $lpw = new LearningPathHasWorkshop();
                $lpw->learning_path_id = $learning_path->id;
                $lpw->workshop_id = $workshop;
                $lpw->save();
            }
        }
        if (isset($request->categories)) {
            foreach ($request->categories as $category) {
                $lpct = new LearningPathHasCategory();
                $lpct->learning_path_id = $learning_path->id;
                $lpct->category_id = $category;
                $lpct->save();
            }
        }


        return $this->index('S');
    }

    public function uploadThumb(Request $request)
    {
        if ($request->hasFile('thumbnail')) {
            return $this->uploadThumbnail($request, 'learning-path');
        } else {
            return response()->json(['url' => "", 'success' => false], 200);
        }
    }

    public function categoryStore(Request $request)
    {
        $category = LearningPathCategory::where('name', $request->category_name)->first();

        if (!isset($category)) {
            $category = new LearningPathCategory();
            $category->name = $request->category_name;
            $category->save();
        }
        $categories = LearningPathCategory::all();

        return response()->json([$categories], 200);
    }
}
