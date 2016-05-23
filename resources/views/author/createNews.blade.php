@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{trans('views\authorPage.createNews')}}</div>

                    <div class="panel-body">
                        @if ($errors->has('error'))
                            <div class="alert alert-warning">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>{{ $errors->first('error') }}</strong>
                            </div>
                        @endif
                        @if ($errors->has('success'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>{{ $errors->first('success') }}</strong>
                            </div>
                        @endif
                        @if ($errors->has('body'))
                            <div class="alert alert-warning">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>{{ trans('views\authorPage.bodyEmpty') }}</strong>
                            </div>
                        @endif
{{--feedback-------------------------------------------------------------------------------------------------------------------}}
                        <div class="form-group">
                            <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('news/create') }}">
                                {!! csrf_field() !!}

                                <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                                    <label class="col-sm-1 control-label">{{trans('views\authorPage.category')}}</label>
                                    <div class="col-sm-6 col-sm-offset-1">
                                        <select class="form-control" name="category" id="category">
                                            <option value="">{{ trans('views\authorPage.chooseCat') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category['id'] }}">{{ $category['desc'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group{{ $errors->has('subcategory') ? ' has-error' : '' }}">
                                    <label class="col-sm-1 control-label">{{trans('views\authorPage.subcategory')}}</label>
                                    <div class="col-sm-6 col-sm-offset-1">
                                        <select class="form-control" name="subcategory" id="subcategory">
                                            {{--@foreach($subcategories as $subcategory)--}}
                                            {{--<option value="{{ $subcategory['id'] }}">{{ $subcategory['name'] }}</option>--}}
                                            {{--@endforeach--}}
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label class="col-sm-1 control-label">{{trans('views\authorPage.newsTitle')}}</label>
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <input type="text" id="title" class="form-control" name="title" value="{{ old('title') }}">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-1 control-label">{{trans('views\authorPage.hotNews')}}</label>
                                    <div class="col-sm-1 col-sm-offset-1">
                                        <input type="checkbox" id="hot" class="form-control" name="hot" value="1">
                                    </div>
                                </div>

                                <div class="input-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                    <span class="input-group-btn">
                                        <a id="lfm" data-input="image" data-preview="holder" class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> {{ trans('views\authorPage.chooseImage') }}
                                        </a>
                                    </span>
                                    <input id="image" class="form-control" type="text" name="image">
                                </div>
                                <img id="holder" style="margin-top:15px;max-height:100px;">

                                <textarea class="form-control" name="body" rows="20">{{ old('body') }}</textarea>
                                <br/>
                                    <div class="">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-btn fa-refresh"></i>{{trans('views\authorPage.createNewsSubmit')}}
                                        </button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::to('src/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script>
        var editor_config = {
            path_absolute : "/",
            resize: false,
            language: 'sl_SI',
            selector: "textarea",
            plugins: [
                "advlist autolink lists link image charmap preview hr anchor pagebreak",
                "table",
                "paste textcolor colorpicker"
            ],
            toolbar: "insertfile undo redo | preview | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            relative_urls: false,
            file_browser_callback : function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
                });
            }
        };

        tinymce.init(editor_config);
    </script>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/createNews.js') }}"></script>
    <script src="/vendor/laravel-filemanager/js/lfm.js"></script>
@endsection

