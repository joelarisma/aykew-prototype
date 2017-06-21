@extends('layouts.home')

@section('content')

<div id="page-wrapper">
    <div class="row" style="padding-top:15px;">
        <div class="col-lg-12">
            <div class="panel panel-default" id="testscreen" style="display:none;">
                <div class="panel-heading text-center">{{ $test->test_name }}</div>
                <div class="panel-body text-justify" id="testcontent">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-8">
                        <span id="content">
                        {{ $test->description }}
                        </span>
                        <br><br>
                        <div id="lastParagraph" class="text-center">
                            <input type="button" id="doneBtn" value="Done" class="btn btn-success" style="display:none;"/>
                        </div>
                    </div>
                    <div class="col-lg-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="instructions" class="modal" data-backdrop="static" data-keyboard="false" id="speed" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="inst_header">
                    Comprehension Test
                </h4>
            </div>
            <div id="inst_msg" class="modal-body text-center">
                <p>
                    As you learn to read faster, your comprehension may decrease temporarily.
                    This is because you are training your eyes to move faster and widen your field-of-vision.
                    After you achieve these new skills, your comprehension will increase.
                </p>
                <p>
                    <strong>Instructions:</strong>
                    Press <strong>Begin Test</strong> to view a short story.
                    As soon as the story displays, a timer will start.
                    Start reading the story immediately. After reading the story,
                    press the <strong>Done</strong> button to stop the timer.
                    Answer 10 questions based on the story and view your Effective Reading Speed (ERS).
                </p>
                <p>
                    This is how ERS is calculated:<br>
                    ERS = Words Per Minute x Percent Answers Correct
                </p>
            </div>
            <div class="modal-footer text-center">
                <input type="button" value="Begin Test" id="start" class="btn btn-success btn-lg btn-block"/>
                {{--<button id="start" class="btn btn-success btn-lg btn-block">Begin Exercise </button>--}}
            </div>
        </div>
    </div>
</div>
<form action="{{ url('session', $session_level) }}" method="POST" id="testform">
    <input type="hidden" name="exercise_id" value="{{ $test->id }}">
    <input type="hidden" name="session_exercise_id" value="{{ $session_exercise->id }}">
    <input type="hidden" name="session_exercise_type_id" value="{{ $session_exercise->type->id }}">
    <input type="hidden" name="wordcount" value="">
    <input type="hidden" name="seconds" value="" id="seconds">
    <input type="hidden" name="time_spend" value="{{ \Carbon\Carbon::now() }}">
</form>
<script>
var starttime;
var totalPara;
var curPara;
var wordcount;
var totalTime;
var wordsPerMinute;

//new
var seconds = 0;
var readTimer;

$(function(){
    $('#instructions').modal('show');
    $("#start").on('click',startRead);
});

function startRead() {
    $('#instructions').modal('hide');
    $("#start").off('click',startRead);

    wordcount = {{ str_word_count(trim(str_replace('  ', ' ', strip_tags($test->description)))) }};
    //wordcount = $("#content").text().split(/\b\S+\b/g).length-1;
    var mstime = wordcount/.033333;  // time to read at 2000 wpm
    doneColor = $("#doneBtn").css('backgroundColor');
    $("#doneBtn").prop('disabled', true);
    $("#instructions").hide();
    $("#testscreen").show();
    $("#doneBtn").toggle();
    $('#instruction_screen_div').modal('toggle');
    
    //old
    var datestart = new Date();
    starttime = datestart.getTime()/100;

    readTimer = setInterval(() => {
            seconds++;
            $('#show-timer').html(seconds/10);
        }, 100);

    setTimeout(showDone, mstime);
}

// don't let them read faster than 1000 wpm
function showDone() {
    $("#doneBtn").on('click',doneRead);
    $("#doneBtn").css('backgroundColor', doneColor);
    $("#doneBtn").prop('disabled', false);
}

function _doneRead() {
    var dateend = new Date();
    var endtime = dateend.getTime()/100;
    $("#doneBtn").off('click',doneRead);
    wordcount = $("#content").text().split(/\b\S+\b/g).length-1;
    totalTime = Math.round(endtime - starttime); // tenths-of-seconds
    wordsPerMinute = Math.round((wordcount/totalTime)*600);
    totalTime = parseInt(endtime) - parseInt(starttime);
    $('#lastParagraph').html('Generating Test Questions...');
    //$("#wpm").val(wordsPerMinute);
    //$("#wcnt").val(wordcount);
    //$("#time").val(totalTime);
    $("#form_score").val(wordsPerMinute);
    $("#testform").submit();
}

function doneRead() {
        $("#doneBtn").off('click', doneRead);
        
        if(readTimer)
            clearInterval(readTimer);

        //new
        var wpm = Math.round((wordcount/(seconds/10)) * 60);
        seconds = seconds/10;

        $('input[name="wordcount"]').val(wordcount);
        $('input[name="seconds"]').val(seconds);

        $('#lastParagraph').html('Generating Test Questions...');

        $("#testform").submit();
    }

</script>
@stop
