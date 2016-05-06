@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{trans('views\adminPage.title')}}</div>
                <div class="panel-body row">
                    @if ($errors->has('success'))
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>{{ $errors->first('success') }}</strong>
                        </div>
                    @endif

                    <div class="col-sm-4">
                        <a class="btn btn-primary" href="{{ URL::route('addAuthor') }}">{{ trans('views\adminPage.addAuthor') }}</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
