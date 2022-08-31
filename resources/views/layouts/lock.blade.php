
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>PIMS | Lockscreen</title>
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
                color: #FAFAFA;
            }
            a {color: lightgreen;}
            .lockscreen-logo {
                color: yellowgreen;
                font-size: 52px;
            }
            .lockscreen-logo b {
                color: #8DC3E3;
            }
            .lockscreen {
                background-color: #1e6d9a;
                overflow: hidden;
            }
            .lockscreen::before {
                content: '.' ;
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                background-image: url('/images/up-image.jpg');
                background-size: cover;
                opacity: 0.1;
                z-index: -1;
            }
            .lockscreen-name {
                color: yellow;
            }
            .help-block {
                color: lightblue;
            }
        </style>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition lockscreen">
        <!-- Automatic element centering -->
        <div class="lockscreen-wrapper">
            <div class="lockscreen-logo">
                <b>DR</b>MS
            </div>

            @include('layouts.inc.messages')

            <!-- User name -->
            <div class="lockscreen-name">Username</div>

            <!-- START LOCK SCREEN ITEM -->
            <div class="lockscreen-item">

                <!-- lockscreen image -->
                <div class="lockscreen-image">
                    <img src="/images/pcw_logo.jpg" alt="SEAL">
                </div>
                <!-- /.lockscreen-image -->

                <!-- lockscreen credentials (contains the form) -->
                <form method="POST" action="#" class="lockscreen-credentials">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="password" class="form-control" name="password" placeholder="password">

                        <div class="input-group-btn">
                            <button type="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
                        </div>
                    </div>
                </form>
                <!-- /.lockscreen credentials -->

            </div>
            <!-- /.lockscreen-item -->
            <div class="help-block text-center">
                Enter your password to retrieve your session
            </div>
            <div class="text-center">
                <a href="/login">Or sign in as a different user</a>
            </div>
        </div>
        <!-- /.center -->

        <!-- jQuery 2.2.3 -->
        <script src="/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
