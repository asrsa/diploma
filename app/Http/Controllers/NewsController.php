<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Like;
use App\News;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class NewsController extends Controller
{
    public function __construct() {
        $this->middleware('web');
    }

    public function index() {
        $news = News::paginate(6);

        return view('news\news', ['news' => $news]);
    }

    public function showNews($newsId) {
        $news = News::where('id', $newsId)->get()->first();

        $likeSubquery = DB::table('likes')
            ->select(DB::raw('sum(likes.value)'))
            ->from('likes')
            ->whereRaw('likes.comment_id = comments.id');

        $comments = DB::table('comments')
            ->select('comments.*', 'users.firstName', 'users.avatar',
                DB::raw('('. $likeSubquery->toSql() .') as likesSum'))
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('news_id', '=', $newsId)
            ->paginate(5);

        return view('news\individualNews', ['news' => $news, 'comments' => $comments]);
    }

    public function deleteComment() {
        $commentId  = Input::get('cid');

        $comment = Comment::where('id', $commentId)->get()->first();
        $comment->delete();

        return Redirect::back()->withErrors(['success' => trans('views\individualNews.commentDeleted')]);
    }

    public function likeComment(Request $request) {
        $type = Input::get('type');
        $commentId = Input::get('cid');
        $user = $request->user()->id;
        $return = 1;

        if($type == 'up') {
            $like = Like::where('user_id', $user)
                ->where('comment_id', $commentId)
                ->get()->first();

            if(isset($like)) {
                if((int) $like->value === 1) {
                    //already liked
                    return response()->json([
                        'return' => 4,
                        'cid'    => $commentId
                    ]);
                }
                else {
                    $value = (int) $like->value + 2;
                    $like->value = $value;
                    $like->save();

                    $return = 2;
                    $value = 2;
                }
            }
            else {
                $likeNew = new Like();
                $likeNew->user_id = $user;
                $likeNew->comment_id = $commentId;
                $likeNew->value = 1;
                $likeNew->save();

                $value = 1;
                $return = 1;
            }
        }
        else if($type == 'down') {
            $like = Like::where('user_id', $user)
                ->where('comment_id', $commentId)
                ->get()->first();

            if(isset($like)) {
                if((int) $like->value === -1) {
                    //already disliked
                    return response()->json([
                        'return' => 5,
                        'cid'    => $commentId
                    ]);
                }
                else {
                    $value = (int) $like->value - 2;
                    $like->value = $value;
                    $like->save();

                    $return = 3;
                    $value = -2;
                }
            }
            else {
                $likeNew = new Like();
                $likeNew->user_id = $user;
                $likeNew->comment_id = $commentId;
                $likeNew->value = -1;
                $likeNew->save();

                $value = -1;
                $return = 1;
            }
        }

        return response()->json([
            'return' => $return,
            'cid'    => $commentId,
            'value'  => $value
        ]);
    }
}
