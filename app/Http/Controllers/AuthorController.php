<?php

namespace App\Http\Controllers;

use App\Category;
use App\News;
use App\Subcategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

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
            'body' => 'required'
        ));

        $title = $request->Input('title');
        $body = $request->Input('body');
        $subcat = $request->Input('subcategory');
        $user = $request->user()->id;

        $news = new News();
        $news->title = $title;
        $news->body = $body;
        $news->user_id = $user;
        $news->subcategory_id = $subcat;

        $news->save();

        return Redirect::route('index')->withErrors(['success' => trans('views\authorPage.newsSuccess')]);
    }

    /*public function uploadImage(Request $request) {
        $image = $request->file('image');
        $ext = $image->getClientOriginalExtension();
        $imageExtensions = array('jpg', 'bmp', 'png');

        if(!in_array($ext, $imageExtensions)) {
            return Redirect::back()->withErrors(['error' => trans('views\authorPage.wrongFileUpload')]);
        }

        $newName = hash('md5', $image->getClientOriginalName()) . '.' . $ext;

        Storage::disk('images')->put(
            $newName,
            file_get_contents($image->getRealPath())
        );

        $newPath = 'media/images/' . $newName;
    }*/
}
