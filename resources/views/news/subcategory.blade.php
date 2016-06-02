@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/categoryNews.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/sidebar.css') }}">
@endsection

@section('title')
    {{ $panelTitle}}
@endsection


@if($catId == 2)
@section('navPolitics')
    navbar-selected
@endsection
@elseif($catId == 1)
@section('navSport')
    navbar-selected
@endsection
@elseif($catId == 3)
@section('navEntertainment')
    navbar-selected
@endsection
@endif

@section('subnav')
    <nav class="navbar navbar-default navbar-sm" id="subnav">
        <ul class="nav navbar-nav">
            @foreach($subcats as $key => $subcat)
                @if($subcat == $panelTitle)
                    <li class="subnav-selected"><a href="{{ url('/subcat/') .'/'. $key }}">{{ $subcat }}</a></li>
                @else
                    <li><a href="{{ url('/subcat/') .'/'. $key }}">{{ $subcat }}</a></li>
                @endif
            @endforeach
        </ul>
    </nav>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">

            {{--MAIN PANEL--}}
            <div class="col-lg-6 col-lg-offset-2">
                <div class="panel panel-default catPanels">
                    <div class="panel-heading">{{ $panelTitle }}</div>

                    <div class="panel-body container">
                        @foreach(array_chunk($mainNews->getCollection()->all(), 3) as $news)
                            <div class="row">
                                    <div class="panel-body container">
                                        @foreach($news as $new)
                                            <div class="container col-lg-2">
                                                <a href="{{ URL::route('individualNews', $new->id) }}"><img src="{{ $new->image }}" class="categoryImg"></a>
                                                <a href="{{ URL::route('individualNews', $new->id) }}"><h5>{{ $new->title }}</h5></a>
                                            </div>
                                        @endforeach
                                    </div>
                            </div>
                        @endforeach
                    </div>
                    {{--{!! $mainNews->appends(Request::except('page'))->links() !!}--}}
                    @include('paginators.customPaginator', ['data' => $mainNews->appends(Request::except('page'))])
                </div>
            </div>

            {{--SIDEBAR--}}
            <div class="col-lg-2">
                <div class="panel panel-default catPanels">
                    <div class="panel-heading">{{ trans('views\categoryNews.newNews') }}</div>

                    <div class="panel-body container col-lg-12">
                        @foreach($newNews as $news)
                            <div class="row">
                                <a href="{{ URL::route('individualNews', $news->id) }}" class="sidebarLink sidebarImg"><img src="{{ $news->image }}" class="sidebarImage"></a>
                                <a href="{{ URL::route('individualNews', $news->id) }}" class="sidebarLink sidebarLinkTitle"><p class="sidebarTitle">{{ $news->title }}</p></a>
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