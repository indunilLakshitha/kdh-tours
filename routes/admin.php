<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseVideosController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\ImageController as AdminImageController;
use App\Http\Controllers\Admin\LearningPathController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\LicenceController;
use App\Http\Controllers\Admin\WorkshopController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\Section\HowItController;
use App\Http\Controllers\Section\QnAController;
use App\Http\Controllers\Section\TopCountController;
use App\Models\Section\HowItWork;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/login', [AdminAuthController::class, 'index'])->name('admin.login.index');
Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.login');
Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('auth:admin_users', 'check_admin_ip')->group(function () {

    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');

    Route::post('/image', [AdminImageController::class, 'upload'])->name('image.upload');

    Route::group(['prefix' => 'qna'], function () {
        Route::get('/', [QnAController::class, 'index'])->name('admin.qna.index');
        Route::get('/create', [QnAController::class, 'create'])->name('admin.qna.create');
        Route::post('/store', [QnAController::class, 'store'])->name('admin.qna.store');
        Route::get('/{id}/edit', [QnAController::class, 'edit'])->name('admin.qna.edit');
        Route::post('/update', [QnAController::class, 'update'])->name('admin.qna.update');
        Route::post('/delete', [QnAController::class, 'deleteNotices'])->name('admin.qna.deleteNotices');
    });

    Route::group(['prefix' => 'how'], function () {
        Route::get('/', [HowItController::class, 'index'])->name('admin.how.index');
        Route::get('/create', [HowItController::class, 'create'])->name('admin.how.create');
        Route::post('/store', [HowItController::class, 'store'])->name('admin.how.store');
        Route::get('/{id}/edit', [HowItController::class, 'edit'])->name('admin.how.edit');
        Route::post('/update', [HowItController::class, 'update'])->name('admin.how.update');
        Route::post('/delete', [HowItController::class, 'deleteNotices'])->name('admin.how.deleteNotices');
    });

    Route::group(['prefix' => 'top'], function () {
        Route::get('/', [TopCountController::class, 'index'])->name('admin.top.index');
        Route::get('/create', [TopCountController::class, 'create'])->name('admin.top.create');
        Route::post('/store', [TopCountController::class, 'store'])->name('admin.top.store');
        Route::get('/{id}/edit', [TopCountController::class, 'edit'])->name('admin.top.edit');
        Route::post('/update', [TopCountController::class, 'update'])->name('admin.top.update');
        Route::post('/delete', [TopCountController::class, 'deleteNotices'])->name('admin.top.deleteNotices');
    });

    Route::group(['prefix' => 'destination'], function () {
        Route::get('/', [DestinationController::class, 'index'])->name('admin.destination.index');
        Route::get('/create', [DestinationController::class, 'create'])->name('admin.destination.create');
        Route::post('/store', [DestinationController::class, 'store'])->name('admin.destination.store');
        Route::get('/{id}/edit', [DestinationController::class, 'edit'])->name('admin.destination.edit');
        Route::post('/update', [DestinationController::class, 'update'])->name('admin.destination.update');
        Route::post('/delete', [DestinationController::class, 'deleteNotices'])->name('admin.destination.deleteNotices');
    });

    

});