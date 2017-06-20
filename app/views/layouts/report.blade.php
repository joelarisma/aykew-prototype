<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<title>eyeQ Speed Reading</title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js" property='stylesheet'></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js" property='stylesheet'></script>
<![endif]-->

<!-- global css -->
{{ HTML::style('assets/fonts/aleo_regular/stylesheet.css', array('property' => 'stylesheet')) }}
{{ HTML::style('assets/css/bootstrap.min.css', array('property' => 'stylesheet')) }}
{{ HTML::style('assets/js/select2/dist/css/select2.css', array('property' => 'stylesheet')) }}
{{ HTML::style('assets/vendors/font-awesome-4.2.0/css/font-awesome.min.css', array('property' => 'stylesheet')) }}
{{ HTML::style('assets/css/styles/black.css', array('property' => 'stylesheet')) }}
{{ HTML::style('assets/css/panel.css', array('property' => 'stylesheet')) }}
{{ HTML::style('assets/css/metisMenu.css', array('property' => 'stylesheet')) }}
{{ HTML::style('assets/css/style.css', array('property' => 'stylesheet')) }}
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet" />
<!-- end of global css -->

<!--page level css-->
@yield('header_styles')
<!--end of page level css-->
</head>

<body class="skin-josh">
<header class="header">
    <a href="{{ URL::to('/') }}" class="logo">
        <img src="/images/eyeQ_IM_Dots_Logos_Gradient_Inverse1.png" style="margin-top: 0px;" alt=""/>
    </a>
    <nav class="navbar navbar-static-top text-center" role="navigation">
        <!-- Sidebar toggle button-->
        <div>
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <div class="responsive_nav"></div>
            </a>
        </div>
        <img src="{{ asset('/images/results-01.svg') }}" class="m-l-1 hidden-xs hidden-sm">
        <span class="f-s-21 hidden-xs hidden-sm"> Results</span>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ $currentUser->gravatarLink(35) }}" width="35" class="img-circle img-responsive pull-left" height="35" alt="">
                        <div class="riot">
                            <div>
                                <span>
                                    <i class="caret"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header bg-light-blue">
                            <img src="{{ $currentUser->gravatarLink(90) }}" width="90" height="90" class="img-responsive img-circle" alt="User Image">
                            <p class="topprofiletext"></p>
                        </li>
                        <!-- Menu Body -->
                        <li>
                            <a href="#">
                                <i class="livicon" data-name="user" data-s="18"></i>
                                Profile
                            </a>
                        </li>
                        <li>
                            <a href="/">
                                <i class="livicon" data-name="users" data-s="18"></i>
                                Dashboard
                            </a>
                        </li>
                        <li role="presentation"></li>
                        <li>
                            <a href="#">
                                <i class="livicon" data-name="gears" data-s="18"></i>
                                Password
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::route("logout_path") }}">
                                <i class="livicon" data-name="sign-out" data-s="18"></i>
                                Logout
                            </a>
                        </li>
                        <!-- Menu Footer-->
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<div class="wrapper row-offcanvas row-offcanvas-left active relative">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <section class="sidebar purplebg">
            <div class="page-sidebar  sidebar-nav">
                <div class="clearfix"></div>
                <!-- BS-Task-8-FRONTEND-ONLY SIDEBAR -->
                <ul id="menu" class="page-sidebar-menu">
                    <li {{ (Request::is('/'.$url) ? 'class="active"' : '') }}>
                        <a href="{{ URL::to('/'.$url) }}">
                            <div>
                                <span class="icon-daily"></span>
                                <span class="title">Daily Training</span>
                            </div>
                        </a>
                    </li>
                @if($unlocks['u_games'])
                    <li {{ (Request::is('/'.$url.'/games') ? 'class="active"' : '') }}>
                        <a href="{{ URL::to('/'.$url.'/games') }}">
                            <div>
                                <span class="icon-game"></span>
                                <span class="title">Games</span>
                            </div>
                        </a>
                    </li>
                @endif
                @if($unlocks['u_reading'])
                    <li {{ (Request::is('/'.$url.'/reading') ? 'class="active"' : '') }}>
                        <a href="{{ URL::to('/'.$url.'/reading') }}">
                            <div>
                                <span class="icon-reading"></span>
                                <span class="title">Reading</span>
                            </div>
                        </a>
                    </li>
                @endif
                    <li {{ (Request::is($url.'/reports') ? 'class="active"' : '') }}>
                        <a href="{{ URL::to('/'.$url.'/reports') }}">
                            <div>
                                <span class="icon-result"></span>
                                <span class="title">Results</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="http://infinitemind.io/eyeq-faq-infinite-mind/" target="_blank">
                            <div>
                                <span class="icon-faq"></span>
                                <span class="title">FAQ</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- END FRONT END ONLY SIDEBAR -->
        </section>
    </aside>
    <aside class="right-side">
        <!-- Notifications -->
        @include('josh-theme/notifications')

        <!-- Content -->
        @yield('content')
    </aside>
    <!-- right-side -->
</div>

<!-- global js -->
{{ HTML::script('assets/js/jquery-1.11.1.min.js') }}
{{ HTML::script('assets/vendors/form_builder1/js/jquery.ui.min.js') }}
{{ HTML::script('assets/js/bootstrap.min.js') }}
{{ HTML::script('assets/js/josh.js') }}
{{ HTML::script('assets/js/metisMenu.js') }}
{{ HTML::script('assets/vendors/holder-master/holder.js') }}
{{ HTML::script('assets/js/select2/dist/js/select2.js') }}
<!-- end of global js -->

<!-- begin page level js -->
@yield('footer_scripts')
<!-- end page level js -->

<!-- growl -->
{{--{{ HTML::script('js/jquery.bootstrap-growl.min.js') }}--}}

@yield('script')
</body>
</html>
