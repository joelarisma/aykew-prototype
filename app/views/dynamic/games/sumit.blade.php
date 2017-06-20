@extends('layouts.home')

@section('content')
<script src="/games/sumon/lib/caat.js"></script>
<script src="/games/sumon/lib/cocoonjs/ads.js"></script>
<script src="/games/sumon/game/namespace.js"></script>
<script src="/games/sumon/game/context.js"></script>
<script src="/games/sumon/actor/garden.js"></script>
<script src="/games/sumon/actor/gamescene.js"></script>
<script src="/games/sumon/actor/main.js"></script>

<div id="page-wrapper" style="min-height:0px;">
    <div class="row" style="padding-top:15px;">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
            <div class="panel panel-default" style="background:rgba(255,255,255,0);">
                <div id="sumitGame" data-user-game-id="{{$userGame}}" data-timer="{{\Carbon\Carbon::now()}}" class="panel-body">
					<canvas id="_c1" width="800px" height="536px" style="margin:auto;display:block;border-radius: 5px;border: 10px solid #FFF;"></canvas>
				</div>
			</div>
		</div>
		<div class="col-lg-1"></div>
	</div>
</div>
@stop

@section('script')
<script>
    $(document).ready(function() {
        var timeSpentDelta = 15; // Seconds
        var timeSpentTimer = setInterval(updateTimeSpent, timeSpentDelta*1000);
    
        function updateTimeSpent() {
            var game_id = $('#sumitGame').attr('data-user-game-id');
            var dateTime = $('#sumitGame').attr('data-timer');

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
