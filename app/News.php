<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class News extends Model
{
    protected $table = 'news';

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function subcategory() {
        return $this->belongsTo('App\Subcategory');
    }

    public function comment() {
        return $this->hasMany('App\Comment');
    }


    public function getSubcategoryNewNews($catId) {
        $newNews = DB::table('subcategories')
            ->select('subcategories.name as subcategory', 'news.*')
            ->join('news', 'subcategories.id', '=', 'news.subcategory_id')
            ->where('subcategories.category_id', '=', $catId)
            ->where('news.deleted', '=', 0)
            ->orderBy('news.created_at', 'desc')
            ->take(5)
            ->get();

        return $newNews;
    }

    public function getSubcategoryNewsById($subcatId) {
        $news = DB::table('subcategories')
            ->select('subcategories.desc as subcategory', 'news.*')
            ->join('news', 'subcategories.id', '=', 'news.subcategory_id')
            ->where('subcategories.id', '=', $subcatId)
            ->where('news.deleted', '=', 0)
            ->orderBy('news.created_at', 'desc')
            ->take(5)
            ->get();

        return $news;
    }

    static function getSubcategoryId($catId) {
        $subId = DB::table('subcategories')
            ->select('id')
            ->where('category_id', '=', $catId)
            ->get();

        return $subId;
    }

    static function getSubcategoryNews($subcatId) {
        $news = DB::table('news')
            ->select('news.*', 'subcategories.desc as subcategory', 'subcategories.name as subcategory_name')
            ->join('subcategories', 'news.subcategory_id', '=', 'subcategories.id')
            ->where('news.subcategory_id', '=', $subcatId)
            ->where('news.deleted', '=', 0)
            ->orderBy('news.created_at', 'desc')
            ->take(3)
            ->get();

        return $news;
    }

    static function getSubcategoryNewsPaginate($subcatId) {
        $news = DB::table('news')
            ->select('news.*', 'subcategories.desc as subcategory', 'subcategories.name as subcategory_name')
            ->join('subcategories', 'news.subcategory_id', '=', 'subcategories.id')
            ->where('news.subcategory_id', '=', $subcatId)
            ->where('news.deleted', '=', 0)
            ->orderBy('news.created_at', 'desc')
            ->paginate(6);

        return $news;
    }

    static function getFiveNewNews() {
        $news = DB::table('news')
            ->select('news.*')
            ->where('news.deleted', '=', 0)
            ->orderBy('news.created_at', 'desc')
            ->take(5)
            ->get();

        return $news;
    }

    static function getAuthorNews($author, $sort, $dateSort) {

        if($sort === '') {
            $news = DB::table('news')
                ->select('*')
                ->where('user_id', '=', $author)
                ->where('news.deleted', '=', 0)
                ->orderBy('created_at', 'desc')
                ->paginate(5);
        }
        else {
            $news = DB::table('news')
                ->select('*')
                ->where('user_id', '=', $author)
                ->where('news.deleted', '=', 0)
                ->orderBy($sort, $dateSort)
                ->paginate(5);
        }

        return $news;
    }

    static function searchNews($query, $pages, $category = null) {
        if($category == null || $category == 'All') {
            $result = DB::table('news')
                ->select('news.*', 'categories.name as catName', 'categories.desc as catDesc')
                ->join('subcategories', 'news.subcategory_id', '=', 'subcategories.id')
                ->join('categories', 'subcategories.category_id', '=', 'categories.id')
                ->where('news.deleted', '=', 0)
                ->where(function($q) use ($query) {
                    $q->where('news.title', 'like', '%' . $query . '%');
                    $q->orWhere('news.body', 'like', '%' . $query . '%');
                })
                ->orderBy('news.created_at', 'desc')
                ->paginate($pages);
        }
        else if($category == 'tmp') {
            $result = DB::table('news')
                ->select('news.*', 'categories.name as catName', 'categories.desc as catDesc')
                ->join('subcategories', 'news.subcategory_id', '=', 'subcategories.id')
                ->join('categories', 'subcategories.category_id', '=', 'categories.id')
                ->where('news.deleted', '=', 0)
                ->where(function($q) use ($query) {
                    $q->where('news.title', 'like', '%' . $query . '%');
                    $q->orWhere('news.body', 'like', '%' . $query . '%');
                })
                ->orderBy('news.created_at', 'desc')
                ->get();
        }
        else if($category != null) {
            $result = DB::table('news')
                ->select('news.*', 'categories.name as catName', 'categories.desc as catDesc')
                ->join('subcategories', 'news.subcategory_id', '=', 'subcategories.id')
                ->join('categories', 'subcategories.category_id', '=', 'categories.id')
                ->where('news.deleted', '=', 0)
                ->where(function($q) use ($query) {
                    $q->where('news.title', 'like', '%' . $query . '%');
                    $q->orWhere('news.body', 'like', '%' . $query . '%');
                })
                ->where('categories.name', '=', $category)
                ->orderBy('news.created_at', 'desc')
                ->paginate($pages);
        }

        return $result;
    }
}
