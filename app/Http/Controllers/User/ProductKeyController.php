<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Mail\VerificationMail;
use App\Models\ProductKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Invitation;

class ProductKeyController extends Controller
{
    public function index()
    {
        return Inertia::render('HR/LicenseKey/AddLicenseKey', with(['current_user' => auth()->user()->email]));
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
        return Inertia::render('HR/LicenseKey/SendLicenseKey', [
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
            return redirect()->route('license.index')->withErrors($validator)->withInput();
        }
        try {

            DB::beginTransaction();

            if ($request->current_user != null) {

                $product_key = ProductKey::whereEmail($request->current_user)->whereStatus(1)->first();
                if (!$product_key) {
                    // need to create licence keys here
                    $licenceKey = generateLicennseKey('product_keys');
                    $product_keys = ProductKey::create([
                        'product_key' => $licenceKey,
                        'email' => $request->current_user,
                        'status' => 1,
                        'user_id' => auth()->user()->id,
                        'expired_at'=>Carbon::now()->addMonths(6)
                    ]);
                }
            }

            if ($request->emails != null) {
                $emailList = array_unique($request->emails);
                foreach ($emailList as $email) {
                    $user = ProductKey::whereEmail($email)->whereStatus(1)->first();
                    if (!$user) {

                        $token = generateEmailToken('invitations');
                        $invitation = Invitation::create([
                            'token' => $token,
                            'email' => $email,
                            'type' => 2,
                            'company_id' => \auth()->user()->userCompany->id
                        ]);

                        //send email here
                        $data = [
                            'subject' => '【BizEyes】メールアドレスの確認',
                            'type' => 'employee-email',
                            'content_url' =>  env('APP_URL')  . '/invitation/' . $invitation->token,
                        ];

                        // register user here
                        $user = User::create([
                            'email' => $email,
                            'company_id' => \auth()->user()->userCompany->id,
                            'status' => 2
                        ]);

                        // need to create licence keys here
                        $licenceKey = generateLicennseKey('product_keys');
                        $product_keys = ProductKey::create([
                            'product_key' => $licenceKey,
                            'email' => $invitation->email,
                            'status' => 1,
                            'token' => $token,
                            'user_id' => $user->id,
                            'expired_at'=>Carbon::now()->addMonths(6)
                        ]);
                        Mail::to($email)->send(new VerificationMail($data));
                    }
                }
            }

            DB::commit();

            return redirect()->route('license.complete');
        } catch (\Throwable $throwable) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //  'message' =>$throwable->getMessage(),
            ]);
        }
    }

    function registerEmailSuccess()
    {
        return Inertia::render('HR/LicenseKey/LicenseKeyComplete');
    }
}
