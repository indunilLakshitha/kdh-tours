<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\CourseTrait;
use App\Mail\WorkshopApplyMail;
use App\Models\Course;
use App\Models\User;
use App\Models\UserHasWorkshop;
use App\Models\UserHasWorkshopActivity;
use App\Models\Workshop;
use App\Models\WorkshopHasAssignment;
use App\Models\WorkshopHasCourse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class WorkshopController extends Controller
{

    use CourseTrait;

    public function index()
    {
        $workshops = Workshop::withCount('userCount')
            ->with('currentUser')
            ->where('status', 1)
            // ->where('reception_status', 1)
            ->get();

        foreach ($workshops as $workshop) {
            if ($workshop->closing_date < Carbon::now() || $workshop->user_count_count >= $workshop->capacity || $workshop->reception_status == 0) {
                $workshop->expired = true;
            } else {
                $workshop->expired = false;
            }

            if (isset($workshop->currentUser)) {
                if ($workshop->currentUser->status == 0) {
                    $workshop->expected = true;
                    $workshop->applied = false;
                } else {
                    $workshop->expected = false;
                    $workshop->applied = true;
                }
            } else {
                $workshop->expected = false;
                $workshop->applied = false;
            }
        }

        return Inertia::render('User/Workshop/WorkshopList', compact('workshops'));
    }

    public function mainList()
    {
        $user = Auth::user();
        if ($user->user_type != 1) {
            return abort(403);
        }

        $workshops = Workshop::with(
            'categories:category_id,workshop_id,id',
            'categories.category:id,name',
        )->where('status', 1)->where('reception_status', 1)->get();
        return Inertia::render('HR/Workshop/Workshop', compact('workshops'));
    }

    public function mainEdit($id)
    {
        $user = Auth::user();
        if ($user->user_type != 1) {
            return abort(403);
        }
        $company_user_ids = User::where('company_id', $user->company_id)->pluck('id');
        $workshop = Workshop::with(
            'categories:category_id,workshop_id,id',
            'categories.category:id,name',
        )->where('status', 1)->where('reception_status', 1)
            ->where('id', $id)->first();

        if (!isset($workshop)) {
            return abort(404);
        }

        if (isset($workshop) && sizeof($company_user_ids) > 0) {

            $workshop_users = UserHasWorkshop::with('user:id,name,name_in_furigana')
                ->where('workshop_id', $id)
                ->whereIn('user_id', $company_user_ids)
                ->select('id', 'workshop_id', 'user_id')
                ->get();
        } else {
            $workshop_users = [];
        }
        return Inertia::render('HR/Workshop/WorkshopEdit', compact('workshop', 'workshop_users'));
    }

    public function view($id)
    {

        $user = Auth::user();
        $userHasWorkshop = UserHasWorkshop::where('user_id', $user->id)->where('workshop_id', $id)->first();
        $workshop = Workshop::withCount('userCount')
            ->with(
                'categories:category_id,workshop_id,id',
                'categories.category:id,name',
                // 'courses.course:id,thumbnail,title,total_watchtime',
                // 'courses.course.courseHasCategories:id,course_id,category_id',
                // 'courses.course.courseHasCategories.category:id,name',
                'workshopActivity.assignment',
                'workshopActivity.assignment:id,title,url,description',
            )
            ->where('status', 1) //active status
            // ->where('reception_status', 1)  //recipient allowing or no
            ->where('id', $id)
            ->first();

        if (!isset($workshop)) {
            return abort(404);
        }

        if (isset($workshop)) {

            $courses_ids = WorkshopHasCourse::where('workshop_id', $workshop->id)->pluck('course_id');
            $courses = Course::with(
                'courseHasCategories:id,course_id,category_id',
                'courseHasCategories.category:id,name',
                'firstVideo'
            )->whereIn('id', $courses_ids)->select('id', 'thumbnail', 'title', 'total_watchtime')->get();
        }

        $workshop->courses = $courses;
        $just_applied = false;

        if ($workshop->closing_date < Carbon::now() || $workshop->user_count_count >= $workshop->capacity) {
            $workshop->expired = 1;
        } else {
            $workshop->expired = 0;
        }

        if (isset($userHasWorkshop)) {
            if ($userHasWorkshop->status == 0) {
                $workshop->applied = 0;
            } else if ($userHasWorkshop->status == 1) {
                $workshop->applied = 1;
            }
        } else {
            $workshop->applied = 2;
        }

        if ($workshop->applied == 1) {
            return Inertia::render('User/Workshop/WorkshopAfterApply', compact('workshop', 'just_applied'));
        }

        // if ($workshop->applied == 1 && $workshop->expired != 1) {
        //     return Inertia::render('User/Workshop/WorkshopAfterApply', compact('workshop', 'just_applied'));
        //     // return 1;
        // }

        return Inertia::render('User/Workshop/WorkshopDetails', compact('workshop', 'just_applied'));
    }

    public function apply($id, $type)
    {
        if ($type == 2) {
            $type = 0;
        }

        try {


            DB::beginTransaction();
            $user = Auth::user();
            $userHasWorkshop = UserHasWorkshop::where('user_id', $user->id)->where('workshop_id', $id)->first();

            $workshop = Workshop::withCount('userCount')
                ->with(
                    'categories:category_id,workshop_id,id',
                    'categories.category:id,name',
                    // 'courses.course:id,thumbnail,title,total_watchtime',
                    // 'courses.course.courseHasCategories:id,course_id,category_id',
                    // 'courses.course.courseHasCategories.category:id,name',
                    'workshopActivity.assignment',
                    'workshopActivity.assignment:id,title,url,description',
                )
                ->where('status', 1) //active status
                // ->where('reception_status', 1)  //recipient allowing or no
                ->where('id', $id)
                ->first();

            if (!isset($workshop)) {
                return abort(404);
            }
            $courses_ids = WorkshopHasCourse::where('workshop_id', $workshop->id)->pluck('course_id');
            $courses = Course::with(
                'courseHasCategories:id,course_id,category_id',
                'courseHasCategories.category:id,name',
                'firstVideo'
            )->whereIn('id', $courses_ids)->select('id', 'thumbnail', 'title', 'total_watchtime')->get();

            $workshop->courses = $courses;


            if (!isset($userHasWorkshop)) {
                $userHasWorkshop = new UserHasWorkshop();
            }

            $userHasWorkshop->user_id = $user->id;
            $userHasWorkshop->workshop_id = $id;
            $userHasWorkshop->status = $type;
            $userHasWorkshop->save();

            if ($userHasWorkshop->status = 1) {

                $assignments = WorkshopHasAssignment::where('workshop_id', $workshop->id)->select('id')->get();
                if (isset($assignments)) {
                    foreach ($assignments as $assignment) {

                        $userHasWorkshopActivity = UserHasWorkshopActivity::where('workshop_id', $workshop->id)->where('workshop_has_assignments_id', $assignment->id)->first();

                        if (!isset($userHasWorkshopActivity)) {
                            $userHasWorkshopActivity = new UserHasWorkshopActivity();
                            $userHasWorkshopActivity->user_id = $user->id;
                            $userHasWorkshopActivity->user_has_workshops_id = $userHasWorkshop->id;
                            $userHasWorkshopActivity->workshop_id = $workshop->id;
                            $userHasWorkshopActivity->workshop_has_assignments_id = $assignment->id;
                            $userHasWorkshopActivity->status = 0;
                            $userHasWorkshopActivity->save();
                        }
                    }
                }
            }
            $just_applied = true;
            try {
                Mail::to($user->email)->send(new WorkshopApplyMail($workshop));
            } catch (Exception $e) {
                \Log::channel('workshop-mail')->info('[' . $user->name . '][' . $workshop->id . '][' . $e->getMessage() . ']');
            }


            DB::commit();
            if ($type == 1) {
                return Inertia::render('User/Workshop/WorkshopAfterApply', compact('workshop', 'just_applied'));
            }
            return redirect()->route('workshop.view', $id);
        } catch (Exception $e) {
            \Log::channel('workshop')->info('[' . $user->name . '][WORKSOP APPLY][' . $workshop->id . '][' . $e->getMessage() . ']');
        }
    }


    public function participants($id)
    {
        return Inertia::render('Admin/Workshop/ParticipantList');
    }

    public function edit($id)
    {
        return Inertia::render('HR/Workshop/WorkshopEdit');
    }

    public function submitAssignments(Request $request)
    {
        if ($request->hasFile('document')) {
            $activity = UserHasWorkshopActivity::where('id', $request['id'])->first();
            if (isset($activity)) {
                $url = $this->uploadFiles($request);
                $activity->sumbmit_url = $url['url'];
                $activity->save();
            }
            return response()->json(['url' => $url, 'success' => true], 200);
        } else {
            return response()->json(['url' => "", 'success' => false], 200);
        }
    }

    public function removeAssignment($assignment_id)
    {
        $activity = UserHasWorkshopActivity::where('id', $assignment_id)->first();
        $user = Auth::user()->id;
        if (isset($activity)) {
            if ($activity->user_id == $user) {
                $url = NULL;
                $activity->sumbmit_url = NULL;
                $activity->save();
            }
        }
        return response()->json(['url' => NULL, 'success' => true], 200);
    }

    public function uploadFiles(Request $request)
    {
        try {

            // $uuid = time();
            $uuid = $request['workshop_id'];
            $file = $request['document'];
            $name =  $file->getClientOriginalName();
            $filePath = 'workshop/assignments/' . 'tempory/' . $uuid . '/' . 'temp/' . $name;
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
}
