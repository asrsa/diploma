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

    {{--Icon--}}
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    {{--<link rel="stylesheet" href="{{ URL::asset('font-awesome-4.6.3/css/font-awesome.min.css') }}" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">--}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">--}}
    <link rel="stylesheet" href="{{ URL::asset('bootstrap-3.3.6-dist/css/bootstrap.min.css') }}" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ URL::asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('weather-icons/css/weather-icons.min.css') }}">
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
    <nav class="navbar navbar-default  navbar-static-top navbarTop-sm">
        <div class="container">
            <div class="navbar-header">
                <ul class="nav navbar-nav">
                    <li>
                    <a class="brand" href="{{ url('/') }}">
                        {{trans('views\layoutPage.siteName')}}
                    </a>
                    </li>
                </ul>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            @if(session()->get('locale') == null)
                                <img src="{{ URL::asset('locales/sl.png') }}">
                            @elseif(session()->get('locale') == 'sl')
                                <img src="{{ URL::asset('locales/sl.png') }}">
                            @elseif(session()->get('locale') == 'en')
                                <img src="{{ URL::asset('locales/en.png') }}">
                            @endif
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu localeMenu">
                            <?php
                                $locales = array('sl', 'en');
                                $sess = session()->get('locale') == null? 'sl': session()->get('locale');
                            ?>
                            @foreach($locales as $locale)
                                @if($sess != $locale)
                                    <li><a href="{{ URL::route('setLocale', $locale) }}"><img src="{{ URL::asset('locales/' . $locale . '.png') }}"></a></li>
                                @endif
                            @endforeach
                        </ul>
                    </li>

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
                                    <li><a href="{{ URL::route('authorNews') }}"><i class="fa"></i>{{trans('views\adminPage.allNews')}}</a></li>
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
        <nav class="navbar navbar-default navbar-sm">
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/') }}">{{ trans('views\layoutPage.frontPage') }}</a></li>
                <li class="@yield('navPolitics')"><a href="{{ url('/cat/politika') }}">{{trans('views\layoutPage.politics')}}</a></li>
                <li class="@yield('navSport')"><a href="{{ url('/cat/sport') }}">{{trans('views\layoutPage.sports')}}</a></li>
                <li class="@yield('navEntertainment')"><a href="{{ url('/cat/zabava') }}">{{trans('views\layoutPage.entertainment')}}</a></li>
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
        @yield('subnav')
    </div>



    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.0-rc.2/jquery-ui.js" integrity="sha256-6HSLgn6Ao3PKc5ci8rwZfb//5QUu3ge2/Sw9KfLuvr8=" crossorigin="anonymous"></script>
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>--}}
    <script src="{{ URL::asset('bootstrap-3.3.6-dist/js/bootstrap.min.js') }}" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>



    <script type="text/javascript" src="{{ URL::asset('js/searchNews.js') }}"></script>
    @yield('scripts')
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
