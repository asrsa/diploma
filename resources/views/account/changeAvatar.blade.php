@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{trans('views\accountPage.changeAvatar')}}</div>

                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/avatar/change') }}" enctype="multipart/form-data">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">{{trans('views\accountPage.imageInput')}}</label>
                                <div class="col-md-6">
                                    <input type="file" class="form-control" name="image">

                                    @if ($errors->has('image'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{trans('views\accountPage.changeAvatarSubmit')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="panel-footer">
                        <a class="btn btn-primary" href="{{ URL::route('account') }}">{{ trans('views\accountPage.back') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
