@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/categoryNews.css') }}">
@endsection

@section('title')
    {{ $panelTitle }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">

            {{--MAIN PANEL--}}
            <div class="col-lg-6 col-lg-offset-2">
                <div class="panel panel-default catPanels">
                    <div class="panel-heading">{{ $panelTitle }}</div>

                    <div class="panel-body container">
                        @foreach($mainNews as $news)
                            <div class="row">
                                <div class="container-fluid col-lg-7">
                                    <div class="panel panel-default categoryPanel">
                                        <div class="panel-heading mainNewsHead"><a href="{{ URL::route('subcategory', $news[0]->subcategory_name) }}">{{ $news[0]->subcategory }}</a></div>
                                        <div class="panel-body container">
                                            <div class="row">
                                                @foreach($news as $new)
                                                    <div class="container col-lg-2">
                                                        <a href="{{ URL::route('individualNews', $new->id) }}"><img src="{{ $new->image }}" class="categoryImg"></a>
                                                        <a href="{{ URL::route('individualNews', $new->id) }}"><h5>{{ $new->title }}</h5></a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{--SIDEBAR--}}
            <div class="col-lg-2">
                <div class="panel panel-default catPanels">
                    <div class="panel-heading">{{ trans('views\categoryNews.newNews') }}</div>

                    <div class="panel-body container col-lg-offset-1">
                        @foreach($newNews as $news)
                            <div class="row sidebarRow">
                                <a href="{{ URL::route('individualNews', $news->id) }}" class="sidebarLink"><img src="{{ $news->image }}" class="sidebarImage"></a>
                                <a href="{{ URL::route('individualNews', $news->id) }}" class="sidebarLink"><p class="sidebarTitle">{{ $news->title }}</p></a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
@endsection