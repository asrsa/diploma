<?php

namespace App\Http\Controllers;

use App\Category;
use App\Comment;
use App\Like;
use App\News;
use App\Subcategory;
use App\Subscription;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class NewsController extends Controller
{
    public function __construct() {
        //$this->middleware('web');
    }

    public function index() {
        $news = News::getAllNews(6);

        $hotNews = News::getHotNews(3);

        $recentSession = session()->get('recent');
        $recentNews = array();

        if($recentSession != null) {
            foreach($recentSession as $key => $recent) {
                $recentNews[$key] = News::find($recent);
            }
        }

        return view('news.news', ['news' => $news, 'hotNews' => $hotNews, 'recentNews' => $recentNews]);
    }

    public function showNews(Request $request, $newsId) {
        $news = News::where('id', $newsId)
            ->where('deleted', '=', 0)
            ->firstOrFail();

        $user = User::where('id', '=', $news->user_id)->first();

        $createdBy = $user->firstName[0] . '.' . ' ' . $user->lastName;

        $catId = $news->subcategory->category->id;

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

        foreach($comments as $key => $comment) {
            $date = new \DateTime($comment->created_at);
            $comments[$key]->created_at = $date->format('j.n.Y G:i');
        }

        $subcatIds = News::getSubcategoryId($catId);

        $subcats = array();
        foreach($subcatIds as $id) {
            $subcats[Subcategory::where('id', $id->id)->first()->name] = Subcategory::where('id', $id->id)->first()->desc;
        }

        $newNews = News::getFiveNewNews();

        $data = array(
            'news' => $news,
            'comments' => $comments,
            'catId' => $catId,
            'subcats' => $subcats,
            'panelTitle' => $news->subcategory->desc,
            'newNews' => $newNews,
            'createdBy' => $createdBy
        );

        $this->setRecentlyViewed($request, $newsId);

        return view('news.individualNews', $data);
    }

    public function setRecentlyViewed(Request $request, $newsId) {
        $this->pushToRecent($newsId);
    }

    public function pushToRecent($newsId) {
        $set = true;

        $recent = session()->get('recent', []);

        if(count($recent) >= 5) {
            foreach($recent as $key => $el) {
                if($el == $newsId) {
                    unset($recent[$key]);
                    array_unshift($recent, $newsId);
                    $set = false;
                }
            }
            if($set) {
                array_pop($recent);
                array_unshift($recent, $newsId);
            }
        }
        else {
            foreach($recent as $key => $el) {
                if($el == $newsId) {
                    unset($recent[$key]);
                    array_unshift($recent, $newsId);
                    $set = false;
                }
            }

            if($set) {
                array_unshift($recent, $newsId);
            }
        }

        session()->put('recent', $recent);
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
        $subcats = array();
        foreach($subcatIds as $id) {
            $news = News::getSubcategoryNews($id->id);
            $subName = Subcategory::where('id', $id->id)->first()->desc;

            $subcats[Subcategory::where('id', $id->id)->first()->name] = Subcategory::where('id', $id->id)->first()->desc;
            if($news != []) {
                $mainNews[$subName] = $news;
            }
        }
        $subbed = -1;
        if(Auth::check()) {
            $subbed = $this->checkIfSubbed($catId);
        }

        return view('news.category', ['panelTitle' => $catTitle, 'newNews' => $newNews, 'mainNews' => $mainNews, 'catId' => $catId, 'subcats' => $subcats, 'subbed' => $subbed]);
    }

    public function checkIfSubbed($catId) {
        $sub = Subscription::where('user_id', '=', Auth::user()->id)
            ->where('category_id', '=', $catId)
            ->first();

        if($sub == null) {
            return 0;
        }
        else {
            return $sub->subscribed;
        }
    }

    public function showSubcategory(Request $request, $subcategory) {
        $catId = Subcategory::where('name', '=', $subcategory)->firstOrFail()->category_id;
        $subcatTitle = Subcategory::where('name', '=', $subcategory)->firstOrFail()->desc;
        $subcatId = Subcategory::where('name', '=', $subcategory)->firstOrFail()->id;

        $newNews = News::getFiveNewNews();

        $subcatIds = News::getSubcategoryId($catId);

        $subcats = array();
        foreach($subcatIds as $id) {
            $subcats[Subcategory::where('id', $id->id)->first()->name] = Subcategory::where('id', $id->id)->first()->desc;
        }

        $mainNews = News::getSubcategoryNewsPaginate($subcatId);

        return view('news.subcategory', ['panelTitle' => $subcatTitle, 'newNews' => $newNews, 'mainNews' => $mainNews, 'catId' => $catId, 'subcats' => $subcats]);
    }

    public function searchNews(Request $request) {
        $search = $request->input('search');
        if(!isset($search) || $search == '') {
            return Redirect::back();
        }

        $category = $request->input('cat');

        $pages = 8;
        if(isset($category)) {
            $result = News::searchNews($search, $pages, ucfirst($category));
        }
        else {
            $result = News::searchNews($search, $pages);
        }

        $catCount = array();
        $tmp = News::searchNews($search, $pages, 'tmp');
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
