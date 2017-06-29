@extends('layouts.games')

@section('content')

<?php
    $test_type = '';
    $last_test = $is_last;
?>
    <div class="center_div">
        <div id="instructions" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div id="timer_div" class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center" id="myModalLabel">Eye Speed Test</h4>
                    </div>
                    <div id="twopt-msg" class="modal-body text-center">
                        <img src="/images/2pointloop.gif" class="two-point-gif">
                        Scan the squares <strong>with your eyes</strong> back and forth from top to bottom.<br><br>
                        Press <strong>Done</strong> when you reach the last line.
                    </div>
                    <div class="modal-footer text-center">
                        <input type="button" id="startBtn" value="Begin" class="btn btn-success btn-lg btn-block">
                    </div>
                </div>
            </div>
        </div>

        <div id="completed" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div id="timer_div" class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center" id="myModalLabel">Eye Speed Test</h4>
                    </div>
                    <div id="twopt-msg" class="modal-body text-center">
                        <em>You scanned 11 lines in <span id="test_time"></span> seconds.</em><br />
                        Your <strong>Eye Muscle Power</strong> is
                        <h2 style="margin:15px; text-decoration:italic" id="test_score"></h2>
                        This eye speed, dictates the user's image exercise speeds.<br /><br />
                        @if($test_type == "pretest")
                        Press <strong>Continue</strong> to begin your daily exercises.
                        @endif
                    </div>
                    <div class="modal-footer text-center">
                        <input type="button" id="finishBtn" value="{{ $last_test ? 'Finish' : 'Continue' }}" class="btn btn-success btn-lg btn-block">
                    </div>
                </div>
            </div>
        </div>

        <div id="training-animation-div" class="twopoint_training_box twopoint_training_captionbtmbox" style="display:none;">
            <div class="listing_line">
                <ul class="twoleft">
                @for($i = 0; $i < 11; $i++)
                    <li>{{ $i*2+1 }}</li>
                @endfor
                </ul>
                <div class="line line_box">
                    <svg width="100%" height="900">
                        <defs>
                            <marker id="arrow" markerWidth="10" markerHeight="10" refx="0" refy="3" orient="auto" markerUnits="strokeWidth">
                                <path d="M0,0 L0,6 L9,3 z" fill="#000" />
                            </marker>
                            <marker id="arrow2" markerWidth="10" markerHeight="10" refx="0" refy="3" orient="auto" markerUnits="strokeWidth">
                                <path d="M0,0 L0,6 L9,3 z" fill="#f00" />
                            </marker>
                        </defs>
                    @for($i = 0, $ybase = 24, $yoffset = 45; $i < 11; $i++)
                        <line class="fw" x1="10" y1="{{ $y = (($yoffset * $i) + $ybase) }}" x2="95%" y2="{{ $y }}" stroke="#000" stroke-width="2" marker-end="url(#arrow)" />
                        @if($i != 10)
                        <line class="bw" x1="95%" y1="{{ $y }}" x2="25" y2="{{ $y + $yoffset }}" stroke="#000" stroke-width="2" marker-end="url(#arrow)" />
                        @endif
                    @endfor
                    </svg>
                </div>
                <div class="line line_box" id="testText" style="display:none;">
                    <h1>hh</h1>
                </div>
                <ul class="tworight">
                @for ($i = 0; $i < 11; $i++)
                    <li>{{ $i*2+2 }}</li>
		@endfor
		</ul>
	    </div>
        </div>
    </div>
		    
    <div class="bottom_caption_sec_2pt">
         <div class="buttom_positon">
             <input type="button" id="doneBtn" value="Done" style="display:none;" class="btn btn-success">
         </div>
     </div>

    <form action="{{ url('session', $session_level) }}" method="POST" id="testform">
        <input type="hidden" name="session_exercise_id" value="{{ $session_exercise->id }}">
        <input type="hidden" name="session_exercise_type_id" value="{{ $session_exercise->type->id }}">
        <input type="hidden" name="seconds" value="" id="seconds">
        <input type="hidden" name="time_spend" value="{{ \Carbon\Carbon::now() }}">
    </form>
 		    
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script>

var objTimer;
var seconds;
var stepSec;

function startTimer() {
    seconds = 0;
    objTimer = setInterval(function() { seconds++; }, 100); // count .1 second intervals
}

function stopTimer() {
    clearInterval(objTimer);
    return seconds;
}

function adjustLines() {
    var width = $("svg").width();
    $("svg line.fw").attr('x2', width-25+"px");
    $("svg line.bw").attr('x1', width-10+"px");
}

function blurgame(blur) {
    $("#game-container").css({'filter': 'blur('+blur+')','-webkit-filter': 'blur('+blur+')','-moz-filter': 'blur('+blur+')',
	  '-o-filter': 'blur('+blur+')','-ms-filter': 'blur('+blur+')'});
}

function noblur() {
    $("#game-container").css({'filter': 'none','-webkit-filter': 'none','-moz-filter': 'none',
	  '-o-filter': 'none','-ms-filter': 'none'});
}

$(function() {
    $("#instructions").modal('toggle');
    $("#training-animation-div").show();
    adjustLines();
    $("#startBtn").on('click', showStep);
});

function showStep() {
    $("#startBtn").off('click', showStep);
    $("#instructions").modal('hide');
    $("#doneBtn").show();
    $("#doneBtn").on('click', doneStep);
    startTimer();
}

function doneStep() {
    var time = stopTimer();
    $("#doneBtn").off('click', doneStep);
    $("#doneBtn").hide();
    seconds = time / 10;
    $("#test_time").html(seconds);
    var score = Math.round((22*5*10) / seconds);
    $("#test_score").html(score);
    $("#seconds").val(seconds);
    $("#completed").modal('show');
    /*stepSec = time;
    $("#test_time").html(stepSec / 10);
    var score = (22*5*10) / stepSec;
    score = Math.round(score*10) / 10;
    $("#test_score").html(score);
    //$("#form_score").val(score);
    $("#completed").modal('show');*/
    $("#finishBtn").on('click', allDone);
}

function allDone() {
    $("#finishBtn").off('click', allDone);
    $("#loading").show();
    exerciseDone();
}

function exerciseDone() {
    $("#testform").submit();
}

$(window).resize(function() {
    adjustLines();
});

    </script>
@stop