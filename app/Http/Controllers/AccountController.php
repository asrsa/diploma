<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AccountController extends Controller
{
    public function __construct() {
        $this->middleware('user');
    }

    public function index() {
        $user = Auth::user();
        $img  = isset($user->avatar)? $user->avatar : Config::get('constants.AVATAR_DEFAULT');

        return view('account\settings', ['img_name' => $img]);
    }

    public function avatarChange(Request $request) {
        dd($request->input());
    }
}
