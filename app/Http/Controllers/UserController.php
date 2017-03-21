<?php

namespace starcrew\Http\Controllers;

use Auth;
use Validator;
use starcrew\User;
use starcrew\Language;
use starcrew\UserLanguage;
use starcrew\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
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
     * Returns the user profile view
     *
     * @return view('user/profile') profile view
     */
    public function profile()
    {

        $languages = Language::select('id', 'name')->get();
        $userLanguages = UserLanguage::where('user_id', Auth::user()->id)->select('language_id')->get();

        return view('user/profile')->with(compact("languages", "userLanguages"));
    }

    /**
    * Updates the user's informations
    *
    * @param Request $request object containing the user's inputs
    *
    * @return redirect()->back() redirects to the last visited page (profile view)
    */
    public function update(Request $request)
    {
        extract($request->all());
        if($email != Auth::user()->email)
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255|unique:users',
                'language' => 'required',
            ]);

        }else{
            $validator = Validator::make($request->all(), [
                'language' => 'required',
            ]);
        }

        if($validator->fails()) {
            return redirect('/profile')
                    ->withErrors($validator)
                    ->withInput();
        }

        if($email != Auth::user()->email)
        {
            $user = User::find(Auth::user()->id);
            $user->email = $email;
            $user->save();
        }

        $userLanguages = UserLanguage::where("user_id", '=', Auth::user()->id)->get();
        foreach ($userLanguages as $userLanguage) {
            $found = false;
            foreach($language as $l)
            {
                if($l == $userLanguage->language_id && Auth::user()->id == $userLanguage->user_id){
                    $found == true;
                }
            }
            if(!$found)
            {
                $userLanguage->delete();
            }
        }
        foreach ($language as $l) {
            $userLanguage = UserLanguage::where("user_id", '=', Auth::user()->id)
                            ->where('language_id', '=', $l)
                            ->get()
                            ->first();

            if($userLanguage == NULL){
                $userLanguage = new userLanguage();
                $userLanguage->user_id = Auth::user()->id;
                $userLanguage->language_id = $l;
                $userLanguage->save();
            }

        }

        return redirect()->back();


    }
}
