<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <!--<script src="{{ mix('js/app.js') }}" defer></script>-->
    <!--<script src="{{ asset('js/bootstrap.min.js') }}" defer></script>-->
    <script src="{{ asset('js/axios.min.js') }}" defer></script>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}" defer></script>
    <script src="{{ asset('js/gototop.js') }}" defer></script>
    <script src="{{ asset('js/offcanvas.js') }}" defer></script>
    <!--<script src="{{ asset('js/popper.min.js') }}" defer></script>-->
    <!-- Fonts -->
    <!--
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
    -->
    <!-- Styles -->
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" class="wrapper">
        <!-- header -->
        <header class="wlong">
            <!-- header nav -->
            <nav class="navbar ">
                <!-- offcanvas-left toggle button -->
                <button type="button" class="menu_btn" type="button" data-toggle="offcanvas-left">
                    <span class="menu_btn_style" ><img class="" src="{{ asset('images/round-menu-w.svg') }}" alt=""></span>
                </button>
                <!-- /offcanvas-left toggle button -->
                <div id="logo">
                <a class="" href="{{ url('/') }}">
                    <span><img class="logo_h" src="{{ asset('images/home-white.svg') }}"  alt="作業工程管理システム"></span>
                    <span>作業工程管理システム</span>
                    <span>&emsp;&emsp;-- 加工課 --</span>
                </a>
                <div>
            </nav>
            <!-- /header nav -->
            @include('layouts.menu')
        </header>
        <!-- /header -->

		<!-- .container-fluid -->
		<div>
                @yield('content')
                    <!-- .panel -->
                    <div id="footer">
                        <div class="foot_cnt">
                            <small>© 2023 Work-in-Process Controll System</small>
                        </div>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- end contents_area -->
                
		</div>
		<!-- /.container-fluid -->
    </div><!--end id="app" class="wrapper"-->
    @yield('jscript')
</body>
</html>
