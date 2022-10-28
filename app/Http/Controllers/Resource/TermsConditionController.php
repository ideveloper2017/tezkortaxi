<?php

namespace App\Http\Controllers\Resource;

use App\AdminTerms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TermsConditionController extends Controller
{

    protected function create(Request $request)
    {
        $this->validate($request, [
                'description' => 'required',
            ]);

        $description = $request->description;
       
        $AdminTerms = new AdminTerms;

        $AdminTerms->description = $request->description;

        $AdminTerms->save();

        return redirect('admin/terms');
    }

    public function termsget()
    {
        try{
            $AdminTerms = AdminTerms::TermsList()->get();
            return $AdminTerms;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        } 
    }

    public function termsget_detail(Request $request)
    {
        $this->validate($request, [
                'request_id' => 'required|integer|exists:user_requests,id',
           ]);
        
        try{
            $AdminTerms = AdminTerms::TermsList($request->request_id)->get();
            return $AdminTerms;
        }

        catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        } 
    }

    public function terms()
    {
        $terms = $this->termsget();
        
        //$faqs_dtl = $this->faqsget_detail($request);
        return view('admin.pages.termsandcondition',compact('terms'));
    }

    public function showFAQForm()
    {
        return view('admin.pages.termsandcondition');
    }

  
}
