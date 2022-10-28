<?php

namespace App\Http\Controllers\Resource;

use App\AdminFaq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FAQController extends Controller
{

    protected function create(Request $request)
    {
        $this->validate($request, [
                'question' => 'required',
                'answer' => 'required',
            ]);

        $question = $request->question;
        $answer = $request->answer;
       
        $AdminFaq = new AdminFaq;

        $AdminFaq->question = $request->question;
        $AdminFaq->answer = $request->answer;

        $AdminFaq->save();

        return redirect('admin/faq');
    }

    public function faqsget()
    {
       try{
            $AdminFaq = AdminFaq::FaqList()->get();
            return $AdminFaq;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        } 
    }

    public function faqsget_detail(Request $request)
    {
        $this->validate($request, [
                'request_id' => 'required|integer|exists:user_requests,id',
           ]);
        
        try{
            $AdminFaq = AdminFaq::FaqList($request->request_id)->get();
            return $AdminFaq;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        } 
    }

    public function faqs()
    {
        $faqs = $this->faqsget();
        
        //$faqs_dtl = $this->faqsget_detail($request);
        return view('admin.pages.faq',compact('faqs'));
    }

    public function showFAQForm()
    {
        return view('admin.pages.faq');
    }

  
}
