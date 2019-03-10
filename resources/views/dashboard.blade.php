<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8"/>
    <title>Adminto - Responsive Admin Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description"/>
    <meta content="Coderthemes" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('adminto/assets/images/favicon.ico')}}">

    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="{{asset('adminto/assets/plugins/morris/morris.css')}}">

    <!-- App css -->
    <link href="{{asset('adminto/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('adminto/assets/css/icons.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('adminto/assets/css/style.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('adminto/assets/css/mystyle.css')}}" rel="stylesheet" type="text/css"/>
    <script src="{{asset('adminto/assets/js/modernizr.min.js')}}"></script>

    <meta name="user" content="@if(\Illuminate\Support\Facades\Auth::check()) {{ \Illuminate\Support\Facades\Auth::user()}} @endif">

</head>
<body>

<div id="app">


</div>
</body>
<script src="{{asset('adminto/assets/js/jquery.min.js')}}"></script>


<!-- jQuery  -->

<script src="{{asset('adminto/assets/js/popper.min.js')}}"></script>
<script src="{{asset('adminto/assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('adminto/assets/js/waves.js')}}"></script>
<script src="{{asset('adminto/assets/js/jquery.slimscroll.js')}}"></script>

<!-- KNOB JS -->
<!--[if IE]>
<script type="text/javascript" src="{{asset('adminto/assets/plugins/jquery-knob/excanvas.js')}}"></script>
<![endif]-->
<script src="{{asset('adminto/assets/plugins/jquery-knob/jquery.knob.js')}}"></script>

<!--Morris Chart-->
<script src="{{asset('adminto/assets/plugins/morris/morris.min.js')}}"></script>
<script src="{{asset('adminto/assets/plugins/raphael/raphael-min.js')}}"></script>

<!-- Dashboard init -->
<script src="{{asset('adminto/assets/pages/jquery.dashboard.js')}}"></script>

<script src="{{asset('js/app.js')}}"></script>
<!-- App js -->
<script src="{{asset('adminto/assets/js/jquery.core.js')}}"></script>
<script src="{{asset('adminto/assets/js/jquery.app.js')}}"></script>


</html>
