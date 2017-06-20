@extends('layouts.home')

@section('content')

<link rel="stylesheet" type="text/css" href="/games/touchdown/game.css">
<script type="text/javascript" src="/games/touchdown/game.js"></script>

<!-- <div class="gamecenter-activator"></div> -->

<div id="ajaxbar">
    <div id="game" style="top:75px" data-user-game-id="{{$userGame}}" data-timer="{{\Carbon\Carbon::now()}}"><canvas id="canvas"></canvas></div>

    <div id="orientate"><img src="/games/touchdown/media/graphics/orientate/landscape.jpg" alt=""></div>
    <div id="play" class="play" onclick=""><img src="/games/touchdown/media/graphics/splash/mobile/cover-start.jpg" alt=""></div>

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
