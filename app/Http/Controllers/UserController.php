<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function changePasswordGet() {
        return view('password.resetPassword');
    }

    public function changePasswordPost(Request $request) {
        $user = $request->user();

        $this->validate($request, array(
            'password' => 'required|min:6|confirmed',
        ));

        $user->forceFill(array(
            'password' => bcrypt($request->input('password'))
        ))->save();

        return Redirect::route('index')->withErrors(['success' => trans('views\accountPage.passwordResetSuccess')]);
    }
}
