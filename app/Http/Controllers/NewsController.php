<?php

namespace App\Http\Controllers;

use App\News;
use Illuminate\Http\Request;

use App\Http\Requests;

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

        return view('news\individualNews', ['news' => $news]);
    }
}
