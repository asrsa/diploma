@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/searchNews.css') }}">
@endsection


@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{trans('views\searchPage.searchPage')}}</div>

                    <div class="panel-body container-fluid">
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

                        @if(!isset($result[0]))
                            <div class="alert alert-danger">
                                <a href="#" class="close" data-dismiss="alert"></a>
                                <strong>{{ trans('views\searchPage.noResult') }}</strong>
                            </div>
                        @else
                            <div class="container col-lg-12">
                                <h4>{{ trans('views\searchPage.listOfCats') }}</h4>
                                @foreach(array_chunk($categories, 4, true) as $row)
                                    <div class="row">
                                        @foreach($row as $key => $cat)
                                            <div class="col-lg-2">
                                                <a href="{{ URL::route('searchNews') }}?search={{ $_GET['search'] }}&cat={{ strtolower($key) }}"
                                                @if(isset($_GET['cat']))
                                                    @if($_GET['cat'] == strtolower($key))
                                                        class="categoryClicked"
                                                    @endif
                                                @else
                                                    @if(strtolower($key)  == 'all')
                                                        class="categoryClicked"
                                                    @endif
                                                @endif
                                                >
                                                    {{ $cat['desc'] }}
                                                </a>
                                                @if($cat['count'] != '')
                                                    ({{ $cat['count'] }})
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                                <hr/>
                            </div>
                            @foreach(array_chunk($result->getCollection()->all(), 2) as $row)
                                <div class="container col-lg-12">
                                    <div class="row">
                                        @foreach($row as $new)
                                            <a href="{{ URL::route('individualNews', $new->id) }}">
                                                <article class="col-lg-4">
                                                    <h5>{{ $new->title }}</h5>
                                                    <img src="{{ $new->image }}" style="height: 96px; width: 130px;">
                                                </article>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        {{--{!! $result->appends(Request::except('page'))->links() !!}--}}
                        @include('paginators.customPaginator', ['data' => $result])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
