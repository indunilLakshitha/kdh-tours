<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Course;
use App\Models\CourseVediosAndTitle;
use App\Models\User;
use App\Models\UserCourseActivities;
use App\Models\UserHasCourse;
use App\Models\UserHasFavourite;
use App\Models\UserReportActivities;
use App\Models\UserReportActivityDetails;
use App\Models\Video;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class VideoViewController extends Controller
{
    use GeneralTrait;

    public function courseDetails($id)
    {
        $user = Auth::guard('web')->user();
        $is_fav= '';
        $edit = true;
        $fav = UserHasFavourite::where('user_id', $user->id)->where('course_id',$id)->first();

        if(isset($fav)){
            $is_fav = 1;
        }else{
            $is_fav = 0;
        }

        $allVideos = CourseVediosAndTitle::select('id')->where('course_id', $id)->where('type', 1)->get();
        $allVideosArray = array();
        foreach ($allVideos as $key => $allVideo) {
            array_push($allVideosArray, $allVideo['id']);
        }
        if (count($allVideosArray) > 0) {
            $firstVideoTitleDetails = CourseVediosAndTitle::select('vedio_id','id')->where('id', $allVideosArray[0])->first();
            $firstVideo = Video::where('id', $firstVideoTitleDetails->vedio_id)->first();
        }else{
            $firstVideoTitleDetails = 'No Videos';
        }

        $allVideos = CourseVediosAndTitle::select('id')->where('course_id', $id)->where('type', 1)->get();
        $allVideosArray = array();
        foreach ($allVideos as $key => $allVideo) {
            array_push($allVideosArray, $allVideo['id']);
        }
        if (count($allVideosArray) > 0) {
            $firstVideoTitleDetails = CourseVediosAndTitle::select('vedio_id','id')->where('id', $allVideosArray[0])->first();
            $firstVideo = Video::where('id', $firstVideoTitleDetails->vedio_id)->first();
        }else{
            $firstVideoTitleDetails = 'No Videos';
        }

        $percentage = $this->newCoursePercentage($id, Auth::user()->id);

        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'authUserCourse.reportActivities.reportActivityDetails',
            'postViewings',
        )->where('id', $id)->first();
        $userHasCourse = UserHasCourse::where('course_id', $id)->where('user_id', Auth::user()->id)->first();
        if ($userHasCourse->progress_status == 2) {
            $user = Auth::user();
            $documents = $courseDetails->postViewings->where('type', 1)->count();
            $worksheet = $courseDetails->postViewings->where('type', 0)->count();
            return Inertia::render('User/Video/VideoView7', compact('courseDetails', 'worksheet', 'documents', 'user','is_fav','edit',
            'firstVideoTitleDetails', 'percentage'));
        } elseif ($userHasCourse->progress_status != 2) {
            return Inertia::render('User/Video/VideoView1', compact('courseDetails','is_fav','edit', 'firstVideoTitleDetails', 'percentage'));
        }
        //return $courseDetails;
        return Inertia::render('User/Video/VideoView1', compact('courseDetails','is_fav','edit', 'firstVideoTitleDetails'));
    }

    public function courseDetailsOfUser($id,$user_id)
    {
        $logged_user = Auth::guard('web')->user();
        $edit = true;
        if($logged_user->user_type != 1 && $logged_user->id !=$user_id){
            return abort(403,'Unauthorized');
        }
        if($logged_user->user_type == 1 && $logged_user->id !=$user_id){
            $edit = false;
        }
        $is_fav= '';
        $fav = UserHasFavourite::where('user_id', $user_id)->where('course_id',$id)->first();

        $allVideos = CourseVediosAndTitle::select('id')->where('course_id', $id)->where('type', 1)->get();
        $allVideosArray = array();
        foreach ($allVideos as $key => $allVideo) {
            array_push($allVideosArray, $allVideo['id']);
        }
        if (count($allVideosArray) > 0) {
            $firstVideoTitleDetails = CourseVediosAndTitle::select('vedio_id','id')->where('id', $allVideosArray[0])->first();
            $firstVideo = Video::where('id', $firstVideoTitleDetails->vedio_id)->first();
        }else{
            $firstVideoTitleDetails = 'No Videos';
        }

        if(isset($fav)){
            $is_fav = 1;
        }else{
            $is_fav = 0;
        }
        $user = User::where('id',$user_id)->first();
        if(!$user){
            return abort(404,'User not Available');
        }
        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'authUserCourse.reportActivities.reportActivityDetails',
            'postViewings',
        )->where('id', $id)->first();
        $userHasCourse = UserHasCourse::where('course_id', $id)->where('user_id', $user_id)->first();
        if ($userHasCourse->progress_status == 2) {

            $documents = $courseDetails->postViewings->where('type', 1)->count();
            $worksheet = $courseDetails->postViewings->where('type', 0)->count();
            return Inertia::render('User/Video/VideoView7', compact('courseDetails', 'worksheet', 'documents', 'user','is_fav','edit', 'firstVideoTitleDetails'));
        } elseif ($userHasCourse->progress_status != 2) {
            return Inertia::render('User/Video/VideoView1', compact('courseDetails','is_fav','edit'));
        }
        //return $courseDetails;
        return Inertia::render('User/Video/VideoView1', compact('courseDetails','is_fav','edit'));
    }

    public function courseVideosView($id)
    {
        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'authUserCourse.reportActivities.reportActivityDetails'
        )->where('id', $id)->first();
        $video = $courseDetails->courseTitles->first()->belongVideos()->first()->belongVideo()->first();
        $watchedVideo = UserCourseActivities::where('course_id', $id)->where('user_id', Auth::user()->id)->where('video_id', $video->id)->first();
        $allVideos = CourseVediosAndTitle::select('id')->where('course_id', $id)->where('type', 1)->get();
        $allVideosArray = array();
        foreach ($allVideos as $key => $allVideo) {
            array_push($allVideosArray, $allVideo['id']);
        }
        if (count($allVideosArray) > 0) {
            $firstVideoTitleDetails = CourseVediosAndTitle::select('vedio_id','id')->where('id', $allVideosArray[0])->first();
            $firstVideo = Video::where('id', $firstVideoTitleDetails->vedio_id)->first();
        }

        return Inertia::render('User/Video/VideoView2', compact('courseDetails', 'video', 'watchedVideo', 'firstVideoTitleDetails'));
    }

    public function courseVideosViewFull($id)
    {
        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'authUserCourse.reportActivities.reportActivityDetails'
        )->where('id', $id)->first();
        $video = $courseDetails->courseTitles->first()->belongVideos()->first()->belongVideo()->first();
        $watchedVideo = UserCourseActivities::where('course_id', $id)->where('user_id', Auth::user()->id)->where('video_id', $video->id)->first();
        $firstVideoId = "";
        return Inertia::render('User/Video/VideoView3', compact('courseDetails', 'video', 'watchedVideo'));
    }
    public function courseNavigationBar($id)
    {
        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'authUserCourse.reportActivities.reportActivityDetails'
        )->where('id', $id)->first();

        $percentage = $this->newCoursePercentage($id, Auth::user()->id);

        return Inertia::render('User/SidebarWatch', compact('courseDetails', 'percentage'));
    }

    public function videoPlay($idTitle, $videoId, $courseId)
    {
        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'authUserCourse.reportActivities.reportActivityDetails'
        )->where('id', $courseId)->first();
        $allVideos = CourseVediosAndTitle::select('id')->where('course_id', $courseId)->where('type', 1)->get();
        $allVideosArray = array();
        $lastVideo = false;
        foreach ($allVideos as $key => $allVideo) {
            array_push($allVideosArray, $allVideo['id']);
        }
        $videoTitleId = array_search($idTitle, $allVideosArray);
        if ($videoTitleId != count($allVideosArray)-1) {
            $nextVideoTitleDetails = CourseVediosAndTitle::select('vedio_id','id')->where('id', $allVideosArray[$videoTitleId+1])->first();
            $nextVideo = Video::where('id', $nextVideoTitleDetails->vedio_id)->first();
        } elseif ($videoTitleId == count($allVideosArray)-1) {
            $nextVideoTitleDetails = CourseVediosAndTitle::select('vedio_id','id')->where('id', $allVideosArray[0])->first();
            $nextVideo = Video::where('id', $nextVideoTitleDetails->vedio_id)->first();
        }

        if (count($allVideosArray) > 0) {
            $firstVideoTitleDetails = CourseVediosAndTitle::select('vedio_id','id')->where('id', $allVideosArray[0])->first();
            $firstVideo = Video::where('id', $firstVideoTitleDetails->vedio_id)->first();
        }

        if ($allVideosArray[count($allVideosArray)-1] == $idTitle) {
            if ($courseDetails->has_reports == 1) {
                $lastVideo = true;
            }
        }

        $video = Video::where('id', $videoId)->first();
        $watchedVideo = UserCourseActivities::where('course_id', $courseId)->where('user_id', Auth::user()->id)->where('video_id', $videoId)->first();
        return Inertia::render('User/Video/VideoView2', compact('courseDetails', 'video', 'watchedVideo', 'nextVideo',
        'nextVideoTitleDetails', 'firstVideoTitleDetails', 'lastVideo'));
    }

    public function videoPlayFull($idTitle, $videoId, $courseId)
    {
        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'authUserCourse.reportActivities.reportActivityDetails'
        )->where('id', $courseId)->first();
        $allVideos = CourseVediosAndTitle::select('id')->where('course_id', $courseId)->where('type', 1)->get();
        $allVideosArray = array();
        $lastVideo = false;

        foreach ($allVideos as $key => $allVideo) {
            array_push($allVideosArray, $allVideo['id']);
        }

        if ($allVideosArray[count($allVideosArray)-1] == $idTitle) {
            if ($courseDetails->has_reports == 1) {
                $lastVideo = true;
            }
        }

        $videoTitleId = array_search($idTitle, $allVideosArray);
        $nextVideoTitleDetails = CourseVediosAndTitle::select('vedio_id','id')->where('id', $allVideosArray[$videoTitleId+1])->first();
        $nextVideo = Video::where('id', $nextVideoTitleDetails->vedio_id)->first();
        $video = Video::where('id', $videoId)->first();
        $watchedVideo = UserCourseActivities::where('course_id', $courseId)->where('user_id', Auth::user()->id)->where('video_id', $videoId)->first();
        return Inertia::render('User/Video/VideoView3', compact('courseDetails', 'video', 'watchedVideo', 'nextVideo', 'nextVideoTitleDetails', 'lastVideo'));
    }

    public function report($courseId)
    {
        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'postViewings',
            'reports',
            'authUserCourse.reportActivities.reportActivityDetails',
        )->where('id', $courseId)->first();
        //return $courseDetails;
        return Inertia::render('User/Video/VideoView4', compact('courseDetails'));
    }

    public function reportFull($courseId)
    {
        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'postViewings',
            'reports',
            'authUserCourse.reportActivities.reportActivityDetails',
        )->where('id', $courseId)->first();
        //return $courseDetails;
        return Inertia::render('User/Video/VideoView4Full', compact('courseDetails'));
    }

    public function videoRoute(Request $request)
    {
        $allVideos = CourseVediosAndTitle::where('course_id', $request->id)->where('type', 1)->first();
        $allVideosArray = array();
        $lastVideo = false;

        // foreach ($allVideos as $key => $allVideo) {
        //     array_push($allVideosArray, $allVideo['id']);
        // }

        // if ($allVideosArray[count($allVideosArray)-1] == $idTitle) {
        //     if ($courseDetails->has_reports == 1) {
        //         $lastVideo = true;
        //     }
        // }
        //return $courseDetails;
        return $allVideos;
    }

    public function watchTime(Request $request)
    {
        try {
            DB::beginTransaction();
            $videoCurrentStatus = 1;
            $activity = UserCourseActivities::where('course_id', $request->courseId)->where('video_id', $request->videoId)
                ->where('user_id', Auth::user()->id)->first();
            $mainVideoTime = Video::select('duration_seconds')->where('id', $request->videoId)->first();

            if (!empty($activity)) {
                if ($request->time > $activity->total_watch_time) {
                    $activity->total_watch_time = round($request->time, 0);
                    if (
                        $mainVideoTime->duration_seconds == round($request->time, 0)
                        || $mainVideoTime->duration_seconds - 1 == round($request->time, 0)
                        || $mainVideoTime->duration_seconds + 1 == round($request->time, 0)
                    ) {
                        $activity->status = 0;
                        $videoCurrentStatus = 0;
                    } else {
                        $activity->status = 1;

                    }
                    $activity->save();
                }
            } elseif (empty($activity)) {
                $crateActivity = new UserCourseActivities();
                $crateActivity->course_id = $request->courseId;
                $crateActivity->video_id = $request->videoId;
                $crateActivity->user_id = Auth::user()->id;
                $crateActivity->total_watch_time = round($request->time, 0);
                if (
                    $mainVideoTime->duration_seconds == round($request->time, 0)
                    || $mainVideoTime->duration_seconds - 1 == round($request->time, 0)
                    || $mainVideoTime->duration_seconds + 1 == round($request->time, 0)
                ) {
                    $crateActivity->status = 0;
                    $videoCurrentStatus = 0;
                } else {
                    $crateActivity->status = 1;

                }
                $crateActivity->save();
            } else {
            }
            $courseVideos = Course::with('courseVideosOnly')->where('id', $request->courseId)->first();
            $videoCount = 0;
            if (!empty($courseVideos->courseVideosOnly)) {
                $videoCount = $courseVideos->courseVideosOnly->count();
            }
            $completedActivities = UserCourseActivities::where('course_id', $request->courseId)->where('status', 0)
                ->where('user_id', Auth::user()->id)->count();

            $completedReport = 0;

            $userHasCourse = UserHasCourse::where('course_id', $request->courseId)->where('user_id', Auth::user()->id)->first();

            if ($courseVideos->has_reports == 1) {
                $report = UserReportActivities::where('course_id', $request->courseId)->where('status', 1)
                    ->where('user_id', Auth::user()->id)->first();

                if (!empty($report)) {
                    $completedReport = 1;
                }

                if ($completedActivities == $videoCount && $completedReport == 1) {

                    $userHasCourse->progress_status = 2;
                    $userHasCourse->save();
                    DB::commit();
                    return ['Finished',$videoCurrentStatus];
                } else {
                    $userHasCourse->progress_status = 1;
                    $userHasCourse->save();
                    DB::commit();
                    return ['success',$videoCurrentStatus];
                }
            } else {
                if ($completedActivities == $videoCount) {

                    $userHasCourse->progress_status = 2;
                    $userHasCourse->save();
                    DB::commit();
                    return ['Finished',$videoCurrentStatus];
                } else {
                    $userHasCourse->progress_status = 1;
                    $userHasCourse->save();
                    DB::commit();
                    return ['success',$videoCurrentStatus];
                }
            }

            DB::commit();
            return ['success',$videoCurrentStatus];
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function reportSave(Request $request)
    {
        try {
            $reportActivity = UserReportActivities::where('user_id', Auth::user()->id)->where('course_id', $request->courseId)->first();
            $userHasCourse = UserHasCourse::where('course_id', $request->courseId)->where('user_id', Auth::user()->id)->first();

            if ($reportActivity) {
                $reportActivity->status = $request->status;
                $reportActivity->save();

                foreach ($request->text as $key1 => $value1) {
                    $reportActivityDetail = UserReportActivityDetails::where('report_activity_id', $reportActivity->id)
                        ->where('user_id', Auth::user()->id)
                        ->where('course_id', $request->courseId)
                        ->where('course_report_id', $value1['id'])
                        ->first();

                    if (!empty($reportActivityDetail)) {
                        $reportActivityDetail->pos = $key1;
                        if (!empty($value1['text'])) {
                            $reportActivityDetail->content = $value1['text'];
                            $reportActivityDetail->status = 1;
                        } elseif (empty($value1['text'])) {
                            $reportActivityDetail->content = null;
                            $reportActivityDetail->status = 0;
                        }
                        $reportActivityDetail->save();
                    } elseif (!empty($value1['text']) && empty($reportActivityDetail)) {
                        $newReportActivityDetail = new UserReportActivityDetails();
                        $newReportActivityDetail->report_activity_id = $reportActivity->id;
                        $newReportActivityDetail->user_id = Auth::user()->id;
                        $newReportActivityDetail->course_id = $request->courseId;
                        $newReportActivityDetail->course_report_id = $value1['id'];
                        $newReportActivityDetail->pos = $key1;
                        $newReportActivityDetail->type = 0;
                        $newReportActivityDetail->content = $value1['text'];
                        $newReportActivityDetail->status = 1;
                        $newReportActivityDetail->save();
                    }
                }
                if (!empty($request->documents)) {
                    foreach ($request->documents as $key2 => $value2) {
                        $reportActivityDetail = UserReportActivityDetails::where('report_activity_id', $reportActivity->id)
                            ->where('user_id', Auth::user()->id)
                            ->where('course_id', $request->courseId)
                            ->where('course_report_id', $value2['id'])
                            ->first();
                        if (!empty($reportActivityDetail)) {
                            $reportActivityDetail->pos = $key1;
                            if (!empty($value2['url'])) {
                                $reportActivityDetail->document = $this->moveDocument($value2['url'], $request->courseId, $value2['name'], 'reports');
                                $reportActivityDetail->status = 1;
                            } else {
                                $reportActivityDetail->document = null;
                                $reportActivityDetail->status = 0;
                            }
                            $reportActivityDetail->save();
                        } elseif (empty($reportActivityDetail)) {
                            $newReportActivityDetail = new UserReportActivityDetails();
                            $newReportActivityDetail->report_activity_id = $reportActivity->id;
                            $newReportActivityDetail->user_id = Auth::user()->id;
                            $newReportActivityDetail->course_id = $request->courseId;
                            $newReportActivityDetail->course_report_id = $value2['id'];
                            $newReportActivityDetail->pos = $key2;
                            $newReportActivityDetail->type = 1;
                            if (!empty($value2['url'])) {
                                $newReportActivityDetail->document = $this->moveDocument($value2['url'], $request->courseId, $value2['name'], 'reports');
                                $newReportActivityDetail->status = 1;
                            } else {
                                $reportActivityDetail->document = null;
                                $reportActivityDetail->status = 0;
                            }
                            $newReportActivityDetail->save();
                        }
                    }
                } elseif (empty($request->documents)) {
                    $reportActivityDetails = UserReportActivityDetails::where('report_activity_id', $reportActivity->id)
                        ->where('user_id', Auth::user()->id)
                        ->where('course_id', $request->courseId)
                        ->where('type', 1)
                        ->get();
                    if (!empty($reportActivityDetails)) {
                        foreach ($reportActivityDetails as $key3 => $value3) {
                            $value3->document = null;
                            $value3->save();
                        }
                    }
                }
            } else {
                $newReportActivity = new UserReportActivities();
                $newReportActivity->user_id = Auth::user()->id;
                $newReportActivity->course_id = $request->courseId;
                $newReportActivity->status = $request->status;
                $newReportActivity->save();
                foreach ($request->text as $key1 => $value1) {
                    if (!empty($value1['text'])) {
                        $newReportActivityDetail = new UserReportActivityDetails();
                        $newReportActivityDetail->report_activity_id = $newReportActivity->id;
                        $newReportActivityDetail->user_id = Auth::user()->id;
                        $newReportActivityDetail->course_id = $request->courseId;
                        $newReportActivityDetail->course_report_id = $value1['id'];
                        $newReportActivityDetail->pos = $key1;
                        $newReportActivityDetail->type = 0;
                        $newReportActivityDetail->content = $value1['text'];
                        $newReportActivityDetail->status = 1;
                        $newReportActivityDetail->save();
                    }
                }
                if (!empty($request->documents)) {
                    foreach ($request->documents as $key2 => $value2) {
                        $newReportActivityDetail = new UserReportActivityDetails();
                        $newReportActivityDetail->report_activity_id = $newReportActivity->id;
                        $newReportActivityDetail->user_id = Auth::user()->id;
                        $newReportActivityDetail->course_id = $request->courseId;
                        $newReportActivityDetail->course_report_id = $value2['id'];
                        $newReportActivityDetail->pos = $key2;
                        $newReportActivityDetail->type = 1;
                        if (!empty($value2['url'])) {
                            $newReportActivityDetail->document = $this->moveDocument($value2['url'], $request->courseId, $value2['name'], 'reports');
                            $newReportActivityDetail->status = 1;
                        }
                        $newReportActivityDetail->save();
                    }
                }
            }

            $courseVideos = Course::with('courseVideosOnly')->where('id', $request->courseId)->first();
            $videoCount = 0;
            if (!empty($courseVideos->courseVideosOnly)) {
                $videoCount = $courseVideos->courseVideosOnly->count();
            }
            $completedActivities = UserCourseActivities::where('course_id', $request->courseId)->where('status', 0)
                ->where('user_id', Auth::user()->id)->count();

            $completedReport = 0;

            if ($courseVideos->has_reports == 1) {
                $report = UserReportActivities::where('course_id', $request->courseId)->where('status', 1)
                    ->where('user_id', Auth::user()->id)->first();

                if (!empty($report)) {
                    $completedReport = 1;
                }

                if ($completedActivities == $videoCount && $completedReport == 1) {

                    $userHasCourse->progress_status = 2;
                    $userHasCourse->save();
                    DB::commit();
                    return 'success';
                } else {
                    $userHasCourse->progress_status = 1;
                    $userHasCourse->save();
                    DB::commit();
                    return 'success';
                }
            } else {
                if ($completedActivities == $videoCount) {

                    $userHasCourse->progress_status = 2;
                    $userHasCourse->save();
                    DB::commit();
                    return 'success';
                } else {
                    $userHasCourse->progress_status = 1;
                    $userHasCourse->save();
                    DB::commit();
                    return 'success';
                }
            }
            DB::commit();
            return 'success';
        } catch (Exception $e) {
            DB::rollBack();
            return 'failed';
        }
    }

    public function uploadReport($request, $course_id)
    {

        try {
            $uuid = $course_id;
            $file = $request['file'];
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

    public function document($courseId)
    {
        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'postViewings',
            'authUserCourse.reportActivities.reportActivityDetails'
        )->where('id', $courseId)->first();

        $user = Auth::user();
        $documents = $courseDetails->postViewings->where('type', 1)->count();
        $worksheet = $courseDetails->postViewings->where('type', 0)->count();

        return Inertia::render('User/Video/VideoView5', compact('courseDetails', 'worksheet', 'documents', 'user'));
    }

    public function documentFull($courseId)
    {
        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'postViewings',
            'authUserCourse.reportActivities.reportActivityDetails'
        )->where('id', $courseId)->first();

        $user = Auth::user();
        $documents = $courseDetails->postViewings->where('type', 1)->count();
        $worksheet = $courseDetails->postViewings->where('type', 0)->count();

        return Inertia::render('User/Video/VideoView5Full', compact('courseDetails', 'worksheet', 'documents', 'user'));
    }

    public function worksheet($courseId)
    {
        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'postViewings',
            'authUserCourse.reportActivities.reportActivityDetails'
        )->where('id', $courseId)->first();

        $user = Auth::user();
        $documents = $courseDetails->postViewings->where('type', 1)->count();
        $worksheet = $courseDetails->postViewings->where('type', 0)->count();

        return Inertia::render('User/Video/VideoView5', compact('courseDetails', 'worksheet', 'documents', 'user'));
    }

    public function worksheetFull($courseId)
    {
        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'postViewings',
            'authUserCourse.reportActivities.reportActivityDetails'
        )->where('id', $courseId)->first();

        $user = Auth::user();
        $documents = $courseDetails->postViewings->where('type', 1)->count();
        $worksheet = $courseDetails->postViewings->where('type', 0)->count();

        return Inertia::render('User/Video/VideoView5Full', compact('courseDetails', 'worksheet', 'documents', 'user'));
    }

    public function reportDocumentSave(Request $request)
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
            return $e;
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

    public function courseComplete($id)
    {
        $courseDetails = Course::with(
            'courseTitles.belongVideos.belongVideo',
            'courseHasCategories.category',
            'authUserCourse.courseActivities.belongVideo',
            'postViewings',
            'authUserCourse.reportActivities.reportActivityDetails'
        )->where('id', $id)->first();

        $user = Auth::user();
        $documents = $courseDetails->postViewings->where('type', 1)->count();
        $worksheet = $courseDetails->postViewings->where('type', 0)->count();

        return Inertia::render('User/Video/VideoView5', compact('courseDetails', 'worksheet', 'documents', 'user'));
    }
}
