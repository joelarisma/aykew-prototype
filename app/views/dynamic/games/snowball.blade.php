@extends('layouts.home')

@section('content')

<link rel="stylesheet" type="text/css" href="/games/snowball/game.css">	
<script type="text/javascript" src="/games/snowball/game.js"></script>

<div id="ajaxbar">
	<div id="game" style="top:75px" data-user-game-id="{{$userGame}}" data-timer="{{\Carbon\Carbon::now()}}"><canvas id="canvas"></canvas></div>
	
	<div id="orientate"><img src="/games/snowball/media/graphics/orientate/landscape.png" alt=""></div>
	<div id="play" class="play" onclick=""><img src="/games/snowball/media/graphics/sprites/background/bg.png" alt=""></div>	

</div>
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