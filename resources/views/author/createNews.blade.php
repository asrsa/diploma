@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{trans('views\authorPage.createNews')}}</div>

                    <div class="panel-body">
                        <div class="form-group">
                            <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('news/create') }}">
                                {!! csrf_field() !!}
                                <textarea class="form-control" name="body" rows="20"></textarea>
                                <br/>
                                    <div class="">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-btn fa-refresh"></i>{{trans('views\authorPage.setPassword')}}
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
        tinymce.init({
            selector: 'textarea',
            resize: false,
            language: 'sl_SI',
            plugins: [
                'advlist autolink lists link image charmap preview anchor',
                'table contextmenu paste'
            ],
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
        });
    </script>
@endsection

