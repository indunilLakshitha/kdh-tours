<?php

use App\Http\Controllers\CompanyInChargeController;
use App\Http\Controllers\User\AuthConttroller;
use App\Http\Controllers\User\CategorySearchController;
use App\Http\Controllers\User\CourseHistoryController;
use App\Http\Controllers\User\FavouriteController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\VideoViewController;
use App\Http\Controllers\User\MyPageController;
use App\Http\Controllers\User\ProductKeyController;
use App\Http\Controllers\User\WorkshopController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use League\Flysystem\PathPrefixer;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('index');
});
