<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseVideosController;
use App\Http\Controllers\Admin\LearningPathController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\LicenceController;
use App\Http\Controllers\Admin\WorkshopController;
use App\Http\Controllers\ImageController;
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

    Route::group(['prefix' => 'course'], function () {
        Route::get('/', [CourseController::class, 'index'])->name('course.index');
        Route::get('/create', [CourseController::class, 'create'])->name('course.create');
        Route::post('/store', [CourseController::class, 'store'])->name('course.store');
        Route::get('/{id}/edit', [CourseController::class, 'edit'])->name('course.edit');
        Route::post('/update', [CourseController::class, 'update'])->name('course.update');
        Route::post('/document', [CourseController::class, 'documentUpolad'])->name('course.documentUpolad');
    });

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

    Route::post('/images', [ImageController::class, 'upload'])->name('image.upload');

    Route::group(['prefix' => 'notice'], function () {
        Route::get('/', [NoticeController::class, 'index'])->name('admin.notice.index');
        Route::get('/create', [NoticeController::class, 'create'])->name('admin.notice.create');
        Route::post('/store', [NoticeController::class, 'store'])->name('admin.notice.store');
        Route::get('/{id}/edit', [NoticeController::class, 'edit'])->name('admin.notice.edit');
        Route::post('/update', [NoticeController::class, 'update'])->name('admin.notice.update');
        Route::post('/delete', [NoticeController::class, 'deleteNotices'])->name('admin.notice.deleteNotices');
        Route::post('/category', [NoticeController::class, 'categoryStore'])->name('admin.notice.categoryStore');
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
