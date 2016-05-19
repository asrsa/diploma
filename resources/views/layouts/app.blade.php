<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--<meta name="csrf-token" content="{{ csrf_token() }}" />--}}

    @hasSection('title')
        <title>@yield('title')</title>
    @else
        <title>{{trans('views\layoutPage.siteName')}}</title>
    @endif


    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    {{--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">--}}
    <link rel="stylesheet" href="{{ URL::asset('css/layout.css') }}">
    @yield('styles')
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container ">
            <div class="navbar-header">

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{trans('views\layoutPage.siteName')}}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/home') }}">{{trans('views\layoutPage.home')}}</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">{{trans('views\layoutPage.login')}}</a></li>
                        <li><a href="{{ url('/register') }}">{{trans('views\layoutPage.register')}}</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->firstName }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                @can('isUser')
                                    <li><a href="{{ URL::route('changeAvatar') }}"><i class="fa"></i>{{trans('views\accountPage.changeAvatar')}}</a></li>
                                    <li><a href="{{ URL::route('changePasswordGet') }}"><i class="fa"></i>{{trans('views\authorPage.changePassword')}}</a></li>
                                    <li role="separator" class="divider"></li>
                                @endcan

                                @can('isAdmin')
                                    <li><a href="{{ URL::route('addAuthor') }}"><i class="fa"></i>{{trans('views\adminPage.addAuthor')}}</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="{{ URL::route('changePasswordGet') }}"><i class="fa"></i>{{trans('views\adminPage.changePassword')}}</a></li>
                                    <li role="separator" class="divider"></li>
                                @endcan

                                @can('isAuthor')
                                    <li><a href="{{ URL::route('createNews') }}"><i class="fa"></i>{{trans('views\authorPage.createNewsMenu')}}</a></li>
                                    <li><a href="{{ URL::route('authorNews') }}"><i class="fa"></i>{{trans('views\authorPage.myNews')}}</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="{{ URL::route('changePasswordGet') }}"><i class="fa"></i>{{trans('views\authorPage.changePassword')}}</a></li>
                                    <li role="separator" class="divider"></li>
                                @endcan

                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>{{trans('views\layoutPage.logout')}}</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>



    <div class="col-lg-8 col-lg-offset-2">
            <nav class="navbar navbar-default">
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/home') }}">{{trans('views\layoutPage.home')}}</a></li>
                    <li><a href="{{ url('/home') }}">{{trans('views\layoutPage.home')}}</a></li>
                    <li><a href="{{ url('/home') }}">{{trans('views\layoutPage.home')}}</a></li>
                    <li><a href="{{ url('/home') }}">{{trans('views\layoutPage.home')}}</a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right" id="searchNav">
                    <li>
                        <div class="form-group">
                            <form class="form-horizontal" role="form" method="GET" action="{{ URL::route('searchNews')}}">
                                {{--{!! csrf_field() !!}--}}
                                <div class="">
                                    <input type="text" id="searchText" class="form-control hidden" name="search" autocomplete="off">
                                </div>
                            </form>
                        </div>
                    </li>
                    <li><a class="btn btn-link" href="#" id="searchButton"><i class="fa fa-search" aria-hidden="true"></i></a></li>
                </ul>
            </nav>
    </div>



    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.0-rc.2/jquery-ui.js" integrity="sha256-6HSLgn6Ao3PKc5ci8rwZfb//5QUu3ge2/Sw9KfLuvr8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    {{--<script type="text/javascript">--}}
        {{--$.ajaxSetup({--}}
            {{--headers: {--}}
                {{--'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--}--}}
        {{--});--}}
    {{--</script>--}}
    <script type="text/javascript" src="{{ URL::asset('js/searchNews.js') }}"></script>
    @yield('scripts')
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
