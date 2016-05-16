@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/categoryNews.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-lg-8 col-lg-offset-1">
                <div class="panel panel-default catPanels">
                    <div class="panel-heading">{{ $panelTitle }}</div>

                    <div class="panel-body container">
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="panel panel-default catPanels">
                    <div class="panel-heading">{{ trans('views\categoryNews.newNews') }}</div>

                    <div class="panel-body container col-lg-offset-1">
                        @foreach($newNews as $news)
                            <div class="row sidebarRow">
                                <img src="{{ $news->image }}" class="sidebarImage">
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