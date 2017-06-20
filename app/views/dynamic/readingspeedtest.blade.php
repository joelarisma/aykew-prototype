@extends('layouts.home')

@section('content')
<div id="page-wrapper">
    <div class="row" style="padding-top:15px;">
        <div class="col-lg-12">
            <div class="panel panel-default posttest" id="instructions">
                <div class="panel-body text-center">
                    <div class="col-md-8 col-md-offset-2">
                        <div id="description_div" class="blur text-justify">
                            <h4 class="test_name text-center">{{ $test->test_name }}</h4>
                            <p class="text-left">{{ $test->description }}</p>
                            <br>
                            <div class="button-box text-center">
                                <input type="button" id="doneBtn" value="Done" class="btn btn-success btn-lg" style="display:none;"/>
                            </div>
                        </div>

                        <div class="modal fade" data-backdrop="static" data-show="true" data-keyboard="false" id="instruction_screen_div" tabindex="-1" role="dialog" aria-hidden="false">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title text-center" id="myModalLabel">{{ $type }}-Training Speed Test</h4>
                                    </div>
                                    <div class="modal-body text-left">
                                        This is a timed test, but read at your natural pace. The timer will start when you click the <b>Start Reading</b> button:</br>
                                        <br><em>When you have completed your reading, click the <b>Done</b> button</em>.
                                    </div>
                                    <div class="modal-footer text-center">
                                        <input type="button" id="start_now"  value="Start Reading" class="btn btn-success btn-lg btn-block"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="speed" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title text-center" id="myModalLabel">{{ $type }}-Training Test Results</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="test_result_div">
                                                <div>Today's <!-- ' --> {{ $type }}-Training Reading Speed:</div>
                                                <div class="game_timer_center_div" style="color: #5BC0DE;">
                                                    <h2 id="wpm" style="font-size:400%;font-weight:bold;"></h2><h5>WPM</h5>
                                                </div>
                                                <div class="game_timer_bottom_div">
                                                    <h6>You read <span id="words"></span> words in <span id="time-span"></span> seconds.</h6>
                                                </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer text-center">
                                        <input type="button" id="continue" value="{{ $last_test ? 'Record My Score' : 'Next'}}" class="btn btn-success btn-lg btn-block"/>
                                        <div style="text-align:center; display:none;" id="loading">
                                            Saving Scores...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <br><br>
<style>
.blur {
color: rgba(0, 0, 0, 0);
text-shadow: 0 0 20px #333;
}
</style>
<form action="/{{ $url }}/session" method="POST" id="testform">
    <input type="hidden" name="session_id" value="{{ $session_id }}">
    <input type="hidden" name="step" value="{{ $current_step }}">
    <input type="hidden" name="test_id" value="{{ $test->id }}">
    <input type="hidden" name="score" value="" id="form_score">
    <input type="hidden" name="time_spend" value="{{ \Carbon\Carbon::now() }}">
</form>
<script>
var test_start_time = 0;
var wordsPerMinute;
var doneColor;
var wordcount;
$(function(){
    $('#instruction_screen_div').modal('toggle');
    $("#start_now").on('click', startRead);

    function startRead() {
        wordcount = $("#description_div").text().split(/\b\S+\b/g).length-1;
        var mstime = wordcount / 0.0333;  // time to read at 2000 wpm
        doneColor = $("#doneBtn").css('backgroundColor');
        $("#doneBtn").prop('disabled', true);
        $("#description_div").removeClass('blur');
        $("#doneBtn").toggle();
        $('#instruction_screen_div').modal('toggle');
        var datestart = new Date();
        test_start_time = datestart.getTime() / 100;
        setTimeout(showDone, mstime);
    }

    // don't let them read faster than 2000 wpm
    function showDone() {
        $("#doneBtn").on('click', doneRead);
        $("#doneBtn").css('backgroundColor', doneColor);
        $("#doneBtn").prop('disabled', false);
    }

    function doneRead() {
        $("#doneBtn").off('click', doneRead);
        if (test_start_time == 0)
            return false;
        var dateend = new Date();
        var test_end_time = dateend.getTime() / 100;
        var total_time = Math.round(test_end_time - test_start_time); // tenths-of-seconds
        wordsPerMinute = Math.round((wordcount/total_time)*600);
        $("#wpm").html(wordsPerMinute);
	$("#form_score").val(wordsPerMinute);
        $("#time-span").html(total_time / 10);
        $("#words").html(wordcount);
        $("#doneBtn").toggle();
        $("#description_div").addClass('blur');
        $('#speed').modal('toggle');
        $("#continue").on('click', saveScore);
    }

    function saveScore() {
        $("#continue").off('click', saveScore);
        $("#testform").submit();
    }
});
</script>
@stop