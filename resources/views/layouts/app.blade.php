<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PIMS') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
 

        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="/vendor/ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
        <!-- App CSS -->
        <link rel="stylesheet" href="/css/app.css">
        <!-- Custom style -->
        <style>
            h4 {
                padding: 0;
                margin: 0;
            }
            h4, h6 {
                color: #000000;
            }
            .title {
                color: #800080;
            }
            .title b {
                color: #666;
            }
            .login-page {
                background-color: #FFFFFF;
                overflow: hidden;
            }
            .login-page::before {
                content: '.' ;
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                background-size: cover;
                opacity: 0.1;
            }
            .login-box-body {
                position: relative;
                background: white;
            }
            .col-xs-5 {
                padding-right: 0;
            }
            
        </style>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                
                <h3>PUBLICATION INVENTORY MANAGEMENT SYSTEM</h3>
                <a class="btn btn-info" href="{{ route('login') }}">Back to Login</a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
 

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                           
 
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
                            </center>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-12">
            @yield('content')
        </main>
    </div>
</body>
</html>
