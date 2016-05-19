@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/individualNews.css') }}">
@endsection

@section('title')
    {{ $news->title }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $news->title }}</div>

                    <div class="panel-body container">
                        {{--@if ($errors->has('success'))--}}
                            {{--<div class="alert alert-success">--}}
                                {{--<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>--}}
                                {{--<strong>{{ $errors->first('success') }}</strong>--}}
                            {{--</div>--}}
                        {{--@endif--}}
                        {{--@if ($errors->has('error'))--}}
                            {{--<div class="alert alert-danger">--}}
                                {{--<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>--}}
                                {{--<strong>{{ $errors->first('error') }}</strong>--}}
                            {{--</div>--}}
                        {{--@endif--}}

                        <h3>{{ $news->title }}</h3>
                        <small>{{ date('j.n.Y', $news->created_at->getTimestamp()) }}</small>
                        <div class="container">
                            {!! $news->body !!}
                        </div>
                    </div>
                </div>
            </div>

            {{--SIDEBAR--}}
            <div class="col-md-2">
                <div class="panel panel-default catPanels">
                    <div class="panel-heading">{{ trans('views\categoryNews.newNews') }}</div>

                    <div class="panel-body container col-lg-offset-1">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('views\individualNews.commentsTitle')  }}</div>

                    <div class="panel-body container-fluid">
                        @if ($errors->has('success'))
                            <div class="alert alert-success  col-lg-11">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>{{ $errors->first('success') }}</strong>
                            </div>
                        @endif
                        @if ($errors->has('error'))
                            <div class="alert alert-danger  col-lg-11">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>{{ $errors->first('error') }}</strong>
                            </div>
                        @endif

                        <div id="feedback">

                        </div>
                        <p class="hidden" id="emptyComment">{{ trans('views\individualNews.commentEmptyFail') }}</p>
                        <p class="hidden" id="addedCommentSuccess">{{ trans('views\individualNews.commentAddedSuccess') }}</p>
                        <p class="hidden" id="commentTooLong">{{ trans('views\individualNews.commentTooLong') }}</p>
                        <p class="hidden" id="commentDeleteTooltip">{{ trans('views\individualNews.deleteComment') }}</p>


                        {{--BUTTONS--}}
                        <div class="container row col-lg-12">
                            <button id="showComments" type="button" class="btn btn-default col-md-6 hidden" style="width:150px;">
                                <i class="fa fa-btn fa-refresh"></i>{{trans('views\individualNews.showComments')}}
                            </button>
                            <button id="hideComments" type="button" class="btn btn-default col-md-6 hidden" style="width:150px;">
                                <i class="fa fa-btn fa-refresh"></i>{{trans('views\individualNews.hideComments')}}
                            </button>
                            @can('isUser')
                                <button id="postComments" type="button" class="btn btn-default col-md-6" style="width:150px;">
                                    <i class="fa fa-btn fa-refresh"></i>{{trans('views\individualNews.postComment')}}
                                </button>
                            @endcan
                        </div>

                        {{--DODAJ KOMENTAR--}}
                        <div id="postCommentContainer" class="container col-lg-11">
                            <div class="row">
                                <div class="panel">
                                    <form id="formComment" method="POST" action="{{ URL::route('postComment') }}" class="form-horizontal hidden">
                                        {!! csrf_field() !!}
                                        <textarea id="comment" name="comment"  rows="5" cols="80"  style="resize: none;"></textarea>
                                        <button id="postCommentForm" type="button" class="btn btn-default">
                                            <i class="fa fa-btn fa-comment-o"></i>{{trans('views\individualNews.postComment')}}
                                        </button>
                                        <button id="closeCommentForm" type="button" class="btn btn-default hidden" style="width: 35px;">
                                            <i class="fa fa-btn fa-close"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <input id="totalPages" class="hidden" value="{{ $comments->lastPage() }}">
                        <input id="totalComments" class="hidden" value="{{ $comments->total() }}">
                        <input id="perPage" class="hidden" value="{{ $comments->perPage() }}">

                        <div id="commentsContainer" class="container col-lg-11">
                            <a name="comments"></a>
                            @foreach($comments as $comment)
                                <div id="childRow" name="{{ $comment->id }}" class="row">
                                    <div class="panel panel-default col-lg-12">
                                        <div class="panel-body">
                                            <div class="col-md-2">
                                                <img src="{{ Config::get('paths.PATH_PUBLIC_AVATARS') .'/'. $comment->avatar }}" style="width: 46px; height: 46px;">
                                                <p>{{ $comment->username }}</p>
                                            </div>
                                            <div class="col-md-9">
                                                <p  style="word-wrap: break-word;">{{ $comment->body }}</p>
                                            </div>

                                            {{--Buttons for delete and like--}}
                                            <div class="col-md-1 pull-right likesDiv" style="width: 50px;">

                                                <div class="row pull-right" style="width: 50px;">
                                                    <div class="likesCount" id="likes{{ $comment->id }}">{{ $comment->likesSum or 0}}</div>
                                                    @can('isNotLoggedUser', $comment)
                                                    <div class="thumbs pull-right" data-toggle="errorVoteTooltip{{ $comment->id }}" data-placement="right" title="{{ trans('views\individualNews.alreadyVoted') }}">
                                                        <input value="{{ $comment->id }}" class="cid hidden">
                                                        {{--<button class="btn btn-link disabled"><i class="fa fa-btn fa-thumbs-o-up"></i></button>--}}
                                                        {{--<button class="btn btn-link"><i class="fa fa-btn fa-thumbs-o-down"></i></button>--}}
                                                        <a class="upvote thumb" id="upvotea{{ $comment->id }}" href="{{ URL::route('likeComment') .'?cid='.$comment->id.'&type=up' }}"><i class="fa fa-btn fa-thumbs-o-up" id="upvote{{ $comment->id }}"></i></a>
                                                        <a class="downvote thumb" id="downvotea{{ $comment->id }}" href="{{ URL::route('likeComment') .'?cid='.$comment->id.'&type=down' }}" ><i class="fa fa-btn fa-thumbs-o-down" id="downvote{{ $comment->id }}"></i></a>
                                                    </div>
                                                    @endcan

                                                    @can('isLoggedUser', $comment)
                                                        <div class="thumbs pull-right" data-toggle="errorVoteTooltip{{ $comment->id }}" data-placement="right" title="{{ trans('views\individualNews.alreadyVoted') }}">
                                                            <input value="{{ $comment->id }}" class="cid hidden">
                                                            <a class="noComment thumb" href="#"><i class="fa fa-btn fa-thumbs-o-up"></i></a>
                                                            <a class="noComment thumb" href="#"><i class="fa fa-btn fa-thumbs-o-down"></i></a>
                                                        </div>
                                                    @endcan
                                                    @if(!Auth::check())
                                                        <div class="thumbs pull-right" data-toggle="errorVoteTooltip{{ $comment->id }}" data-placement="right" title="{{ trans('views\individualNews.alreadyVoted') }}">
                                                            <input value="{{ $comment->id }}" class="cid hidden">
                                                            <a class="noComment thumb" href="#"><i class="fa fa-btn fa-thumbs-o-up"></i></a>
                                                            <a class="noComment thumb" href="#"><i class="fa fa-btn fa-thumbs-o-down"></i></a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @can('isLoggedUser', $comment)
                                            <div class="pull-right deleteComment" style="width: 50px;">
                                                <div id="deleteClick">
                                                    {{--                                                        <a href="{{ URL::route('deleteComment') .'?cid='.$comment->id}}" data-toggle="deleteComment" title="{{ trans('views\individualNews.deleteComment') }}"><i class="fa fa-btn fa-close"></i></a>--}}
                                                    <a class="btn btn-xs btn-default" href="#" data-href="{{ URL::route('deleteComment') .'?cid='.$comment->id}}" data-toggle="modal" data-target="#confirmDelete" title="{{ trans('views\individualNews.deleteComment') }}"><i class="fa fa-btn fa-close"></i>{{ trans('views\individualNews.deleteButton') }}</a>
                                                </div>
                                            </div>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            {{ trans('views\individualNews.confirmDelete') }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('views\individualNews.cancelDelete') }}</button>
                                            <a class="btn btn-danger btnDelete">{{ trans('views\individualNews.deleteButton') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="ajaxAdd"></div>
                            {{--{{ $comments->appends(Request::except('page'))->links() }}--}}
                            @include('paginators.customPaginator', ['data' => $comments, 'append' => '#comments'])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/individualNews.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/commentLike.js') }}"></script>
@endsection