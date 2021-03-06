@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 ">
            <div class="panel panel-default">
                <div class="panel-heading">{{trans('views\welcomePage.welcome')}}</div>

                <div class="panel-body">
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
                    Your Application's Landing Page.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
