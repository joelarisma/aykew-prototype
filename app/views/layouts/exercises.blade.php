<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=9;IE=edge,chrome=1" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0, minimal-ui">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet"/>
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,800,700,600,300" rel="stylesheet"/>
    <link href="/styles/activity.css" rel="stylesheet"/>
    <link href="/images/favicon.ico" rel="shortcut icon"/>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script>
    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    </script>
</head>
<body>
    @yield('content')
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-12417112-6', 'auto');
  ga('send', 'pageview');

  //Prevents backspace key, except on writables
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
</script>
</body>
</html>
