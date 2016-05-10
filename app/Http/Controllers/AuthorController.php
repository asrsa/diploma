<?php

namespace App\Http\Controllers;

use App\Category;
use App\Subcategory;
use Illuminate\Http\Request;

use App\Http\Requests;

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
        $body = $request->Input('body');

        dd($body);
    }
}
