<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\CourseTrait;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use CourseTrait;

    public function test(){
      return $this->assignCourseToUser(1);
    }
}
