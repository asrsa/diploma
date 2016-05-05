@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{trans('views\accountPage.title')}}</div>

                <div class="panel-body row">
                    <div class="col-xs-4">
                        <img src="{{ URL::to('/') . Config::get('paths.PATH_PUBLIC_AVATARS') . '/'. $img_name }}">
                        <form class="form-inline" role="form" method="POST" action="{{ url('/avatar/change') }}">
                            {!! csrf_field() !!}
                            <br/>
                            <div class="form-group">
                                <div class="col-xs-4">
                                    <input type="file" class="file" name="image" >
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
