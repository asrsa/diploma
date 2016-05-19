<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
        //$this->middleware('web');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstName' => 'required|max:50',
            'lastName' => 'required|max:50',
            'username' => 'required|max:50',
            'gender' => 'required',
            'birthday' => 'required',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        //$roleId = Role::where('title', 'user')->first()->id;
        $roleId = Config::get('constants.ROLE_USER');
        $secretCode = $data['email'] . rand(1,999999);
        $activateToken = hash('md5', $secretCode);

        return User::create([
            'email'     => $data['email'],
            'firstName' => $data['firstName'],
            'lastName'  => $data['lastName'],
            'username'  => $data['username'],
            'password'  => bcrypt($data['password']),
            'gender'    => $data['gender'],
            'birthday'  => $data['birthday'],
            'avatar'    => Config::get('constants.AVATAR_DEFAULT'),
            'activate_token' => $activateToken,
            'active'    => 0,
            'role_id'   => $roleId
        ]);
    }

    /**
     * Sends activation key
     *
     * @param $activationCode
     */
    protected function sendActivation($email, $name, $activationCode) {
        Mail::send('emails.activate', ['activationCode' => $activationCode], function($message) use ($email, $name, $activationCode){
            $message
                ->to($email, $name)
                ->subject(trans('emails\registerMail.activateAccount'));
        });
    }

    /**
     * Poskrbi za aktivacijo racuna
     *
     * @param $activationCode
     */
    public function activate($activationCode) {
        $user = User::where('activate_token', $activationCode)->first();

        if(!isset($user)) {
            return redirect(Config::get('paths.PATH_ROOT').'login')->withErrors(['success' => trans('errors.account_activated_fail')]);
        }
        $user->active = 1;
        $user->activate_token = null;
        $user->save();

        return redirect(Config::get('paths.PATH_ROOT').'login')->withErrors(['success' => trans('errors.account_activated')]);
    }


    /**
     * Poskrbi za aktivacijo avtorja
     *
     * @param $activationCode
     */
    public function activateAuthor($activationCode) {
        $user = User::where('activate_token', $activationCode)->first();

        if(!isset($user)) {
            return redirect(Config::get('paths.PATH_ROOT').'login')->withErrors(['success' => trans('errors.account_activated_fail')]);
        }

        return view('author\activateAccount', ['code' => $activationCode]);
    }

    public function postActivateAuthor(Request $request, $activationCode) {
        $this->validate($request, array(
            'password' => 'required|min:6|confirmed',
        ));

        $user = User::where('activate_token', $activationCode)->first();

        $user->active = 1;
        $user->activate_token = null;
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return redirect(Config::get('paths.PATH_ROOT').'login')->withErrors(['success' => trans('errors.account_activated')]);
    }
}
