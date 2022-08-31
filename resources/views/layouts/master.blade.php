<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>PIMS - @yield('head_title')</title>
        <link rel='shortcut icon' type='image/x-icon' href='/favicon.ico'/>
        
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="/vendor/ionicons/css/ionicons.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
        <!-- Select2 -->
        <link rel="stylesheet" href="/AdminLTE/plugins/select2/select2.min.css">
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="/AdminLTE/plugins/iCheck/all.css">
        @yield('page_css')
        <!-- Theme style -->
        <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
             page. However, you can choose any other skin. Make sure you
             apply the skin class to the body tag so the changes take effect.
        -->
        <link rel="stylesheet" href="/AdminLTE/dist/css/skins/skin-blue.min.css">
        <!-- App CSS -->
        <link rel="stylesheet" href="/css/app.css">
        <script src="/js/jquery-1.12.4.min.js"></script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style type="text/css">
            #table thead.modified {
                background-color: #3c8dbc;
                color: white;
            }
            #table tr.modified:hover {
                background-color: #ffff99;
            }
            #table th {
                text-align: center;
            }
            #table tbody tr:nth-child(odd){
            background-color:#e6f2ff;
            color: #000;
            }
            #table tbody tr:nth-child(even){
            background-color:#f0f5f5;
            color: #000;
            }

            #tblHistory thead.modified {
                background-color: #3c8dbc;
                color: white;
            }
            #tblHistory tr.modified:hover {
                background-color: #ffff99;
            }
            #tblHistory th {
                text-align: center;
            }
            #tblHistory tbody tr:nth-child(odd){
            background-color:#e6f2ff;
            color: #000;
            }
            #tblHistory tbody tr:nth-child(even){
            background-color:#f0f5f5;
            color: #000;
            }
        </style>

    </head>
    <!--
    BODY TAG OPTIONS:
    =================
    Apply one or more of the following classes to get the
    desired effect
    |---------------------------------------------------------|
    | SKINS         | skin-blue                               |
    |               | skin-black                              |
    |               | skin-purple                             |
    |               | skin-yellow                             |
    |               | skin-red                                |
    |               | skin-green                              |
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
    -->
    <body class="hold-transition skin-blue fixed sidebar-mini">

        <div class="wrapper">

            @include('layouts.header')

            @include('layouts.sidebars.index')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <left>
                    <ol class="breadcrumb">
                        <li class="active"><i class="fa fa-home"></i> Home</li>
                        @if($page['parent'] !== '/')
                            <li class="active">{{ $page['crumb'] }}</li>
                        @endif
                    </ol>
                </left>
               <div class="box-body">    
               <div class="row">
                <div class="col-md-10">
                     <h3>
                        <b>@yield('page_title')</b>
                        <small>@yield('page_subtitle')</small>
                    </h1>
                </div>
                <div class="col-md-2" id="divCreateNewButton">
                </div>
                </section>

                <!-- Main content -->
                <section class="content">

                    <!-- Your Page Content Here -->
                    @yield('content')

                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            @include('layouts.footer')
            @include('layouts.controlbar')
                
        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED JS SCRIPTS -->

        <!-- jQuery 2.2.3 -->
        <script src="/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
        <!-- DataTables -->
        <script src="/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
        <script src="/AdminLTE/plugins/datatables/dataTables.buttons.min.js"></script>
        <!-- SlimScroll -->
        <script src="/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="/AdminLTE/plugins/fastclick/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="/AdminLTE/dist/js/app.min.js"></script>
        <!-- App JS -->
        <script src="/js/app.js"></script>
        <!-- Select2 -->
        <script src="/AdminLTE/plugins/select2/select2.full.min.js"></script>
        <!-- iCheck 1.0.1 -->
        <script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>
        @yield('page_script')

    </body>
</html>
