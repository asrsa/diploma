@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{trans('views\accountPage.title')}}</div>
                <div class="panel-body row">
                    @if ($errors->has('success'))
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>{{ $errors->first('success') }}</strong>
                        </div>
                    @endif
                    <div class="col-xs-4">
                        <img src="{{ URL::to('/') . Config::get('paths.PATH_PUBLIC_AVATARS') . '/'. $img_name }}" width="160" height="160">

                        <form class="form-inline" role="form" method="POST" action="{{ url('/avatar/change') }}" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <br/>
                            <div class="form-group">
                                <div class="col-xs-6 form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                    <input type="file" class="" name="image" >
                                    @if ($errors->has('image'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                    @endif
                                    <button type="submit" class="btn btn-sm btn-default">
                                        {{trans('views\accountPage.update_avatar')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-xs-4">
                        <p class="">test</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
