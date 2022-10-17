<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/jquery-3.3.1.slim.min.js') }}" defer></script>
    <script src="{{ asset('js/offcanvas.js') }}" defer></script>
    <script src="{{ asset('js/popper.min.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pm_1.css') }}" rel="stylesheet">
</head>
<body class="pb-0">
    <div id="app" class="min-height-full">
    @if(Auth::check())
        <!-- header -->
        <header>
            <!-- header nav -->
            <nav class="navbar navbar-expand-lg fixed-top bg-white border border-top-0 border-left-0  border-right-0 border-light">
                <!-- editable title -->
                <!-- {{ request()->path() }} -->
                @if(request()->path() == "process_view")
                <h1 class="title001">作業工程状況</h1>
                @else
                <a class="navbar-brand mr-auto mr-lg-0" href="{{ url('/') }}">
                    <!--<img class="logo-height" src="{{ asset('images/home-solid.svg') }}" alt=>-->
                    <img class="logo_height" src="{{ asset('images/order_logo2.svg') }}" alt="受発注管理システム">
                </a>
                @endif
                <!-- /editable title -->
                <div class="form-inline my-lg-0 ml-auto">
                    @if(Auth::check())
                        @if(request()->path() !== "process_view")
                    <company-set></company-set>
                        @endif
                    @else
                    <span class="pr-2">
                        <a href="{{ route('login') }}">{{ __('Login') }}</a>
                    </span>
                    @if (Route::has('register'))
                    <span class="pr-2">
                        <a href="{{ route('register') }}">{{ __('Register') }}</a>
                    </span>
                    @endif
                    </ul>
                    @endif
                    <!-- Right Side Of Navbar -->
                    <!--
                    <ul class="navbar-nav ml-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                    -->
                    <!-- /Right Side Of Navbar -->
                    <!-- group name -->
                    <!--
                    <span class="pr-2 d-none d-md-inline">三条印刷株式会社</span>
                    -->
                    <!-- /group name -->
                    <!-- offcanvas-right toggle button -->
                    <!--
                    <button type="button" class="btn btn-secondary btn-sm" type="button" data-toggle="offcanvas-right">
                        <span class="navbar-toggler-icon"><img class="icon-size-sm" src="{{ asset('images/round-search-w.svg') }}" alt=""></span>
                    </button>
                    -->
                    <!-- /offcanvas-right toggle button -->
                </div>
            </nav>
            <!-- /header nav -->
        </header>
        <!-- /header -->
        @endif

		<!-- .container-fluid -->
		<div class="container-fluid min-height-full">
			<!-- .row -->
			<div class="row min-height-full">
                @yield('content')
                @if(Auth::check())
                <!-- main contentns row -->
                <div class="row justify-content-between print-none">
                    <!-- .panel -->
                    <div class="col-md p-3">
                        <div class="text-center">
                            <small><!--© 2021 Ordering System--></small>
                        </div>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /main contentns row -->
				<!-- offcanvas-right -->
				<div class="offcanvas-collapse offcanvas-collapse-from-right side-base">
					<aside>
					</aside>
				</div>
				<!-- /offcanvas-right -->
                @endif
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
    </div>
</body>
</html>
