<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware(['web', 'auth', 'admin']);
    }

    public function index() {
        return view('admin\settings');
    }

    public function addAuthor() {
        return view('admin\addAuthor');
    }

    public function registerAuthor(Request $request) {

        $this->validate($request, array(
            'firstName' => 'required|max:50',
            'lastName' => 'required|max:50',
            'birthday' => 'required',
            'email' => 'required|email|max:100|unique:users',
        ));

        $data = $request->input();

        $roleId = Config::get('constants.ROLE_AUTHOR');
        $randPassword = bcrypt(hash('md5', $data['email'].$data['firstName'].rand(1,999999)));
        $secretCode = $data['email'] . rand(1,9999);
        $activateToken = hash('md5', $secretCode);

        User::create([
            'email'     => $data['email'],
            'firstName' => $data['firstName'],
            'lastName'  => $data['lastName'],
            'password'  => bcrypt($randPassword),
            'birthday'  => $data['birthday'],
            'avatar'    => Config::get('constants.AVATAR_DEFAULT'),
            'activate_token' => $activateToken,
            'active'    => 0,
            'role_id'   => $roleId
        ]);

        $this->sendActivation($data['email'], $data['firstName'], $activateToken);

        return Redirect::route('index')->withErrors(['success' => trans('views\adminPage.addAuthorSuccess')]);
    }

    public function sendActivation($email, $name, $activationCode) {
        Mail::send('emails.authorActivate', ['activationCode' => $activationCode], function($message) use ($email, $name, $activationCode){
            $message
                ->to($email, $name)
                ->subject(trans('emails\authorActivateMail.activateAuthorAccount'));
        });
    }

    public function showResetPassword() {
        return view('admin.resetPassword');
    }
}
