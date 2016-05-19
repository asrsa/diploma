@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{trans('views\welcomePage.welcome')}}</div>

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

                        @foreach(array_chunk($news->getCollection()->all(), 2) as $row)
                            <div class="row">
                                @foreach($row as $new)
                                    <a href="{{ URL::route('individualNews', $new->id) }}">
                                        <article class="col-md-6">
                                            <h5>{{ $new->title }}</h5>
                                            <img src="{{ $new->image }}" style="height: 96px; width: 130px;">
                                        </article>
                                    </a>
                                @endforeach
                            </div>
                        @endforeach

                        {{--{!! $news->appends(Request::except('page'))->links() !!}--}}
                        @include('paginators.customPaginator', ['data' => $news])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
