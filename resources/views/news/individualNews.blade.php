@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $news->title }}</div>

                    <div class="panel-body container">
                        @if ($errors->has('success'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>{{ $errors->first('success') }}</strong>
                            </div>
                        @endif
                        @if ($errors->has('error'))
                            <div class="alert alert-danger">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>{{ $errors->first('error') }}</strong>
                            </div>
                        @endif

                        <h3>{{ $news->title }}</h3>
                        <small>{{ date('j.n.Y', $news->created_at->getTimestamp()) }}</small>
                        <div class="container">
                            {!! $news->body !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('views\individualNews.commentsTitle')  }}</div>

                    <div class="panel-body container">
                        @if ($errors->has('success'))
                            <div class="alert alert-success  col-md-7">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>{{ $errors->first('success') }}</strong>
                            </div>
                        @endif
                        @if ($errors->has('error'))
                            <div class="alert alert-danger  col-md-7">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>{{ $errors->first('error') }}</strong>
                            </div>
                        @endif

                        <div id="feedback">

                        </div>
                        <p class="hidden" id="emptyComment">{{ trans('views\individualNews.commentEmptyFail') }}</p>
                        <p class="hidden" id="addedCommentSuccess">{{ trans('views\individualNews.commentAddedSuccess') }}</p>

                        {{--BUTTONS--}}
                        <div class="container row col-md-7">
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
                        <div id="postCommentContainer" class="container col-md-7 col-md-offset-0 hidden">
                            <div class="row">
                                <div class="panel">
                                    <form method="POST" action="{{ URL::route('postComment') }}" class="form-horizontal">
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


                            <div id="commentsContainer" class="container col-md-7 col-md-offset-0">
                                @foreach($comments as $comment)
                                    <div class="row">
                                        <div class="panel">
                                            <div class="col-md-2">
                                                <img src="{{ Config::get('paths.PATH_PUBLIC_AVATARS') .'/'. $comment->avatar }}" style="width: 46px; height: 46px;">
                                                <p>{{ $comment->firstName }}</p>
                                            </div>
                                            <div class="col-md-10">
                                                <p>{{ $comment->body }}</p>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                                {{ $comments->links() }}
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/individualNews.js') }}"></script>
@endsection