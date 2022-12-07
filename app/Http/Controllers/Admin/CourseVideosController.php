<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseVideos;
use App\Models\Video;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Vimeo\Laravel\Facades\Vimeo;
use Vimeo\Laravel\VimeoManager;

class CourseVideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = Video::orderBy('id', 'DESC')->get();
        return Inertia::render('Admin/Video/VideoList', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Video/VideoRegister');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, VimeoManager $vimeo)
    {
        $request->validate([
            'title' => 'required',
            'summary' => 'required',
            'thumbnail' => 'required',
            'video' => 'required',
        ], [
            'title.required' => '動画名は必須項目です。',
            'summary.required' => '動画概要は必須項目です。',
            'thumbnail.required' => 'サムネイルは必須項目です。',
            'video.required' => '詳細設定は必須項目です。',

        ]);
        try {
            DB::beginTransaction();
            $video = new Video();
            if ($request->hasFile('thumbnail')) {
                $video->thumbnail = $this->upload($request);
            }
            if ($request->hasFile('video')) {
                $uri = $vimeo->upload($request->video, ['name' => $request->title, 'description' => $request->summary]);
                $video->videos = $uri;
                $getID3 = new \getID3;
                $video_file = $getID3->analyze($request->file('video'));
                $duration_seconds = round($video_file['playtime_seconds'], 0);
                $video->duration_seconds = $duration_seconds;

                if ($request->hasFile('thumbnail')) {
                    $vimeoThumbUri = Vimeo::request("$uri?fields=metadata.connections.pictures.uri");
                    $vimeoThumbUri = $vimeoThumbUri['body']['metadata']['connections']['pictures']['uri'];
                    Vimeo::uploadImage($vimeoThumbUri, $request->file('thumbnail'), true );
                }
            }

            $video->title = $request->title;
            $video->summary = $request->summary;
            $video->status = $request->status;
            $video->save();
            DB::commit();
            $success = true;
            return Redirect::route('video.index', compact('success'));
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::route('video.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CourseVideos  $courseVideos
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseVideos  $courseVideos
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $video = Video::where('id', $id)->first();
        return Inertia::render('Admin/Video/VideoEdit', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseVideos  $courseVideos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VimeoManager $vimeo)
    {
        $request->validate([
            'title' => 'required',
            'summary' => 'required',
            'thumbnail' => 'required',
            'video' => 'required',
        ], [
            'title.required' => '動画名は必須項目です。',
            'summary.required' => '動画概要は必須項目です。',
            'thumbnail.required' => 'サムネイルは必須項目です。',
            'video.required' => '詳細設定は必須項目です。',

        ]);

        try {
            DB::beginTransaction();
            $video = Video::where('id', $request->id)->first();
            if ($request->hasFile('thumbnail')) {
                $video->thumbnail = $this->upload($request);
            }
            if ($request->hasFile('video')) {
                $uri = $vimeo->upload($request->video, ['name' => $request->title, 'description' => $request->summary]);
                $video->videos = $uri;
                $getID3 = new \getID3;
                $video_file = $getID3->analyze($request->file('video'));
                $duration_seconds = $video_file['playtime_seconds'];
                $video->duration_seconds = $duration_seconds;

                if ($request->hasFile('thumbnail')) {
                    $vimeoThumbUri = Vimeo::request("$uri?fields=metadata.connections.pictures.uri");
                    $vimeoThumbUri = $vimeoThumbUri['body']['metadata']['connections']['pictures']['uri'];
                    Vimeo::uploadImage($vimeoThumbUri, $request->file('thumbnail'), true );
                }

            }else{
                if ($request->hasFile('thumbnail')) {
                    $vimeoThumbUri = Vimeo::request("$video->videos?fields=metadata.connections.pictures.uri");
                    $vimeoThumbUri = $vimeoThumbUri['body']['metadata']['connections']['pictures']['uri'];
                    Vimeo::uploadImage($vimeoThumbUri, $request->file('thumbnail'), true );
                }
            }
            $video->title = $request->title;
            $video->summary = $request->summary;
            $video->status = $request->status;
            $video->save();
            DB::commit();
            $success = true;
            return Redirect::route('video.index', compact('success'));
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::route('video.create');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseVideos  $courseVideos
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }

    public function upload(Request $request)
    {
        $uuid = time();
        $file = $request->file('thumbnail');
        $name =  $file->getClientOriginalName();
        // $name =  $file->getClientOriginalName().'-'.time() ;
        $filePath = 'images/' . $uuid . '/' .  $name;
        Storage::disk('s3')->put($filePath, file_get_contents($file));

        $url = Storage::disk('s3')->url($filePath);

        return $url;
    }
}
