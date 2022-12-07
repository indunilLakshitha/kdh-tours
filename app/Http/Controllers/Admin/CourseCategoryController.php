<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    public function store(Request $request)
    {
        $category = CourseCategory::where('name',$request->category_name)->first();

        if(!isset($category)){
            $category = new CourseCategory();
            $category->name = $request->category_name;
            $category->save();
        }
        $categories = CourseCategory::all();

        return response()->json([$categories],200);
    }
}
