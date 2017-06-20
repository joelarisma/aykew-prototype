@extends('layouts.exercises')

@section('content')
<div id="instructions" class="modal" data-backdrop="static" data-keyboard="false" id="speed" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div id="timer_div" class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="inst_header">
                    <i class="fa fa-eye" style="font-size:150%;"></i> Eye Training Exercises
                </h4>
            </div>
            <div id="inst_msg" class="modal-body text-center"></div>
            <div class="modal-footer text-center">
                <button id="begin_exercise" class="btn btn-success btn-lg btn-block">Begin Exercise </button>
            </div>
        </div>
    </div>
</div>

<header class="games-header">
    <div class="header_container">
        <div class="header_left_div">
            <a href="/{{ $url }}"><i class="fa fa-home"></i></a>
        </div>
    </div>
</header>

<div class="games-container overthrow">
    <div class="games-inner-container">
        <div id="ex" style="cursor:none"></div>
    </div>
</div>

<footer class="games-footer">
    <button class='pause_outerpanel' id='cmdpause' value='Pause'><i class='fa fa-pause'></i></button>
    <div class="game_round_status" id="game_round_status_bar">
        <div class="wpm">
            <span id="wpm" style="color: #ffffff"></span>
        </div>
        <div class="round_img_container">
        @foreach ($exercise_order as $no)
            @if ($current_exercise->id < $no->id)
            <span id='round_{{$no->id}}' class='round_remaining'></span>
            @else
            <span id='round_{{$no->id}}' class='round_completed'></span>
            @endif
        @endforeach
        </div>
    </div>
    <div id="loading" style="display:none;">
        <div style="color:#fff;text-align:center;">Loading...</div>
    </div>
</footer>

<form action="/{{ $url }}/session" method="POST" id="exerciseform">
    <input type="hidden" name="session_id" value="{{ $session_id }}">
    <input type="hidden" name="step" value="{{ $current_step }}">
    <input type="hidden" name="score" value="exercise">
    <input type="hidden" name="time_spend" value="{{ \Carbon\Carbon::now() }}">
</form>

<script src="/js/howler.min.js"></script>
<script src="/js/exercise.js"></script>
<script src="/js/splittext.js"></script>
<script>
    var ex = {{ $ex }};
    //  {"id":24,
    // "name":"Exercise 24 - Left\/Right, Top\/Bottom",
    // "identifier":"ex24",
    // "instructions":"Follow the object with your eyes as it moves around the screen.<br>This is a warm up exercise for your eye muscles.<br><br>Hold mobile devices closer to your eyes for maximum effect.",
    // "speeds":[400,300,350]}

    // Global values accessed by individual exercises
    var slowWpm = {{ $slow_wpm }};
    var mediumWpm = {{ $medium_wpm }};
    var fastWpm = {{ $fast_wpm }};
    var superfastWpm = {{ $superfast_wpm }};
    var wordsNeeded = {{ $words }};
    var api_dir = '/course/';

    $(function(){
        showInstructions();
    });

    // show instructions and load individual exercise
    // the exercise will call showStart() when it has initialized and loaded its assets
    // it will then watch for #begin_exercise.click to start
    function showInstructions()
    {
        $(".pause_outerpanel").hide();
        $('#instructions').modal('show');
        $('#inst_header').html("<i class='fa fa-info-circle' style='font-size:150%;'></i> Exercise <span id='exid'></span> - Instructions");
        $('#begin_exercise').show();
        $("#begin_exercise").css('background-color','#fff');
        $("#begin_exercise").prop('disabled', true);
        $("#inst_msg").html(ex.instructions);
        $("#exid").html(ex.id);
        $("#instructions").modal('show');
        exIdent = ex.identifier;
        // load html for exercise
        $("#ex").load("/exercises/"+exIdent+".html", function() {
            // after html, load js for exercise
            $.ajaxSetup({cache: true});
            $.getScript("/exercises/"+exIdent+".js")
                // if js loads ok, run js to initialize
                .done(function( script, textStatus) {
                    window[exIdent](ex.speeds);  // Note: this calls the js
                })
                .fail(function(jqxhr, textStatus, errorThrown) {
                    console.log(jqxhr.status);
                    console.log(textStatus);
                    console.log(errorThrown);
                });
        });
    }

    // called by each exercise when exercise is ready to start
    function showStart() {

        $("#begin_exercise").prop('disabled', false);
        $("#begin_exercise").css('background-color','#5cb85c');
    }

    // called by each exercise when started to hide instructions
    function hideInstructions() {
        $("#instructions").modal('hide');
        $(".pause_outerpanel").show();
    }

    // called by each exercise when it is finished
    function exerciseEnded() {
        $(".pause_outerpanel").hide();
        hideWpm();
        finishAll();
    }

    /* The following functions may be called from within individual exercises */

    function togglePause()
    {
        if ($("#cmdpause").val()== "Pause")
        {
            $("#cmdpause").val("Resume");
            $("#cmdpause").empty().append("<i class='fa fa-play'></i>");
        }
        else
        {
            $("#cmdpause").val("Pause");
            $("#cmdpause").empty().append("<i class='fa fa-pause'></i>");
        }
    }
    function showWpm(wpm)
    {
        $('#wpm').html(wpm +' WPM');
        $('#wpm').show();
    }
    function showSlowWpm()
    {
        showWpm(slowWpm);
    }
    function showMediumWpm()
    {
        showWpm(mediumWpm);
    }
    function showFastWpm()
    {
        showWpm(fastWpm);
    }
    function showSuperfastWpm() {
        showWpm(superfastWpm);
    }
    function hideWpm()
    {
        $('#wpm').html('');
        $('#wpm').hide();
    }

    function finishAll()
    {
        $("#exerciseform").submit();
    }
</script>
@stop
