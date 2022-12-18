<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DestinationController extends Controller
{
    public function index()
    {
         $destinations = Destination::with(
            'imageOne',
            'imageTwo',
            'imageThree',
            'imageFour',
            'imageFive'
        )
            ->get();

        return Inertia::render('Admin/DESTINATION/List', compact('destinations'));
    }

    public function create()
    {
        return Inertia::render('Admin/DESTINATION/Create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // try {
        //     DB::beginTransaction();
        $destination  = new Destination();
        $destination->img_1  = isset($request->image_id[0]) ? $request->image_id[0] : 0;
        $destination->img_2  = isset($request->image_id[1]) ? $request->image_id[1] : 0;
        $destination->img_3  = isset($request->image_id[2]) ? $request->image_id[2] : 0;
        $destination->img_4  = isset($request->image_id[3]) ? $request->image_id[3] : 0;
        $destination->img_5  = isset($request->image_id[4]) ? $request->image_id[4] : 0;
        $destination->title  = $request->title;
        $destination->location  = $request->location;
        // $Destination->status  = $request->status;
        $destination->save();

        DB::commit();

        return Redirect::route('admin.destination.index');
        // } catch (Exception $e) {

        //     DB::rollBack();
        // }
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
        return Inertia::render('Admin/Destination/Edit', compact('top'));
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
