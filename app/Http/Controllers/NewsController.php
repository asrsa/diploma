<?php

namespace App\Http\Controllers;

use App\Category;
use App\Comment;
use App\Like;
use App\News;
use App\Subcategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class NewsController extends Controller
{
    public function __construct() {
        $this->middleware('web');
    }

    public function index() {
        $news = News::where('deleted', '=', 0)->paginate(6);

        return view('news\news', ['news' => $news]);
    }

    public function showNews($newsId) {
        $news = News::where('id', $newsId)
            ->where('deleted', '=', 0)
            ->firstOrFail();

        $likeSubquery = DB::table('likes')
            ->select(DB::raw('sum(likes.value)'))
            ->from('likes')
            ->whereRaw('likes.comment_id = comments.id');

        $comments = DB::table('comments')
            ->select('comments.*', 'users.username', 'users.avatar',
                DB::raw('('. $likeSubquery->toSql() .') as likesSum'))
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('news_id', '=', $newsId)
            ->where('deleted', '=', 0)
            ->paginate(5);

        return view('news\individualNews', ['news' => $news, 'comments' => $comments]);
    }

    public function deleteComment(Request $request) {
        $commentId  = Input::get('cid');

        $comment = Comment::where('id', $commentId)->get()->first();
        $comment->deleted = 1;
        $comment->save();

        return redirect(explode('?', URL::previous())[0])->withErrors(['success' => trans('views\individualNews.commentDeleted')]);
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
                $return = 2;
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
                $return = 3;
            }
        }

        return response()->json([
            'return' => $return,
            'cid'    => $commentId,
            'value'  => $value
        ]);
    }

    public function showCategory(Request $request, $category) {
        $catId = (int) Category::where('name', '=', $category)->firstOrFail()->id;
        $catTitle = Category::where('name', '=', $category)->firstOrFail()->desc;

        $newNews = new News();
        $newNews = $newNews->getSubcategoryNewNews($catId);

        $subcatIds = News::getSubcategoryId($catId);

        $mainNews = array();
        foreach($subcatIds as $id) {
            $news = News::getSubcategoryNews($id->id);
            $subName = Subcategory::where('id', $id->id)->first()->desc;

            if($news != []) {
                $mainNews[$subName] = $news;
            }
        }

        return view('news.category', ['panelTitle' => $catTitle, 'newNews' => $newNews, 'mainNews' => $mainNews]);
    }

    public function showSubcategory(Request $request, $subcategory) {

        $subcatTitle = Subcategory::where('name', '=', $subcategory)->firstOrFail()->desc;
        $subcatId = Subcategory::where('name', '=', $subcategory)->firstOrFail()->id;

        $newNews = News::getFiveNewNews();

        $mainNews = News::getSubcategoryNewsPaginate($subcatId);

        return view('news.subcategory', ['panelTitle' => $subcatTitle, 'newNews' => $newNews, 'mainNews' => $mainNews]);
    }

    public function searchNews(Request $request) {
        $search = $request->input('search');
        if(!isset($search) || $search == '') {
            return Redirect::back();
        }

        $category = $request->input('cat');

        if(isset($category)) {
            $result = News::searchNews($search, 8, ucfirst($category));
        }
        else {
            $result = News::searchNews($search, 8);
        }

        $catCount = array();
        $tmp = News::searchNews($search, 8);
        $catCount['all'] = array('count' => '', 'desc' => 'Vse kategorije');
        foreach($tmp as $news) {
            $catCount[$news->catName] = array('count' => 0, 'desc' => $news->catDesc);
        }
        foreach($tmp as $news) {
            $catCount[$news->catName]['count'] = $catCount[$news->catName]['count'] + 1;
        }

        return view('news.searchNews', ['result' => $result, 'categories' => $catCount]);
    }
}
