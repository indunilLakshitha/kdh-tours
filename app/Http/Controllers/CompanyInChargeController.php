<?php

namespace App\Http\Controllers;

use App\Http\Traits\CourseTrait;
use App\Mail\VerificationMail;
use App\Models\Industry;
use App\Models\Invitation;
use App\Models\Occupation;
use App\Models\Position;
use App\Models\Prefecture;
use App\Models\ProductKey;
use App\Models\User;
use App\Models\UserCompanyDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CompanyInChargeController extends Controller
{

    use CourseTrait;

    function hrVerificationEmailSent(Request $request)
    {
        $rules = [
            'email' => 'required|email'
        ];
        $validator = Validator::make($request->all(), $rules, $messages = [
            'email.required' => __('messages.validation.email.required'),
            'email.email' => __('messages.validation.email.invalid'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::whereEmail($request->email)->whereStatus(1)->first();
        if ($user) {
            throw ValidationException::withMessages([
                'message' => __('messages.validation.email.unique'),
            ]);
        }

        $token = generateEmailToken('invitations');
        $invitation = Invitation::create([
            'token' => $token,
            'email' => $request->email,
            'type' => 1,
            'company_id' => 0
        ]);

        //send email here
        $data = [
            'subject' => '【BizEyes】メールアドレスの確認  ',
            'type' => 'hr-verification',
            'content_url' => env('APP_URL') . '/verification/' . $invitation->token,
        ];
        Mail::to($request->email)->send(new VerificationMail($data));

        return redirect()->route('email.verification-sent');
    }

    function emailSentView()
    {
        return Inertia::render('HR/VerifyEmail');
    }


    function emailUrlLink($token)
    {

        $invitation = Invitation::whereToken($token)->whereStatus(1)->whereType(1)->first();
        if (!$invitation) {
            return Inertia::render('User/LinkExpired', [
                'errors.message' => __('messages.validation.invalid_token.expired')
            ]);
            return redirect()->route('email.verification')->withErrors(['message' => __('messages.validation.invalid_token.expired')]);
        }
        return Inertia::render('HR/HrRegister1', [
            'email' => $invitation->email
        ]);
    }

    function inChargePersonRegister1(Request $request)
    {

        try {
            $rules = [
                'name' => 'required',
                'email' => 'required|email',
                'furigana' => 'required',
                'nick_name' => 'required',
                'password' => 'required|min:6|max:15|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-.]).{6,}$/',
                'confirm_password' => 'required|same:password',
            ];
            $validator = Validator::make($request->all(), $rules, $messages = [
                'name.required' => __('messages.validation.name.required'),
                'email.required' => __('messages.validation.email.required'),
                'email.email' => __('messages.validation.email.invalid'),
                'furigana.required' => __('messages.validation.furigana.required'),
                'nick_name.required' => __('messages.validation.nick_name.required'),
                'password.required' => __('messages.validation.password.required'),
                'password.regex' => __('messages.validation.password.regex'),
                'password.min' => __('messages.validation.password.regex'),
                'password.max' => __('messages.validation.password.regex'),
                'confirm_password.required' => __('messages.validation.password_confirmation.required'),
                'confirm_password.same' => __('messages.validation.password_confirmation.confirmed'),
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = User::whereEmail($request->email)->whereStatus(1)->first();
            if ($user) {
                return Inertia::render('User/LinkExpired', [
                    'errors.message' => __('messages.validation.email.unique')
                ]);
                return redirect()->route('email.verification')->withErrors(['message' => __('messages.validation.invalid_token.expired')]);
            }

            $invitation = Invitation::whereEmail($request->email)->whereStatus(1)->whereType(1)->first();
            if (!$invitation) {
                return Inertia::render('User/LinkExpired', [
                    'errors.message' => __('messages.validation.invalid_token.expired')
                ]);
                return redirect()->route('email.verification')->withErrors(['message' => __('messages.validation.invalid_token.expired')]);
            }
            DB::beginTransaction();
            $data = [
                'name' => $request->name,
                'name_in_furigana' => $request->furigana,
                'nick_name' => $request->nick_name,
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'company_id' => 0,
                'industry_id' => 0,
                'occupation' => 0,
                'user_type' => 1,
                'position' => 0,
                'dob' => Carbon::now(),
            ];

            $user_found = User::whereEmail($request->email)->whereStatus(0)->first();
            if (!$user_found) {
                $user_found = User::create($data);
            } else {
                $user_found->update($data);
            }
            DB::commit();
            return redirect()->route('hr.register2', ['user_id' => $user_found->id, 'email' => $user_found->email]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw ValidationException::withMessages([
                // 'message' => __('messages.validation.common_error'),
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function registerView2(Request $request)
    {
        return Inertia::render('HR/HrRegister2', [
            'positions' => Position::orderBy('id', 'ASC')->get(),
            'occupations' => Occupation::orderBy('id', 'ASC')->get(),
            'industries' => Industry::orderBy('id', 'ASC')->get(),
            'prefectures' => Prefecture::orderBy('id', 'ASC')->get(),
            'user_id' => $request->user_id, 'email' => $request->email
        ]);
    }

    function emailVerificationView(Request $request)
    {
        return Inertia::render('HR/EmailVerification');
    }


    function registerView1(Request $request)
    {
        return Inertia::render('HR/HrRegister1', [
            'user_id' => $request->user_id,
            'email' => $request->email,
        ]);
    }

    function inChargePersonRegister2(Request $request)
    {
        $rules = [
            'company_name' => 'required',
            'birth_year' => 'required',
            'industry' => 'required|exists:industries,id',
            'occupation' => 'required|exists:occupations,id',
            'position' => 'required|exists:positions,id',
            'post_code' => 'nullable|max:7|min:7|regex:/[0-9]{7}$/',
            'checked' => 'in:1',
            'telephone_no' => 'nullable|max:10|min:10|regex:/[0-9]{10}$/'
        ];
        $validator = Validator::make($request->all(), $rules, $messages = [
            'company_name.required' => __('messages.validation.company_name.required'),
            'birth_year.required' => __('messages.validation.birth_year.required'),
            'birth_year.date' => __('messages.validation.birth_year.date'),
            'industry.required' => __('messages.validation.industry.required'),
            'industry.exists' => __('messages.validation.industry.exist'),
            'occupation.required' => __('messages.validation.occupation.required'),
            'occupation.exists' => __('messages.validation.occupation.exist'),
            'position.required' => __('messages.validation.position.required'),
            'position.exists' => __('messages.validation.position.exist'),
            //'post_code.size' => __('messages.validation.post_code.size'),
            'post_code.max' => __('messages.validation.post_code.max'),
            'post_code.min' => __('messages.validation.post_code.max'),
            'post_code.regex' => __('messages.validation.post_code.max'),
            'checked.in' => __('messages.validation.is_agree.in'),
            'telephone_no.max' => __('messages.validation.telephone_no.regex'),
            'telephone_no.min' => __('messages.validation.telephone_no.min'),
            'telephone_no.regex' => __('messages.validation.telephone_no.regex')
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $user = User::whereId($request->user_id)->whereStatus(0)->whereEmail($request->email)->first();
            if (!$user) {
                return redirect()->route('hr.login', ['alert' => __('messages.alert.employee-register')]);
            }

            //prefecture
            $prefecture = Prefecture::whereId($request->prefecture)->first();

            // register company
            $company = UserCompanyDetails::create([
                'user_id' => $user->id,
                'name' => $request->company_name,
                'industry_id' => $request->industry,
                'prefecture_id' => $prefecture != null ? $prefecture->id : null,
                'tp_no' => $request->telephone_no,
                'post_code' => $request->post_code,
                'address_line_1' => $request->address_1,
                'address_line_2' => $request->address_2,
                'building_name' => $request->building_name,
            ]);

            $user->update([
                'company_id' => $company->id,
                'industry_id' => $request->industry,
                'occupation' => $request->occupation,
                'position' => $request->position,
                'dob' => Carbon::parse($request->birth_year),
                'status' => 1
            ]);

            $this->assignCourseToUser($user->id, [], 'ALL');

            $invitation = Invitation::whereEmail($user->email)->whereStatus(1)->delete();

            DB::commit();
            return redirect()->route('hr.register.success');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw ValidationException::withMessages([
                // 'message' => __('messages.validation.common_error'),
                'message' => $th->getMessage(),
            ]);
        }

    }

    function registerEmailView(Request $request)
    {
        return Inertia::render('HR/RegisterEmails', [
            'current_user' => auth()->user()->email,
            'email_list' => $request->emails
        ]);
    }

    function ConfirmEmailView(Request $request)
    {
        $i = 0;
        $emailList = [];
        if ($request->emails != null) {
            foreach ($request->emails as $email) {
                if (isset($email['value'])) {
                    if (!(filter_var($email['value'], FILTER_VALIDATE_EMAIL))) {
                        throw ValidationException::withMessages([
                            'emails.' . $i => __('messages.validation.email.invalid'),
                        ]);
                    }
                    $users = User::whereEmail($email['value'])->first();
                    if ($users) {
                        throw ValidationException::withMessages([
                            'emails.' . $i => __('messages.validation.email.unique'),
                        ]);
                    }
                    $i++;
                    array_push($emailList, $email['value']);
                }
            }
        }

        return Inertia::render('HR/RegisterEmailsConfirm', [
            'current_user' => auth()->user()->email,
            'email_list' => $emailList
        ]);
    }

    function userInvitationSend(Request $request)
    {

        $rules = [
            'current_user' => 'required|email',
            'emails' => 'nullable|array',
            'emails.value' => 'nullable|email|exists:users,email'
        ];
        $validator = Validator::make($request->all(), $rules, $messages = [
            //  'emails.required' => __('messages.validation.email_list_empty'),
            'current_user.required' => __('messages.validation.email.required'),
            'emails.*.email' => __('messages.validation.email.invalid'),
            'current_user.email' => __('messages.validation.email.invalid'),
        ]);

        if ($validator->fails()) {
            return redirect()->route('hr.reg.emails')->withErrors($validator)->withInput();
        }
        try {

            DB::beginTransaction();

            if ($request->current_user != null) {

                $product_key = ProductKey::whereEmail($request->current_user)->first();
                $user_current = User::whereEmail($request->current_user)->first();
                if (!$product_key && $user_current) {
                    // need to create licence keys here
                    $licenceKey = generateLicennseKey('product_keys');
                    $product_keys = ProductKey::create([
                        'product_key' => $licenceKey,
                        'email' => $request->current_user,
                        'status' => 1,
                        'user_id' => auth()->user()->id,
                        'expired_at' => Carbon::now()->addMonths(6)
                    ]);
                }
            }

            if ($request->emails != null) {
                $emailList = array_unique($request->emails);
                foreach ($emailList as $email) {

                    $product_key = ProductKey::whereEmail($email)->first();

                    if (!$product_key) {
                        $token = generateEmailToken('invitations');
                        $invitation = Invitation::create([
                            'token' => $token,
                            'email' => $email,
                            'type' => 2,
                            'company_id' => \auth()->user()->userCompany->id,
                        ]);
                        //send email here
                        $data = [
                            'subject' => '【BizEyes】メールアドレスの確認',
                            'type' => 'employee-email',
                            'content_url' => env('APP_URL') . '/invitation/' . $invitation->token,
                        ];

                        // register user here
                        $user = User::create([
                            'email' => $email,
                            'status' => 2,
                            'company_id' => \auth()->user()->userCompany->id,
                        ]);

                        // need to create licence keys here
                        $licenceKey = generateLicennseKey('product_keys');
                        $product_keys = ProductKey::create([
                            'product_key' => $licenceKey,
                            'email' => $invitation->email,
                            'status' => 1,
                            'token' => $token,
                            'user_id' => $user->id,
                            'expired_at' => Carbon::now()->addMonths(6)
                        ]);
                        Mail::to($email)->send(new VerificationMail($data));
                    }
                }
            }

            DB::commit();

            return redirect()->route('hr.email.complete');
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //  'message' =>$throwable->getMessage(),
            ]);
        }
    }

    function registerEmailConfirmView(Request $request)
    {
        return Inertia::render('HR/RegisterEmailsConfirm');
    }

    function registerEmailSuccessView(Request $request)
    {
        return Inertia::render('HR/EmployeEmailRegisterSuccess');
    }

    function userInvitationAccept($token)
    {

        // search invitation token here
        $invitation = Invitation::whereToken($token)->whereStatus(1)->first();
        if (!$invitation) {
            return Inertia::render('HR/LinkExpired', [
                'errors.message' => __('messages.validation.invalid_token.expired')
            ]);
        }

        $product_keys = ProductKey::whereEmail($invitation->email)->whereToken($token)->first();
        if ($product_keys != null) {
            $product_keys->update([
                'status' => 1
            ]);

            $this->assignCourseToUser($product_keys->user_id, [], 'ALL');
        }
        return Inertia::render('User/Register', [
            'token' => $invitation->token,
            'email' => $invitation->email,
            'positions' => Position::orderBy('name', 'ASC')->get(),
            'license_key' => $product_keys->product_key
        ]);
    }

    function employeeRegister(Request $request)
    {
        $rules = [
            'token' => 'required|exists:invitations,token',
            'name' => 'required',
            'furigana' => 'required',
            'nickname' => 'required',
            'password' => 'required|min:6|max:15|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-.]).{6,}$/',
            'confirm_password' => 'required|same:password',
            'position' => 'required|exists:positions,id',
            'birth_year' => 'required',
            'license_key' => 'required|exists:product_keys,product_key',
        ];
        $validator = Validator::make($request->all(), $rules, $messages = [
            'token.required' => 'Token Invalid',
            'name.required' => __('messages.validation.name.required'),
            'furigana.required' => __('messages.validation.furigana.required'),
            'nickname.required' => __('messages.validation.nick_name.required'),
            'password.required' => __('messages.validation.password.required'),
            'password.regex' => __('messages.validation.password.regex'),
            'password.min' => __('messages.validation.password.regex'),
            'password.max' => __('messages.validation.password.regex'),
            'confirm_password.required' => __('messages.validation.password_confirmation.required'),
            'confirm_password.confirmed' => __('messages.validation.password_confirmation.confirmed'),
            'confirm_password.same' => __('messages.validation.password_confirmation.confirmed'),
            'position.required' => __('messages.validation.position.required'),
            'position.exists' => __('messages.validation.position.exist'),
            'birth_year.required' => __('messages.validation.birth_year.required'),
            'birth_year.date' => __('messages.validation.birth_year.date'),
            'license_key.required' => __('messages.validation.licence_key.required'),
            'license_key.exists' => __('messages.validation.licence_key.exist'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // invitation details search here
            $invitation = Invitation::whereToken($request->token)->whereEmail($request->email)->whereStatus(1)->whereType(2)->first();
            if (!$invitation) {
                throw ValidationException::withMessages([
                    'message' => __('messages.validation.invalid_token.expired'),
                ]);
            }
            $company = UserCompanyDetails::whereId($invitation->company_id)->first();
            $user = User::whereEmail($request->email)->whereStatus(2)->first();
            if (!$user) {
                throw ValidationException::withMessages([
                    'message' => __('messages.validation.email.invalid'),
                ]);
            }
            $data = [
                'name' => $request->name,
                'name_in_furigana' => $request->furigana,
                'nick_name' => $request->nickname,
                'password' => Hash::make($request->password),
                'company_id' => $company != null ? $company->id : 0,
                'industry_id' => $company != null ? $company->industry_id : 0,
                'occupation' => 0,
                'user_type' => 2,
                'status' => 1,
                'position' => $request->position,
                'dob' => Carbon::parse($request->birth_year)
            ];

            $user->update($data);

            $product_key = ProductKey::where('product_key', $request->license_key)->whereEmail($request->email)->whereToken($request->token)->first();
            if (!$product_key) {
                throw ValidationException::withMessages([
                    'message' => __('messages.validation.licence_key.exist'),
                ]);
            }
            $invitation->update(['status' => 2]);
            DB::commit();
            return redirect()->route('user.register.complete');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //'message' => $th->getMessage(),
            ]);
        }
    }

    function emailValidate(Request $request)
    {
        $rules = [
            'email' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules, $messages = [
            'email.required' => __('messages.validation.email.required')
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $users = User::whereEmail($request->email)->whereStatus(1)->get();
        if ($users) {
            throw ValidationException::withMessages([
                'email' => __('messages.validation.email.unique')
            ]);
        }
    }

    function employeeRegisterComplete(Request $request)
    {
        return Inertia::render('User/RegisterComplete', [
            'alert' => $request->alert
        ]);
    }

    function registerSuccess()
    {
        return Inertia::render('HR/EmailRegSuccess');
    }

    function registerEmailSuccess()
    {
        return Inertia::render('HR/EmailSuccess');
    }

    function agreementInformationList()
    {
        $userList = [];
        $users = User::whereCompanyId(auth()->user()->company_id)->get();
        foreach ($users as $user) {
            array_push($userList, [
                'id' => $user->id,
                'product_id' => $user->productKey->id,
                'product_key' => $user->productKey->product_key,
                'name' => $user->name,
                'username' => $user->name_in_furigana,
                'email' => $user->email,
                'product_key_status' => $user->productKey->status,
                'status' => $user->status,
                'is_mine' => $user->id==auth()->user()->id?1:0,
                'issued_date' => Carbon::parse($user->productKey->created_at)->format('Y/m/d'),
                'expire_date' => Carbon::parse($user->productKey->expired_at)->format('Y/m/d'),
            ]);
        }
        return Inertia::render('HR/AgreementInformationList', [
            'users' => $userList,
            'username' => \auth()->user()->name]);
    }

    function licenseKeyStop(Request $request)
    {
        foreach ($request->product_list as $id) {
            $productKey = ProductKey::whereId($id)->whereStatus(1)->first();
            if (!$productKey) {
                throw ValidationException::withMessages([
                    'message' => 'Invalid Product Key',
                ]);
            }
            if ($productKey->user_id != auth()->user()->id) {
                $productKey->update([
                    'status' => 4
                ]);
            }
        }
        return redirect()->back();
    }

    function licenseKeyResume(Request $request)
    {
        foreach ($request->product_list as $id) {
            $productKey = ProductKey::whereId($id)->whereIn('status', [0, 2, 4])->first();
            if (!$productKey) {
                throw ValidationException::withMessages([
                    'message' => 'Invalid Product Key',
                ]);
            }
            if ($productKey->user_id != auth()->user()->id) {
                if ($productKey->status == 'Expired') {
                    // need to renew
                    // extend to 6 month
                    $productKey->update([
                        'expired_at' => Carbon::now()->addMonths(6)
                    ]);
                }
                $productKey->update([
                    'status' => 1
                ]);
            }
        }
        return redirect()->back();
    }

    function resendEmailToUser(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = User::whereId($request->id)->whereStatus(2)->first();
            if (!$user) {
                throw ValidationException::withMessages([
                    'message' => 'Invalid User',
                ]);
            }
            $product_key = ProductKey::whereUserId($user->id)->whereStatus(1)->first();
            if ($product_key) {
                $token = generateEmailToken('invitations');
                $invitation = Invitation::create([
                    'token' => $token,
                    'email' => $user->email,
                    'type' => 2,
                    'company_id' => \auth()->user()->userCompany->id,
                ]);
                //send email here
                $data = [
                    'subject' => '【BizEyes】メールアドレスの確認',
                    'type' => 'employee-email',
                    'content_url' => env('APP_URL') . '/invitation/' . $invitation->token,
                ];
                $product_key->update([
                    'token' => $token
                ]);
                Mail::to($user->email)->send(new VerificationMail($data));
                DB::commit();
            } else {
                throw ValidationException::withMessages([
                    'message' => 'Invalid Product Key',
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //  'message' =>$throwable->getMessage(),
            ]);
        }
    }

}
