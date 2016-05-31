@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/frontPage.css') }}">
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-6 col-lg-offset-2">
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
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
                        <div class="panel-heading">{{trans('views\welcomePage.hotNews')}}</div>
                        <div class="panel-body container-fluid">
                            <div class="container-fluid hotPanel col-lg-12">
                                @foreach($hotNews as $hot)
                                    <div class="panel hotDiv">
                                        <a href="{{ URL::route('individualNews', $hot->id) }}">
                                            <img class="hotImg" src="{{ $hot->image }}">
                                        </a>
                                        <a class="titleLink" href="{{ URL::route('individualNews', $hot->id) }}">
                                            <span class="dark">
                                                <h3 class="hotTitle">{{ $hot->title }}</h3>
                                            </span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="panel panel-default newsContainer">
                        <div class="panel-heading">{{trans('views\welcomePage.welcome')}}</div>

                        <div class="panel-body container-fluid">
                            @foreach(array_chunk($news->all(), 2) as $row)
                                <div class="row">
                                    @foreach($row as $new)
                                        <a href="{{ URL::route('individualNews', $new->id) }}">
                                            <article class="col-md-6 article">
                                                <img src="{{ $new->image }}">
                                                <h5>{{ $new->title }}</h5>
                                            </article>
                                        </a>
                                    @endforeach
                                </div>
                            @endforeach

                            <div class="bot">
                                {{--{!! $news->appends(Request::except('page'))->links() !!}--}}
                                @include('paginators.customPaginator', ['data' => $news->appends(Request::except('page'))])
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <div class="col-lg-2" id="sidebar">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('views\welcomePage.weather') }}</div>
                    <div class="panel-body container-fluid">

                    </div>
                </div>
            </div>
            </div>

        {{--</div>--}}

        {{--<div class="row">--}}
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/hotNews.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/weather.js') }}"></script>
@endsection