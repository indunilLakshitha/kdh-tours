<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\CourseCategory;
use App\Models\Vedio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session as FacadesSession;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{

    public function index()
    {
        if (Auth::guard('admin_users')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return Inertia::render('Admin/Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ],[
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.required' => 'IDは必須項目です。',
            'password.required' => 'パスワードは必須項目です。'
        ]);

        $user = AdminUser::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'invalid' => ['ユーザーネームまたはパスワードが間違っています。'],
            ]);
        } else {
            Auth::guard('admin_users')->attempt(['email' => $request->email, 'password' => $request->password]);

            return redirect()->route('admin.dashboard');
        }
    }

    public function logout()
    {

        FacadesSession::flush();
        Auth::logout();

        return redirect()->route('admin.login.index');
    }

    public function dashboard()
    {
        // return Inertia::render('Admin/Course/CourseRegister');
        return Inertia::render('Admin/Dashboard');

    }
    public function courseIndex()
    {
         $course_categories = CourseCategory::select('id','name')->get();
        $vedios = Vedio::all();

        return Inertia::render('Admin/Course/CourseRegister',[
            'course_categories' =>$course_categories,
            'vedios' =>$vedios,
        ]);
        // return Inertia::render('Admin/Dashboard');

    }
}
