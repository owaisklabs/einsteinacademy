<?php

namespace App\Http\Controllers;

use App\Models\PrivacyPolicy;
use App\Models\TermAndCondition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user =  User::all();
        // dd($user);
        return view('admin.user.user',get_defined_vars());
    }
    public function termsAndCondition()
    {
         $term_condition = TermAndCondition::first();
        if (Auth::user()->type == 'Admin')
        return view('admin.terms_condition',compact('term_condition'));
        else
        return $this->formatResponse('success','privacy-policy-get',$term_condition);
    }
    public function privacyPolicy()
    {
        $privacyPolicy = PrivacyPolicy::first();
        if (Auth::user()->type == 'Admin')
        return view('admin.privacy_policy',compact('privacyPolicy'));
        else
        return $this->formatResponse('success','privacy-policy-get',$privacyPolicy);

    }
    public function termsAndConditionStore(Request $request)
    {
//        return $request->all();
        $terms_condtion =  TermAndCondition::find(1);
        $terms_condtion->description = $request->terms_and_condition;
        $terms_condtion->save();
        return redirect()->back();
    }
    public function privacyPolicyStore(Request $request)
    {
//        return $request->all();
        $Privacy_policy =  PrivacyPolicy::find(1);
        $Privacy_policy->description = $request->privacy_policy;
        $Privacy_policy->save();
        return redirect()->back();
    }




}
