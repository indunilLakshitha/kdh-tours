<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\VerificationMail;
use App\Models\Invitation;
use App\Models\Position;
use App\Models\ProductKey;
use App\Models\User;
use App\Rules\SamePasswordRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Testing\Concerns\Has;
use Laravel\Fortify\Fortify;

class AuthConttroller extends Controller
{
    function loginView(Request $request)
    {
        return Inertia::render('HR/Login', [
            'alert' => $request->alert,
            'isFirst'=>$request->isFirst
        ]);
    }

    function passwordResetView()
    {
        return Inertia::render('User/PasswordReset');
    }

    function login(Request $request)
    {
            $rules = [
                'email' => 'required|email',
                'password' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules, $messages = [
                'email.required' => __('messages.validation.email.required'),
                'email.email' => __('messages.validation.email.invalid'),
                'password.required' => __('messages.validation.password.required')
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $checkUser = User::where('email', $request->email)->where('status', 1)->first();

            if ($checkUser != null && Hash::check($request->password, $checkUser->password)) {
                // user need to check user licence key status
                userProductkeyExpiredCheck($checkUser->id);

                if ($checkUser->user_type == 1) {
                    Auth::login($checkUser);

                    // if licence key found req process completed
                    $productKey=ProductKey::whereUserId($checkUser->id)->first();
                    if($productKey){
                        if($productKey->status=='Active'){
                            // activated
                            return redirect()->route('myPage.index');
                        }else{
                            // not using
                            throw ValidationException::withMessages([
                                'message' => ['アカウントは無効です。担当者へご連絡お願い致します。'],
                            ]);
                        }
                    }
                    return redirect()->route('hr.reg.emails', ['user_email' => $checkUser->email]);
                } else {

                    // if licence key found
                    $productKey=ProductKey::whereUserId($checkUser->id)->first();
                    if($productKey){
                        if($productKey->status=='Active'){
                            // activated
                            Auth::login($checkUser);
                            return redirect()->route('user.home');
                        }else{
                            // not using
                            throw ValidationException::withMessages([
                                'message' => ['アカウントは無効です。担当者へご連絡お願い致します。'],
                            ]);
                        }
                    }
                }
            } else {
                throw ValidationException::withMessages([
                    'message' => ['メールもしくはパスワードが正しくありません。'],
                ]);
            }
    }

    function passwordReset(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];
        $validator = Validator::make($request->all(), $rules, $messages = [
            'email.required' => __('messages.validation.email.required'),
            'email.email' => __('messages.validation.email.invalid'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $checkUser = User::where('email', $request->email)->where('status', 1)->first();
            if (!$checkUser) {
                return redirect()->back()->withErrors(['message' => __('messages.validation.password_reset.user_not_found')])->withInput();
            }
            // send password reset token here

            $token = generateEmailToken('password_resets');
            $tokenCr = DB::table('password_resets')->insert([
                'email' => $checkUser->email,
                'token' => $token
            ]);

            $data = [
                'subject' => '【BizEyes】パスワード再発行',
                'type' => 'password-forget',
                'content_url' => env('APP_URL') . '/password-reset-verify/' . $token,
            ];
            Mail::to($checkUser->email)->send(new VerificationMail($data));
            return redirect()->route('hr.login');
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
               // 'message' => $th->getMessage(),
            ]);
        }
    }

    function passwordResetVerify($token)
    {
        // search invitation token here
        try {
            $invitation = DB::table('password_resets')->where('token', $token)->first();
            if (!$invitation) {
                return Inertia::render('User/LinkExpired', [
                    'errors.message' => __('messages.validation.invalid_token.expired')
                ]);
            }
            $checkUser = User::where('email', $invitation->email)->where('status', 1)->first();
            if (!$checkUser) {
                return Inertia::render('User/LinkExpired', [
                    'errors.message' => __('messages.validation.invalid_token.expired')
                ]);
            }
            return Inertia::render('User/EnterNewPassword', [
                'token' => $invitation->token,
                'email' => $checkUser->email,
            ]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                //'message' => __('messages.validation.common_error'),
                'message' => $th->getMessage(),
            ]);
        }
    }

    function passwordChange(Request $request)
    {
        $rules = [
            'password' => 'required|min:6|max:15|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirm_password' => 'required|same:password',
            'email' => 'required|email',
            'token' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules, $messages = [
            'password.required' => __('messages.validation.password.required'),
            'password.regex' => __('messages.validation.password.regex'),
            'password.min' => __('messages.validation.password.regex'),
            'password.max' => __('messages.validation.password.regex'),
            'confirm_password.required' => __('messages.validation.password_confirmation.required'),
            'confirm_password.confirmed' => __('messages.validation.password_confirmation.confirmed'),
            'confirm_password.same' => __('messages.validation.password_confirmation.confirmed'),
            'token.required' => __('messages.validation.invalid_token.expired'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $invitation = DB::table('password_resets')->where('token', $request->token)->where('email', $request->email)->first();
            if (!$invitation) {
                return redirect()->back()->withErrors([
                        'message' => __('messages.validation.invalid_token.expired')]
                )->withInput();
            }
            $user = User::whereEmail($invitation->email)->whereStatus(1)->first();
            if (!$user) {
                throw ValidationException::withMessages([
                    'message' => __('messages.validation.invalid_token.expired'),
                ]);
            }
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            DB::table('password_resets')->where('token', $request->token)->where('email', $request->email)->delete();
            return redirect()->route('hr.login');
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //'message' => $th->getMessage(),
            ]);
        }
    }

    public function passwordResetCompleteView()
    {
        return Inertia::render('User/ResetComplete');
    }

    public function userProfileView(Request $request)
    {
        $user = User::whereId(auth()->user()->id)->whereStatus(1)->first();
        if ($user) {
            $data = [
                'furigana' => $user->name_in_furigana,
                'name' => $user->name,
                'company_name' => $user->userCompany->name,
                'position' => $user->position,
                'email' => $user->email,
                'nick_name' => $user->nick_name,
                'avatar' => $user->avatar,
                'profile_photo_path' => $user->profile_photo_path,
                'user_type'=>$user->user_type
            ];

            return Inertia::render('User/Profile/Profile', [
                'positions' => Position::all(),
                'user_data' => $data,
            ]);
        }

    }

    public function userProfileUpdate(Request $request){
        $request->validate([
            'nickname' => 'required',
            'name' => 'required',
            'position' => 'required|exists:positions,id',
            'furigana' => 'required',
            'password' => 'nullable|min:6|max:15|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-.]).{6,}$/',
            'confirm_password' => 'same:password',
            'image' => 'nullable|mimes:jpeg,png,jpg,svg,gif|image|max:10200',
        ],[
            'nickname.required' => __('messages.validation.nick_name.required'),
            'name.required' => __('messages.validation.name.required'),
            'furigana.required' => __('messages.validation.furigana.required'),
            'password.regex' => __('messages.validation.password.regex'),
            'password.min' => __('messages.validation.password.regex'),
            'password.max' => __('messages.validation.password.regex'),
            'position.required' => __('messages.validation.position.required'),
            'position.exists' => __('messages.validation.position.exist'),
            'password.required' => __('messages.validation.password.required'),
            'password.regex' => __('messages.validation.password.regex'),
            'password.min' => __('messages.validation.password.regex'),
            'password.max' => __('messages.validation.password.regex'),
            'confirm_password.required' => __('messages.validation.password_confirmation.required'),
            'confirm_password.same' => __('messages.validation.password_confirmation.confirmed'),
            'image.mimes' => __('messages.validation.image.type'),
            'image.size' => __('messages.validation.image.size_10'),
        ]);

        if(\auth()->user()->user_type==1){
            $request->validate([
                    'company_name' => 'required']
                ,[
                    'company_name.required' => __('messages.validation.company_name.required'),
                ]);
        }
        if(Hash::check($request->password,auth()->user()->password)){
            throw ValidationException::withMessages([
                'password' =>'新しいパスワードは現在と同じです。',
            ]);
        }
        try {
            DB::beginTransaction();
            // image upload

            $url=null;
            if($request->image!=null){
                $url=uploadImage($request);
            }
            $user=User::whereId(auth()->user()->id)->whereStatus(1)->first();

            if (!$user) {
                throw ValidationException::withMessages([
                    'message' => __('messages.validation.common_error'),
                ]);
            }
            $data=[
                'nick_name'=>$request->nickname,
                'name_in_furigana'=>$request->furigana!=null?$request->furigana:$user->name_in_furigana,
                'name'=>$request->name,
                'password'=>$request->password!=null?Hash::make($request->password):$user->password,
                'position'=>$request->position,
                'avatar'=>$url!=null?$url:$user->avatar
            ];

            // user company update
            if(\auth()->user()->user_type==1 && $request->company_name!=null){
                $user->userCompany->update([
                'name'=>$request->company_name
                ]);
            }
            $user->update($data);
            DB::commit();
            return redirect()->route('myPage.index',
                ['message'=>'プロフィールを更新しました。','alertStatus'=>'visible']);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw ValidationException::withMessages([
                //'message' => __('messages.validation.common_error'),
                'message' => $th->getMessage(),
            ]);
        }
    }
}
