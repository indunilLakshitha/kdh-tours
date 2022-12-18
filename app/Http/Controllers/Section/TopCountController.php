<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use App\Models\Section\TopCount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TopCountController extends Controller
{
    public function index()
    {
        $top = TopCount::with('file')->first();

        return Inertia::render('Admin/TOPCOUNT/List', compact('top'));
    }

    public function create()
    {
        return Inertia::render('Admin/TOPCOUNT/Create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $TOPCOUNTWork  = TopCount::first();
            $TOPCOUNTWork->description_1  = $request->description_1;
            $TOPCOUNTWork->description_2  = $request->description_2;
            $TOPCOUNTWork->type_1  = $request->type_1;
            $TOPCOUNTWork->type_3  = $request->type_3;
            $TOPCOUNTWork->type_2  = $request->type_2;
            $TOPCOUNTWork->image  = $request->image_id;
            $TOPCOUNTWork->save();

            DB::commit();

            return Redirect::route('admin.top.index');
        } catch (Exception $e) {

            DB::rollBack();
        }
    }



    public function deleteTOPCOUNTWorks(Request $request)
    {
        if (sizeof($request->selected_TOPCOUNTWorks) > 0) {

            foreach ($request->selected_TOPCOUNTWorks as $key => $value) {
                TOPCOUNTWork::where('id', $value)->delete();
            }
        }
        $TOPCOUNTWorks = TOPCOUNTWork::with(
            'categories:id,category_id,TOPCOUNTWork_id',
            'categories.category:id,category',
            'user:id,username'
        )
            // ->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        $success = true;
        return redirect()->route('admin.TOPCOUNTWork.index');
    }

    public function edit($id)
    {
        $top = TopCount::first();
        return Inertia::render('Admin/TOPCOUNT/Edit',compact('top'));
    }

    public function update(Request $request)
    {
        $TOPCOUNTWork  = TopCount::first();
        $TOPCOUNTWork->description_1  = $request->description_1;
        $TOPCOUNTWork->description_2  = $request->description_2;
        $TOPCOUNTWork->type_1  = $request->type_1;
        $TOPCOUNTWork->type_3  = $request->type_3;
        $TOPCOUNTWork->type_2  = $request->type_2;
        $TOPCOUNTWork->image  = $request->image_id;
        $TOPCOUNTWork->save();
    }
}
