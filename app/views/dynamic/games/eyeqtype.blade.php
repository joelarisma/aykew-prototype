@extends('layouts.home')

@section('content')
<style>
#canvas {
	display: block;
	margin: 20px auto;
	border-radius: 5px;
}
</style>
<script>ImpactPrefix='/games/eyeqtype/';</script>
<script src="/games/eyeqtype/lib/impact/impact.js"></script>
<script src="/games/eyeqtype/lib/game/main.js"></script>
<div id="eyeqtypeGame" data-user-game-id="{{$userGame}}" data-timer="{{\Carbon\Carbon::now()}}">
	<canvas id="canvas"></canvas>
</div>
@stop

@section('script')
<script>
    $(document).ready(function() {
        var timeSpentDelta = 15; // Seconds
        var timeSpentTimer = setInterval(updateTimeSpent, timeSpentDelta*1000);
        
        function updateTimeSpent() {
            var game_id = $('#eyeqtypeGame').attr('data-user-game-id');
            var dateTime = $('#eyeqtypeGame').attr('data-timer');
        
            var url = '/update-game-activity';
            $.ajax({
                method: 'post',
                url: url,
                dataType: 'json',
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
