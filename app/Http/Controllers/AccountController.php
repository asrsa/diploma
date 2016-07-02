<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Subscription;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function __construct() {
        $this->middleware(['web', 'auth', 'user']);
    }

    public function index() {
        $user = Auth::user();

        if(isset($user->avatar)) {
            if(Storage::disk('avatars')->exists($user->avatar)) {
                $img = $user->avatar;
            }
            else {
                $img = Config::get('constants.AVATAR_DEFAULT');
            }
        }
        else {
            $img = Config::get('constants.AVATAR_DEFAULT');
        }

        return view('account\settings', ['img_name' => $img]);
    }

    public function avatarChangeGet() {
        $user = Auth::user();

        if(isset($user->avatar)) {
            if(Storage::disk('avatars')->exists($user->avatar)) {
                $img = $user->avatar;
            }
            else {
                $img = Config::get('constants.AVATAR_DEFAULT');
            }
        }
        else {
            $img = Config::get('constants.AVATAR_DEFAULT');
        }

        return view('account\changeAvatar', ['img_name' => $img]);
    }

    //upload nove avatar slike
    public function avatarChange(Request $request) {
        $user = Auth::user();

        $this->validate($request, array(
            'image' => 'required|mimes:jpeg,png,bmp,gif|max:100'
        ));

        $img = $request->file('image');

        $imgPath = $img->getRealPath();
        $imgExt = $img->getClientOriginalExtension();

        $hashed = hash('md5', $user->email);
        $newName = $hashed.'.'.$imgExt;

        //$img->move('avatars', $newName);
        //upload file
        Storage::disk('avatars')->put(
            $newName,
            file_get_contents($img->getRealPath())
        );


        $user->avatar = $newName;
        $user->save();

        return redirect()->route('changeAvatar')->withErrors(['success' => trans('views\accountPage.avatarChanged')]);
    }

    //password resets
    public function showReset() {
        return view('account.resetPassword');
    }

    //reset password
    public function resetPassword(Request $request) {
        $user = Auth::user();

        $this->validate($request, array(
            'password' => 'required|min:6|confirmed',
        ));

        $user->forceFill(array(
            'password' => bcrypt($request->input('password'))
        ))->save();

        return Redirect::route('account')->withErrors(['success' => trans('views\accountPage.passwordResetSuccess')]);
    }



    //post comment
    public function postComment(Request $request) {

        if($request->ajax()) {
            $body = $request->input('body');

            if(strlen($body) > 255) {
                return response()->json(['return' => 'commentLong',
                ]);
            }

            else if(strlen($body) <= 255) {
                $news = $request->input('news');
                $user = $request->user()->id;

                $comment = new Comment();
                $comment->body = $body;
                $comment->user_id = $user;
                $comment->news_id = $news;

                $comment->save();

                return response()->json(['return' => 'success',
                    'comment' => $comment,
                    'user' => $comment->user
                ]);
            }
        }
    }

    public function ajaxSubscribe(Request $request) {
        if($request->ajax()) {
            $user = Auth::user();
            $catId = intval($request->input('cat'));
            $subVal = intval($request->input('sub'));

            $sub = Subscription::where('user_id', '=', $user->id)
                ->where('category_id', '=', $catId)
                ->first();

            if($sub == null) {
                $sub = new Subscription();
                $sub->user_id = $user->id;
                $sub->category_id = $catId;
                $sub->subscribed = $subVal;

                $save = $sub->save();

                if($save) {
                    $result = array('success');
                }
                else {
                    $result = array('fail');
                }
            }
            else {
                $sub->subscribed = $subVal;
                $save = $sub->save();

                if($save) {
                    $result = array('success');
                }
                else {
                    $result = array('fail');
                }
            }


            return response()->json($result);
        }
    }
}
