<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui">
    <title>eyeQ Speed Reading</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,800,700,600,300" rel="stylesheet"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css" rel="stylesheet"/>
    <link href="/styles/eyeq.css" rel="stylesheet"/>
    <link href="/styles/guest_activity.css" rel="stylesheet"/>
    <link href="/images/favicon.ico" rel="shortcut icon"/>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!-- include analytics here -->
</head>
<body style="background:#eee;">
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top navbar-mobile" role="navigation" style="margin-bottom: 0;height:75px;">
            <div class="container">
                <span style="text-align: center;position: absolute;left: 100px;right: 100px;margin-left: auto;margin-right: auto;">
                    <img src="/images/eyeQ_Logo_Gradient_Blue_RGB.png" style="margin:auto; height:60px;"/>
                </span>
            </div>
        </nav>
        <nav class="navbar navbar-default navbar-static-top navbar-main" role="navigation" style="margin-bottom:0">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/eyeq/"><img src="/images/eyeQ_Logo_Gradient_Blue_RGB.png" style="margin-top:-28px; height: 72px;"/></a>
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

<!-- Custom JavaScript for the Side Menu -->
<script>
window.onload=function() {
    $('.selectpicker').selectpicker({
        'selectedText': 'cat'
    });

    (function ( window, document, undefined ) {

      /*
       * Grab all iframes on the page or return
       */
      var iframes = document.getElementsByTagName( 'iframe' );

      /*
       * Loop through the iframes array
       */
      for ( var i = 0; i < iframes.length; i++ ) {

            var iframe = iframes[i],

            /*
               * RegExp, extend this if you need more players
               */
            players = /www.youtube.com|player.vimeo.com/;

            /*
             * If the RegExp pattern exists within the current iframe
             */
            if ( iframe.src.search( players ) > 0 ) {

                  /*
                   * Calculate the video ratio based on the iframe's w/h dimensions
                   */
                  var videoRatio        = ( iframe.height / iframe.width ) * 100;

                  /*
                   * Replace the iframe's dimensions and position
                   * the iframe absolute, this is the trick to emulate
                   * the video ratio
                   */
                  iframe.style.position = 'absolute';
                  iframe.style.top      = '0';
                  iframe.style.left     = '0';
                  iframe.width          = '100%';
                  iframe.height         = '100%';

                  /*
                   * Wrap the iframe in a new <div> which uses a
                   * dynamically fetched padding-top property based
                   * on the video's w/h dimensions
                   */
                  var wrap              = document.createElement( 'div' );
                  wrap.className        = 'fluid-vids';
                  wrap.style.width      = '100%';
                  wrap.style.position   = 'relative';
                  wrap.style.paddingTop = videoRatio + '%';

                  /*
                   * Add the iframe inside our newly created <div>
                   */
                  var iframeParent      = iframe.parentNode;
                  iframeParent.insertBefore( wrap, iframe );
                  wrap.appendChild( iframe );
            }
        }
    })( window, document );
};

var $buoop = {vs:{i:10,f:20,o:17,s:6},text: "You are using an <b>outdated browser</b>. For the best experience with eyeQ, we highly recommend that you <b>upgrade/update your browser</b> to the latest version of Google Chrome.",newwindow: true};
$buoop.ol = window.onload;
window.onload=function(){
     try {if ($buoop.ol) $buoop.ol();} catch (e) {}
     var e = document.createElement("script");
     e.setAttribute("type", "text/javascript");
     e.setAttribute("src", "/js/browser-update.js");
     document.body.appendChild(e);
}

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
