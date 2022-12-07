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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::prefix('')->controller(CompanyInChargeController::class)->group(function () {


    Route::get('/verification', 'emailVerificationView');
    Route::post('email-verification/submit', 'hrVerificationEmailSent')->name('hr.email-verification-submit');
    Route::get('/verification/sent', 'emailSentView')->name('email.verification-sent');

    Route::get('/verification/{email}', 'emailUrlLink')->name('email.verification-url');

    Route::get('/register/part-1', 'registerView1')->name('hr.register1');
    Route::post('register-part-1', 'inChargePersonRegister1')->name('hr.register1-submit');

    Route::get('/register/part-2', 'registerView2')->name('hr.register2');
    Route::post('register-part-2', 'inChargePersonRegister2')->name('hr.register2-submit');
    Route::get('/register/success', 'registerSuccess')->name('hr.register.success');

    Route::middleware(['auth'])->group(function () {
        Route::get('/register', 'registerEmailView')->name('hr.reg.emails');
        Route::get('/register/confirm', 'ConfirmEmailView')->name('hr.confirm.emails');

        Route::post('send-invitations', 'userInvitationSend')->name('hr.user-email-send');

        Route::get('register-emails-confirm', 'registerEmailConfirmView')->name('hr.reg.emails.confirm');
        Route::get('invitation/sent', 'registerEmailSuccessView')->name('hr.email.success');
        Route::get('invitation/sent/complete', 'registerEmailSuccess')->name('hr.email.complete');
    });

    Route::get('/verification/confirm', 'registerEmailSuccessView')->name('user-email-sent');
    Route::get('user-email-validate', 'emailValidate')->name('user-email-validate');

    // user invitation url
    Route::get('/invitation/{data}', 'userInvitationAccept')->name('invite.user-register-link');
    Route::post('/register/employee', 'employeeRegister')->name('invite.user-register');


    Route::get('/register/complete', 'employeeRegisterComplete')->name('user.register.complete');
});

Route::get('/login', [AuthConttroller::class, 'loginView'])->name('hr.login');
Route::post('/hr/login', [AuthConttroller::class, 'login'])->name('login.submit');

Route::prefix('')->controller(AuthConttroller::class)->group(function () {

    Route::get('/password-reset', 'passwordResetView')->name('user.password.reset.view');
    Route::post('password-reset', 'passwordReset')->name('user.password.reset');
    Route::post('password-change', 'passwordChange')->name('user.password.change');
    Route::get('password-reset-verify/{token}', 'passwordResetVerify')->name('user.password.reset.verify');

    Route::get('/password-reset/complete', 'passwordResetCompleteView')->name('user.password.reset.complete');
});

Route::middleware(['auth:web'])->group(function () {

    Route::prefix('')->controller(AuthConttroller::class)->group(function () {

        Route::get('/profile', 'userProfileView')->name('user.profile.view');
        Route::post('profile-submit', 'userProfileUpdate')->name('user.profile.submit');
    });
    Route::get('/home', [HomeController::class, 'index'])->name('user.home');
    Route::get('/logout', [HomeController::class, 'logout'])->name('user.logout');

    Route::prefix('/category')->group(function () {

        Route::get('', [CategorySearchController::class, 'index'])->name('user.category');
        Route::get('/search/{id}', [CategorySearchController::class, 'search'])->name('user.category.search');
        Route::get('/keyword/{keyword}', [CategorySearchController::class, 'searchByKeyword'])->name('user.category.searchByKeyword');
    });

    Route::prefix('/course')->group(function () {

        Route::get('/details/{id}', [VideoViewController::class, 'courseDetails'])->name('course.details');
        Route::get('/videosView/{id}', [VideoViewController::class, 'courseVideosView'])->name('course.courseVideosView');
        Route::get('/videosViewFull/{id}', [VideoViewController::class, 'courseVideosViewFull'])->name('course.courseVideosViewFull');
        Route::get('/navigationBar/{id}', [VideoViewController::class, 'courseNavigationBar'])->name('course.courseNavigationBar');
        Route::get('/videoPlay/{id}/{videoId}/{courseId}', [VideoViewController::class, 'videoPlay'])->name('course.videoPlay');
        Route::get('/videoFull/{id}/{videoId}/{courseId}', [VideoViewController::class, 'videoPlayFull'])->name('course.videoPlayFull');
        Route::get('/report/{courseId}', [VideoViewController::class, 'report'])->name('course.report');
        Route::get('/reportFull/{courseId}', [VideoViewController::class, 'reportFull'])->name('course.reportFull');
        Route::post('/watchTime', [VideoViewController::class, 'watchTime'])->name('course.watchTime');
        Route::post('/reportSave', [VideoViewController::class, 'reportSave'])->name('course.reportSave');
        Route::post('/reportDocumentSave', [VideoViewController::class, 'reportDocumentSave'])->name('course.reportDocumentSave');
        Route::get('/document/{courseId}', [VideoViewController::class, 'document'])->name('course.document');
        Route::get('/documentFull/{courseId}', [VideoViewController::class, 'documentFull'])->name('course.documentFull');
        Route::get('/worksheet/{courseId}', [VideoViewController::class, 'worksheet'])->name('course.worksheet');
        Route::get('/worksheetFull/{courseId}', [VideoViewController::class, 'worksheetFull'])->name('course.worksheetFull');
        Route::get('/courseComplete/{id}', [VideoViewController::class, 'courseComplete'])->name('course.courseComplete');
        Route::post('/videoRoute', [VideoViewController::class, 'videoRoute'])->name('course.videoRoute');

        Route::get('/history', [CourseHistoryController::class, 'index'])->name('history.index');
        Route::get('/details/{id}/{user_id}', [VideoViewController::class, 'courseDetailsOfUser'])->name('course.courseDetailsOfUSer');


    });

    Route::get('/my-page', [MyPageController::class, 'index'])->name('myPage.index');
    // Agreement information list
    Route::get('agreement-information-list', [CompanyInChargeController::class, 'agreementInformationList'])->name('hr.agreement.information.list');
    Route::post('licensekeys/stop', [CompanyInChargeController::class, 'licenseKeyStop'])->name('hr.license.stop');
    Route::post('licensekeys/resume', [CompanyInChargeController::class, 'licenseKeyResume'])->name('hr.license.resume');
    Route::post('resend-user-email', [CompanyInChargeController::class, 'resendEmailToUser'])->name('hr.user-email.resend');


    // license area
    Route::prefix('/license')->group(function () {
        Route::get('/', [ProductKeyController::class, 'index'])->name('license.index');
        Route::get('/confirm', [ProductKeyController::class, 'ConfirmEmailView'])->name('license.confirm');
        Route::post('/send-invitations', [ProductKeyController::class, 'userInvitationSend'])->name('license.send');
        Route::get('/completed', [ProductKeyController::class, 'registerEmailSuccess'])->name('license.complete');
    });


    // favourite area
    Route::prefix('/favourites')->group(function () {
        Route::get('/', [FavouriteController::class, 'index'])->name('favourites.index');
        Route::post('/', [FavouriteController::class, 'addToFav'])->name('favourites.addToFav');
        Route::get('/my', [FavouriteController::class, 'getMyFAvList'])->name('favourites.getMyFAvList');
    });

    // workshop area
    Route::prefix('/workshop')->group(function () {
        Route::get('/', [WorkshopController::class, 'index'])->name('workshop.index');
        Route::get('/hr', [WorkshopController::class, 'mainList'])->name('workshop.hr.list');
        Route::get('/{id}', [WorkshopController::class, 'view'])->name('workshop.view');
        Route::get('/{id}/apply/{type}', [WorkshopController::class, 'apply'])->name('workshop.apply');
        Route::get('/{id}/participants', [WorkshopController::class, 'participants'])->name('workshop.participants');
        Route::get('/{id}/edit', [WorkshopController::class, 'edit'])->name('workshop.edit');
        Route::get('/hr/{id}', [WorkshopController::class, 'mainEdit'])->name('workshop.hr.edit');
        Route::post('/assignments', [WorkshopController::class, 'submitAssignments'])->name('workshop.assignment');
        Route::delete('/assignments/{id}', [WorkshopController::class, 'removeAssignment'])->name('workshop.removeAssignment');
    });
});




// Watch video (5 screens)
Route::get('/watch-video-page-1', function () {
    return Inertia::render('User/Video/VideoView1');
})->name('user.video1');

Route::get('/watch-video-page-2', function () {
    return Inertia::render('User/Video/VideoView2');
})->name('user.video2');

Route::get('/watch-video-page-3', function () {
    return Inertia::render('User/Video/VideoView3');
})->name('user.video3');

Route::get('/watch-video-page-4', function () {
    return Inertia::render('User/Video/VideoView4');
})->name('user.video4');

Route::get('/watch-video-page-5', function () {
    return Inertia::render('User/Video/VideoView5');
})->name('user.video5');

Route::get('/watch-video-page-7', function () {
    return Inertia::render('User/Video/VideoView7');
})->name('user.video7');




// Route::get('workshop/participant-list', function () {
//     return Inertia::render('Admin/Workshop/ParticipantList');
// })->name('admin.workshop.participant.list');

// Route::get('/admin/workshop', function () {
//     return Inertia::render('Admin/Workshop/WorkshopList');
// })->name('admin.workshop.list');

// Workshop
// Route::get('/workshop', function () {
//     return Inertia::render('User/Workshop/WorkshopList');
// })->name('workshop.list');

// Route::get('/workshop/details', function () {
//     return Inertia::render('User/Workshop/WorkshopDetails');
// })->name('workshop.details');

// Route::get('/workshop/complete', function () {
//     return Inertia::render('User/Workshop/WorkshopComplete');
// })->name('workshop.complete');

// Route::get('/workshop/apply', function () {
//     return Inertia::render('User/Workshop/WorkshopApply');
// })->name('workshop.apply');

Route::get('/workshops/report-submit', function () {
    return Inertia::render('User/Workshop/WorkshopReportSubmit');
})->name('workshop.report.submit');




// User notice
Route::get('/notice', function () {
    return Inertia::render('User/Notice/NoticeList');
})->name('user.notice.list');

// Admin Learning path Create & List
Route::get('/admin/learning-path/create', function () {
    return Inertia::render('Admin/LearningPath/LearningPathCreate');
})->name('admin.learning-path.create');

Route::get('/admin/learning-path', function () {
    return Inertia::render('Admin/LearningPath/LearningPathList');
})->name('admin.learning-path.list');

// HR Learning path Edit
Route::get('/HR/learning-path/edit', function () {
    return Inertia::render('HR/LearningPath/LearningPathEdit');
})->name('hr.learning-path.edit');





Route::get('/HR/attendance-status', function () {
    return Inertia::render('HR/Attendance/Status');
})->name('hr.attendance.status');
