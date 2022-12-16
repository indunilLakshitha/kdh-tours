<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseVideosController;
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

    Route::group(['prefix' => '/categories'], function () {
        Route::group(['prefix' => '/course'], function () {
            Route::post('/', [CourseCategoryController::class, 'store'])->name('course.category.store');
        });
    });

    Route::group(['prefix' => 'video'], function () {
        Route::get('/', [CourseVideosController::class, 'index'])->name('video.index');
        Route::get('/create', [CourseVideosController::class, 'create'])->name('video.create');
        Route::post('/store', [CourseVideosController::class, 'store'])->name('video.store');
        Route::get('/{id}/edit', [CourseVideosController::class, 'edit'])->name('video.edit');
        Route::post('/update', [CourseVideosController::class, 'update'])->name('video.update');
    });

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

    Route::group(['prefix' => 'workshop'], function () {
        Route::get('/', [WorkshopController::class, 'index'])->name('admin.workshop.index');
        Route::get('/create', [WorkshopController::class, 'create'])->name('admin.workshop.create');
        Route::post('/store', [WorkshopController::class, 'store'])->name('admin.workshop.store');
        Route::get('/{id}/edit', [WorkshopController::class, 'edit'])->name('admin.workshop.edit');
        Route::post('/update', [WorkshopController::class, 'update'])->name('admin.workshop.update');
        Route::post('/delete', [WorkshopController::class, 'deleteNotices'])->name('admin.workshop.deleteNotices');
        Route::post('/category', [WorkshopController::class, 'categoryStore'])->name('admin.workshop.categoryStore');
        Route::post('/thumb', [WorkshopController::class, 'uploadThumbnail'])->name('admin.workshop.thumb');
        Route::post('/document', [WorkshopController::class, 'uploadDocument'])->name('admin.workshop.document');
        Route::get('/{id}/participants', [WorkshopController::class, 'participantList'])->name('admin.workshop.participants');
    });
    Route::group(['prefix' => 'learning-path'], function () {
        Route::get('/', [LearningPathController::class, 'index'])->name('admin.learning-path.index');
        Route::get('/create', [LearningPathController::class, 'create'])->name('admin.learning-path.create');
        Route::post('/store', [LearningPathController::class, 'store'])->name('admin.learning-path.store');
        Route::get('/{id}/edit', [LearningPathController::class, 'edit'])->name('admin.learning-path.edit');
        Route::post('/update', [LearningPathController::class, 'update'])->name('admin.learning-path.update');
        Route::post('/delete', [LearningPathController::class, 'deleteNotices'])->name('admin.learning-path.deleteNotices');
        Route::post('/category', [LearningPathController::class, 'categoryStore'])->name('admin.learning-path.categoryStore');
        Route::post('/thumb', [LearningPathController::class, 'uploadThumb'])->name('admin.learning-path.thumb');
        Route::post('/document', [WorkshopController::class, 'uploadDocument'])->name('admin.learning-path.document');
        Route::get('/{id}/participants', [LearningPathController::class, 'participantList'])->name('admin.learning-path.participants');
    });

});

Route::group(['prefix' => 'licence-keys'], function () {
    Route::get('/',[LicenceController::class,'index'])->name('admin.lisensekey.list');
    Route::get('/edit',[LicenceController::class,'edit'])->name('admin.lisensekey.edit');
    Route::post('/update',[LicenceController::class,'update'])->name('admin.lisensekey.update');
});

Route::get('/test', [TestController::class, 'test']);
