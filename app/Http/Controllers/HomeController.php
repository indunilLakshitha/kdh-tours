<?php

namespace App\Http\Controllers;

use App\Models\Section\TopCount;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $top = TopCount::with('file')->first();
        return view('index',compact('top'));
    }
}
