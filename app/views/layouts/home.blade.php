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
    @yield("style")
</head>
<body>
@yield('content')
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js" async defer></script>
<script>

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
