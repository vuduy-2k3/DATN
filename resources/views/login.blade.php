<!DOCTYPE html>
<html lang="en"><head>
    <title>Đăng nhập</title>
    <!-- Meta tag Keywords -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="keywords" content="Online Login Form Responsive Widget,Login form widgets, Sign up Web forms , Login signup Responsive web form,Flat Pricing table,Flat Drop downs,Registration Forms,News letter Forms,Elements">
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
    function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!-- Meta tag Keywords -->
    <!-- css files -->
    <link rel="stylesheet" href="{{ asset('login.css') }}" type="text/css" media="all"> <!-- Style-CSS --> 
    <link rel="stylesheet" href="{{ asset('font-login.css') }}"> <!-- Font-Awesome-Icons-CSS -->
    <!-- //css files -->
    <!-- online-fonts -->
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Dosis:200,300,400,500,600,700,800&amp;subset=latin-ext" rel="stylesheet">
    <!-- //online-fonts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
      <!-- Toastr JavaScript -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
      <!-- Toastr CSS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <link href="{{ asset('toastr-custom.css') }}" rel="stylesheet">
    </head>
    <body>
           <!-- Toastr initialization -->
    @include('toastr')
    <!-- main -->
    <div class="center-container">
        <!--header-->
        <div class="header-w3l">
            <h1>Đăng nhập</h1>
        </div>
        <!--//header-->
        <div class="main-content-agile">
            <div class="sub-main-w3">	
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="pom-agile">
                        <input placeholder="E-mail" id="email" name="email" class="form-control" type="email" aria-required="true">
                        @if ($errors->has('email'))
                        <div style="color: red;">
                            @foreach ($errors->get('email') as $message)
                                <p>{{ $message }}</p>
                            @endforeach
                        </div>
                    @endif
                        <span class="icon1"><i class="fa fa-user" aria-hidden="true"></i></span>
                    </div>
                    <div class="pom-agile">
                        <input placeholder="Password" id="password" name="password" class="form-control" type="password" aria-required="true">
                        @if ($errors->has('password'))
                        <div style="color: red;">
                            @foreach ($errors->get('password') as $message)
                                <p>{{ $message }}</p>
                            @endforeach
                        </div>
                    @endif
                        <span class="icon2"><i class="fa fa-unlock" aria-hidden="true"></i></span>
                    </div>
                    <div class="sub-w3l">
                        <div class="right-w3l">
                            <input type="submit" value="Login">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--//main-->
        <!--footer-->
    
        <!--//footer-->
    </div>
    
    </body></html>