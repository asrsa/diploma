<?php

namespace App\Http\Controllers;

use App\Category;
use App\News;
use App\Subcategory;
use Illuminate\Http\Request;

use App\Http\Requests;
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

        $news = new News();
        $news->title = $title;
        $news->body = $body;
        $news->user_id = $user;
        $news->subcategory_id = $subcat;
        $news->image = $image;

        $news->save();

        return Redirect::route('index')->withErrors(['success' => trans('views\authorPage.newsSuccess')]);
    }

    //ajax
    public function getSubcategories(Request $request) {
        $catId = (int) $request->query('catId');
        $result = Subcategory::all()->where('category_id', $catId);

        return response()->json($result);
    }
}
