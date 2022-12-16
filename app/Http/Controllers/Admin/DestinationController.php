<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::all();

        return Inertia::render('Admin/DESTINATION/List', compact('destinations'));
    }

    public function create()
    {
        return Inertia::render('Admin/DESTINATION/Create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $DestinationWork  = Destination::first();
            $DestinationWork->description_1  = $request->description_1;
            $DestinationWork->description_2  = $request->description_2;
            $DestinationWork->type_1  = $request->type_1;
            $DestinationWork->type_3  = $request->type_3;
            $DestinationWork->type_2  = $request->type_2;
            $DestinationWork->image  = $request->image_id;
            $DestinationWork->save();

            DB::commit();

            return Redirect::route('admin.top.index');
        } catch (Exception $e) {

            DB::rollBack();
        }
    }



    public function deleteDestinationWorks(Request $request)
    {
        if (sizeof($request->selected_DestinationWorks) > 0) {

            foreach ($request->selected_DestinationWorks as $key => $value) {
                DestinationWork::where('id', $value)->delete();
            }
        }
        $DestinationWorks = DestinationWork::with(
            'categories:id,category_id,DestinationWork_id',
            'categories.category:id,category',
            'user:id,username'
        )
            // ->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        $success = true;
        return redirect()->route('admin.DestinationWork.index');
    }

    public function edit($id)
    {
        $top = Destination::first();
        return Inertia::render('Admin/Destination/Edit',compact('top'));
    }

    public function update(Request $request)
    {
        $DestinationWork  = Destination::first();
        $DestinationWork->description_1  = $request->description_1;
        $DestinationWork->description_2  = $request->description_2;
        $DestinationWork->type_1  = $request->type_1;
        $DestinationWork->type_3  = $request->type_3;
        $DestinationWork->type_2  = $request->type_2;
        $DestinationWork->image  = $request->image_id;
        $DestinationWork->save();
    }
}
