<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>PIMS | @yield('head_title')</title>
        <link rel='shortcut icon' type='image/x-icon' href='/favicon.ico'/>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta http-equiv="refresh" content="600" />

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

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
     <body class="hold-transition login-page">
        <div class="login-box">
            <div class="row login-logo">
                <div class="col-md-12"><img src="/images/pcw-logo-login.png" style="width:100%;"></div>
                <div class="col-md-12">
                    </br>
                    <center>
                        <h4 class="title"><b>Publication Inventory<br> Management System (PIMS)</b></h4>
                    </center>
                </div>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body" style="border:2px solid #f2f2f2;">

                @yield('content')

            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
        
        <!-- jQuery 2.2.3 -->
        <script src="/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="/AdminLTE/bootstrap/js/bootstrap.min.js"></script>

        <!-- Scripts -->
        <script src="/js/app.js"></script>
    </body>
</html>

