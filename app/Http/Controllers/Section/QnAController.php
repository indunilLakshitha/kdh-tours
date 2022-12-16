<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use App\Models\Section\QnA;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class QnAController extends Controller
{
    public function index()
    {
        $QnAs = QnA::orderBy('updated_at', 'desc')
            ->get();

        return Inertia::render('Admin/QnA/List', compact('QnAs'));
    }

    public function create()
    {
        return Inertia::render('Admin/QnA/Create');
    }

    public function store(Request $request)
    {
        try {

            DB::beginTransaction();
            $QnA  = new QnA();
            $QnA->question  = $request->question;
            $QnA->answer  = $request->answer;
            $QnA->status  = $request->status;
            $QnA->save();

            DB::commit();

            return Redirect::route('admin.qna.index');
        } catch (Exception $e) {

            DB::rollBack();
        }
    }



    public function deleteQnAs(Request $request)
    {
        if (sizeof($request->selected_QnAs) > 0) {

            foreach ($request->selected_QnAs as $key => $value) {
                QnA::where('id', $value)->delete();
            }
        }
        $QnAs = QnA::with(
            'categories:id,category_id,QnA_id',
            'categories.category:id,category',
            'user:id,username'
        )
            // ->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        $success = true;
        return redirect()->route('admin.QnA.index');
    }

    public function edit($id)
    {
        try {
            $QnA = QnA::where('id', $id)
                ->first();

            if (isset($QnA)) {
                return Inertia::render('Admin/QnA/Edit', ['qna' => $QnA]);
            }
        } catch (Exception $e) {

            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try {

            $QnA  = QnA::where('id', $request->id)->first();


            if (isset($QnA)) {

                $QnA->question  = $request->question;
                $QnA->answer  = $request->answer;
                $QnA->status  = $request->status;

                $QnA->save();
                return Redirect::route('admin.qna.index');
            }
        } catch (Exception $e) {
            return redirect()->back();
        }
    }
}
