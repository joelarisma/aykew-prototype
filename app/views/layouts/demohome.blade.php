<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui">
    <title>eyeQ Speed Reading</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css" rel="stylesheet"/>
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,800,700,600,300" rel="stylesheet"/>
    <link href="/styles/eyeq.css" rel="stylesheet"/>
    <link href="/images/favicon.ico" rel="shortcut icon"/>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!-- include analytics here -->
</head>
<body>

<div id="wrapper">
    <nav class="navbar navbar-default navbar-static-top navbar-mobile" role="navigation" style="margin-bottom: 20px;">
        <div class="container">
            <span style="text-align: center;position: absolute;left: 100px;right: 100px;margin-left: auto;margin-right: auto;">
                <img src="/images/eyeqlogowhite.png" style="margin:auto;height: 60px;"/>
            </span>
        </div>
    </nav>
    <nav class="navbar navbar-default navbar-static-top navbar-main" role="navigation" style="margin-bottom: 0">
        <div class="container">
            <div class="navbar-header">
<!--                <a class="navbar-brand" href="#">-->
                    <img src="/images/eyeqlogowhite.png" style="height: 60px;"/>
<!--                </a>-->
            </div>
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>
</div>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-12417112-6', 'auto');
  ga('send', 'pageview');

</script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.session.js') }}"></script>

@yield('script')
<script>
window.onload=function() {
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
        $("#sidebar-wrapper").toggleClass("active");
    });
    $("#menu-toggle-left").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("activeleft");
        $("#sidebar-wrapper-left").toggleClass("active");
    });
    $('.selectpicker').selectpicker({
        'selectedText': 'cat'
    });
    $('.dropdown').tooltip('hide');

    // Prevents backspace key, except on writables
    $(document).bind("keydown keypress", function(deleteEvent){

         // KeyCode 8 is backspace
        if ( deleteEvent.which == 8 ) {
        
            // Enable backspace for these elements
            if (deleteEvent.target.tagName ===  "INPUT" ||
                 deleteEvent.target.tagName === "SELECT" ||
                 deleteEvent.target.tagName === "TEXTAREA" ||
                 deleteEvent.target.id ==       "editor") {
            
                 return;
             }

             // Prevent all other backspaces
             deleteEvent.preventDefault();
         }
    });   
};
</script>
</body>
</html>
