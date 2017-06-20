@extends('layouts.home')

@section('content')

<link rel="stylesheet" type="text/css" href="/games/hamster/game.css">	
<script type="text/javascript" src="/games/hamster/game.js"></script>

<div id="ajaxbar">
	<div id="game" style="top:75px" data-user-game-id="{{$userGame}}" data-timer="{{\Carbon\Carbon::now()}}"><canvas id="canvas"></canvas></div>
	
	<div id="orientate"><img src="/games/hamster/media/graphics/orientate/landscape.jpg" alt=""></div>
	<div id="play" class="play" onclick=""><img src="/games/hamster/media/graphics/splash/mobile/cover-start.jpg" alt=""></div>	
        <img id="scrollDown" width="220" height="277" src="#" alt="">
</div>
    
<div id="tempdiv"><br><br><br></div>
@stop

@section('script')
<script>
    $(document).ready(function() {
        var timeSpentDelta = 15; // Seconds
        var timeSpentTimer = setInterval(updateTimeSpent, timeSpentDelta*1000);
    
        function updateTimeSpent() {
            var game_id = $('#game').attr('data-user-game-id');
            var dateTime = $('#game').attr('data-timer');
            var url = '/update-game-activity';
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    game_id:game_id,
                    dateTime:dateTime
                },
                success:function(data){
                    console.log('Data successfully added');
                }
            });
        }
    });
</script>
@stop
