<?php

namespace App\Http\Controllers;

use App\Category;
use App\News;
use App\Subcategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Response;
use Illuminate\Support\Facades\Redirect;

class AuthorController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'author']);
    }

    public function createNewsGet() {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('author\createNews', ['categories' => $categories, 'subcategories' => $subcategories]);
    }

    public function createNewsPost(Request $request) {
        $this->validate($request, array(
            'title' => 'required',
            'body' => 'required',
            'category'=> 'required',
            'subcategory'=> 'required',
            'image' => 'required',
        ));

        $title = $request->Input('title');
        $body = $request->Input('body');
        $subcat = $request->Input('subcategory');
        $user = $request->user()->id;
        $image = $request->Input('image');
        $hot = $request->Input('hot');

        $news = new News();
        $news->title = $title;
        $news->body = $body;
        $news->user_id = $user;
        $news->subcategory_id = $subcat;
        $news->image = $image;
        if(isset($hot)) {
            $news->hot = 1;
        }

        $news->save();

        return Redirect::route('index')->withErrors(['success' => trans('views\authorPage.newsSuccess')]);
    }

    //ajax
    public function getSubcategories(Request $request) {
        $catId = (int) $request->query('catId');
        $result = Subcategory::all()->where('category_id', $catId);

        return response()->json($result);
    }

    public function showNews(Request $request) {
        $author = $request->user()->id;

        $request->user()->isAdmin()? $author = -1: $author;

        $search = $request->input('search');
        $dateSort = $request->input('date');
        $titleSort = $request->input('title');
        $editSort = $request->input('edit');

        if(isset($dateSort)) {
            $sortIt = $dateSort;
            $sort = 'created_at';
        }
        else if(isset($titleSort)) {
            $sortIt = $titleSort;
            $sort = 'title';
        }
        else if(isset($editSort)) {
            $sortIt = $editSort;
            $sort = 'updated_at';
        }
        else {
            $sortIt = '';
            $sort = '';
        }

        if($author != -1) {
            $news = News::getAuthorNews($author, $sort, $sortIt, $search);
        }
        else {
            $news = News::getAdminNews($sort, $sortIt, $search);
        }

        foreach($news as $key => $new) {
            $date = new \DateTime($new->created_at);
            $news[$key]->created_at = $date->format('j.n.Y G:i');

            $updated = new \DateTime($new->updated_at);
            $news[$key]->updated_at = $date == $updated? '': $updated->format('j.n.Y G:i');
        }

        return view('author\showNews', ['news' => $news]);
    }

    public function editNews(Request $request, $newsId) {
        $categories = Category::all();

        $news = DB::table('news')
            ->select('news.*', 'categories.id as catId', 'categories.desc as catDesc', 'subcategories.id as subId', 'subcategories.desc as subDesc')
            ->join('subcategories', 'news.subcategory_id', '=', 'subcategories.id')
            ->join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->where('news.id', '=', $newsId)
            ->first();

        $subcategories = Subcategory::where('category_id', '=', $news->catId)->get();

        return view('author\editNews', ['news' => $news, 'categories' => $categories, 'subcategories' => $subcategories]);
    }

    public function editNewsPost(Request $request, $newsId) {
        $this->validate($request, array(
            'title' => 'required',
            'body' => 'required',
            'category'=> 'required',
            'subcategory'=> 'required',
            'image' => 'required',
        ));

        $title = $request->Input('title');
        $body = $request->Input('body');
        $subcat = $request->Input('subcategory');
        $user = $request->user()->id;
        $image = $request->Input('image');
        $hot = $request->Input('hot');

        $news = News::where('id', '=', $newsId)->first();
        $news->title = $title;
        $news->body = $body;
        $news->user_id = $user;
        $news->subcategory_id = $subcat;
        $news->image = $image;
        isset($hot)? $news->hot = 1: $news->hot = 0;

        $news->save();

        return Redirect::route('authorNews')->withErrors(['success' => trans('views\authorPage.newsEdited')]);
    }

    public function deleteNews($newsId) {
        $news = News::where('id', '=', $newsId)->first();
        $news->deleted = 1;
        $news->save();

        return Redirect::route('authorNews')->withErrors(['success' => trans('views\authorPage.newsDeleted')]);
    }

    public function showResetPassword() {
        return view('author.resetPassword');
    }

    public function resetPassword(Request $request) {
        $user = $request->user();

        $this->validate($request, array(
            'password' => 'required|min:6|confirmed',
        ));

        $user->forceFill(array(
            'password' => bcrypt($request->input('password'))
        ))->save();

        return Redirect::route('index')->withErrors(['success' => trans('views\accountPage.passwordResetSuccess')]);
    }

    public function ajaxSearchNews(Request $request) {
        $result = News::searchNews($request->input('query'), 5);

        return response()->json($result);
    }
}
