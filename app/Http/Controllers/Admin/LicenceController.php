<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductKey;
use App\Models\User;
use Aws\Api\Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use PHPUnit\Exception;

class LicenceController extends Controller
{
    public function index(Request $request){
        $userList=[];
        $users = User::orderBy('created_at','DESC')->whereStatus(1)->get();
        foreach ($users as $user){
            userProductkeyExpiredCheck($user->id);
            array_push($userList,[
                'id'=>$user->id,
                'product_id'=>$user->productKey->id,
                'product_key'=>$user->productKey->product_key,
                'name'=>$user->name,
                'furigana'=>$user->name_in_furigana,
                'company_name'=>$user->company->name,
                'email'=>$user->email,
                'user_type'=>$user->user_type,
                'contract_type'=>$user->user_type==1?'担当者':'利用者',
                'product_key_status'=>$user->productKey->status,
                'proposal_status'=>$user->productKey->proposal_status,
                'payment_status'=>$user->productKey->payment_status,
                'status'=>$user->status,
                'issued_date'=>Carbon::parse($user->productKey->created_at)->format('Y/m/d'),
                'expire_date'=>Carbon::parse($user->productKey->expired_at)->format('Y/m/d'),
            ]);
        }
        return Inertia::render('Admin/LicenseKey/LicenseKeyList',[
            'users'=>$userList
        ]);
    }

    public function edit(Request $request){
        $user=User::whereId($request->id)->whereStatus(1)->first();
        $data=[
            'id'=>$user->id,
            'product_id'=>$user->productKey->id,
            'product_key'=>$user->productKey->product_key,
            'name'=>$user->name,
            'nick_name'=>$user->nick_name,
            'furigana'=>$user->name_in_furigana,
            'company_name'=>$user->company->name,
            'email'=>$user->email,
            'position'=>$user->user_type==1?'担当者':'利用者',
            'contract_type'=>$user->user_type==1?'担当者':'利用者',
            'product_key_status'=>$user->productKey->status,
            'proposal_status'=>$user->productKey->proposal_status,
            'payment_status'=>$user->productKey->payment_status,
            'status'=>$user->status,
            'contact'=>$user->productKey->contact,
            'proposal_file_name'=>$user->productKey->proposal_file_name,
            'issued_date'=>Carbon::parse($user->productKey->created_at)->format('Y/m/d'),
            'expire_date'=>Carbon::parse($user->productKey->expired_at)->format('Y/m/d'),
        ];
        return Inertia::render('Admin/LicenseKey/LicenseKeyDetails',[
            'user'=>$data
        ]);
    }

    public function update(Request $request){
        try {
            DB::beginTransaction();
            $product_key=ProductKey::whereId($request->product_id)->first();
            if(!$product_key){
                return redirect()->back();
            }
                    if($request->status=='Force-Stopped'){
                        $url=null;
                        if($request->file('file')!=null) {

                            $file = $request->file('file');
                            $name = $file->getClientOriginalName();
                            // $name =  $file->getClientOriginalName().'-'.time() ;
                            $filePath = 'files/proposal/' . $product_key->id . '/' . $name;
                            Storage::disk('s3')->put($filePath, file_get_contents($file));

                            $url = Storage::disk('s3')->url($filePath);
                        }
                        $product_key->update([
                            'payment_status'=>$request->payment_status,
                            'proposal_status'=>$request->proposal,
                            'status'=>3,
                            'contact'=>$request->contact,
                            'proposal_file'=>$url!=null? $url:$product_key->proposal_file,
                            'proposal_file_name'=>$url!=null? $name:$product_key->proposal_file_name
                        ]);
                    }
                    else{

                        $url=null;

                        if($request->file('file')!=null){
                            $file = $request->file('file');
                            $name =  $file->getClientOriginalName();
                            // $name =  $file->getClientOriginalName().'-'.time() ;
                            $filePath = 'files/proposal/' . $product_key->id . '/' .  $name;
                            Storage::disk('s3')->put($filePath, file_get_contents($file));

                            $url = Storage::disk('s3')->url($filePath);
                        }

                        if($product_key->user->user_type==1){
                            $product_key->update([
                                'payment_status'=>$request->payment_status,
                                'proposal_status'=>$request->proposal,
                                'status'=>1,
                                'contact'=>$request->contact,
                                'proposal_file'=>$url!=null? $url:$product_key->proposal_file,
                                'proposal_file_name'=>$url!=null? $name:$product_key->proposal_file_name
                            ]);
                        }else{
                            $product_key->update([
                                'payment_status'=>$request->payment_status,
                                'proposal_status'=>$request->proposal,
                                'status'=>4,
                                'contact'=>$request->contact,
                                'proposal_file'=>$url!=null? $url:$product_key->proposal_file,
                                'proposal_file_name'=>$url!=null? $name:$product_key->proposal_file_name
                            ]);
                        }
                    }
                    DB::commit();
            return redirect()->route('admin.lisensekey.list');
        }catch (Exception $th){
            DB::rollBack();
            throw ValidationException::withMessages([
                'message' => __('messages.validation.common_error'),
                //  'message' =>$throwable->getMessage(),
            ]);
        }
    }
}
