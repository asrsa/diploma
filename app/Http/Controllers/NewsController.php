<?php

namespace App\Http\Controllers;

use App\Comment;
use App\News;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

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
        //$comments = Comment::where('news_id', $newsId)->get();
        $comments = DB::table('comments')
            ->select('*')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('news_id', '=', $newsId)
            ->paginate(5);


        return view('news\individualNews', ['news' => $news, 'comments' => $comments]);
    }
}
