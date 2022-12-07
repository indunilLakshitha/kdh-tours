<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeCategory;
use App\Models\NoticeHasCategory;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::with(
            'categories:id,category_id,notice_id',
            'categories.category:id,category',
            'user:id,username'
        )
            // ->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        return Inertia::render('Admin/Notice/NoticeList', compact('notices'));
    }

    public function create()
    {
        $notice_categories = NoticeCategory::select('id', 'category')->get();
        return Inertia::render('Admin/Notice/NoticeCreate', ['notice_categories' => $notice_categories]);
    }

    public function store(Request $request)
    {
        try {

            DB::beginTransaction();
            $notice  = new Notice();
            $notice->title  = $request->title;
            $notice->status  = $request->status;
            $notice->link  = $request->link;
            $notice->created_by  = Auth::user()->id;
            $notice->save();

            foreach ($request->categories as $key => $value) {
                // return $cat;
                $cat = new NoticeHasCategory();
                $cat->notice_id = $notice->id;
                $cat->category_id = $value;
                $cat->save();
            }

            DB::commit();
            $success = true;
            return Redirect::route('admin.notice.index', compact('success'));
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function categoryStore(Request $request)
    {
        $category = NoticeCategory::where('category', $request->category_name)->first();

        if (!isset($category)) {
            $category = new NoticeCategory();
            $category->category = $request->category_name;
            $category->save();
        }
        $categories = NoticeCategory::all();

        return response()->json([$categories], 200);
    }

    public function deleteNotices(Request $request)
    {
        if (sizeof($request->selected_notices) > 0) {

            foreach ($request->selected_notices as $key => $value) {
                Notice::where('id', $value)->delete();
            }
        }
        $notices = Notice::with(
            'categories:id,category_id,notice_id',
            'categories.category:id,category',
            'user:id,username'
        )
            // ->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        $success = true;
        return redirect()->route('admin.notice.index');
    }

    public function edit($id)
    {
        try {
            $notice = Notice::with(
                'categories:id,category_id,notice_id',
                'categories.category:id,category',
                'user:id,username'
            )
                ->where('id', $id)
                ->first();

            if (isset($notice)) {
                if ($notice->created_by != Auth::user()->id) {
                    throw ValidationException::withMessages([
                        'message' => __('messages.validation.common_error'),
                    ]);
                } else {
                    $notice_categories = NoticeCategory::select('id', 'category')->get();
                    return Inertia::render('Admin/Notice/NoticeEdit', ['notice_categories' => $notice_categories, 'notice' => $notice]);
                }
            }
        } catch (Exception $e) {

            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //'message' => $th->getMessage(),
            ]);
        }
    }

    public function update(Request $request)
    {
        try {

            $notice  = Notice::where('id', $request->id)->first();


            if (isset($notice)) {
                if ($notice->created_by != Auth::user()->id) {
                    throw ValidationException::withMessages([
                        'message' => __('messages.validation.common_error'),
                    ]);
                } else {

                    DB::beginTransaction();
                    $notice->title  = $request->title;
                    $notice->status  = $request->status;
                    $notice->link  = $request->link;
                    $notice->created_by  = Auth::user()->id;
                    $notice->save();

                    NoticeHasCategory::where('notice_id', $notice->id)->delete();
                    foreach ($request->categories as $key => $value) {
                        // return $cat;
                        $cat = new NoticeHasCategory();
                        $cat->notice_id = $notice->id;
                        $cat->category_id = $value;
                        $cat->save();
                    }
                    DB::commit();
                    $success = true;
                    return Redirect::route('admin.notice.index', compact('success'));
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
