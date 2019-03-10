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



    <!-- App css -->
    <link href="{{asset('adminto/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('adminto/assets/css/icons.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('adminto/assets/css/style.css')}}" rel="stylesheet" type="text/css"/>


</head>
<body>
        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
            <div class="m-t-50 card-box" style="margin-top:40%">
                <div class="text-center">
                    <a href="/" class="logo"><span>Hà Thanh<span> Hải</span></span></a>
                    <h5 class="text-muted mt-0 font-600">Hệ Thống Giám Sát Và Theo Dõi</h5>
                </div>

                @if(session()->has('message'))
                    <br><br>
                    <div class="text-danger" style="margin-left: 6%">
                        <p>{!! session()->get('message') !!}</p>
                    </div>
                @endif
                @if(session()->has('authentication_fail'))
                    <br><br>
                    <div class="text-danger" style="margin-left: 11%">
                        <p>{!! session()->get('authentication_fail') !!}</p>
                    </div>
                @endif
                <div class="p-20">
                    <form action="{{route('login')}}" method="post" class="form-horizontal m-t-20" >
                        <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input  value="{{ old('username') }}" class="form-control" type="text" name="username"  placeholder="Username">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control"  name="password" type="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group text-center m-t-30">
                            <div class="col-xs-12">
                                <button type="submit"  class="btn btn-custom btn-bordred btn-block waves-effect waves-light">Log In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</body>


<!-- jQuery  -->
<script src="{{asset('adminto/assets/js/jquery.min.js')}}"></script>



</html>
