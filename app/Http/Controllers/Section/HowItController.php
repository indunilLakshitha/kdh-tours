<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use App\Models\Section\HowItWork;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class HowItController extends Controller
{

    public function index()
    {
        $HowItWorks = HowItWork::orderBy('updated_at', 'desc')
            ->get();

        return Inertia::render('Admin/HOWIT/List', compact('HowItWorks'));
    }

    public function create()
    {
        return Inertia::render('Admin/HOWIT/Create');
    }

    public function store(Request $request)
    {
        try {

            DB::beginTransaction();
            $HowItWork  = new HowItWork();
            $HowItWork->title  = $request->title;
            $HowItWork->description  = $request->description;
            $HowItWork->status  = $request->status;
            $HowItWork->save();

            DB::commit();

            return Redirect::route('admin.how.index');
        } catch (Exception $e) {

            DB::rollBack();
        }
    }



    public function deleteHowItWorks(Request $request)
    {
        if (sizeof($request->selected_HowItWorks) > 0) {

            foreach ($request->selected_HowItWorks as $key => $value) {
                HowItWork::where('id', $value)->delete();
            }
        }
        $HowItWorks = HowItWork::with(
            'categories:id,category_id,HowItWork_id',
            'categories.category:id,category',
            'user:id,username'
        )
            // ->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        $success = true;
        return redirect()->route('admin.HowItWork.index');
    }

    public function edit($id)
    {
        try {
            $HowItWork = HowItWork::where('id', $id)
                ->first();

            if (isset($HowItWork)) {
                return Inertia::render('Admin/HOWIT/Edit', ['HowItWork' => $HowItWork]);
            } else {
                return abort(404);
            }
        } catch (Exception $e) {

            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try {

            $HowItWork  = HowItWork::where('id', $request->id)->first();


            if (isset($HowItWork)) {

                $HowItWork->title  = $request->title;
                $HowItWork->description  = $request->description;
                $HowItWork->status  = $request->status;

                $HowItWork->save();
                return Redirect::route('admin.how.index');
            }
        } catch (Exception $e) {

            return redirect()->back();
        }
    }
}
