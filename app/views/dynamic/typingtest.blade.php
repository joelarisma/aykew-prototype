@extends("layouts.home")

@section('content')
<style>
.blur {
    color: rgba(0, 0, 0, 0);
    text-shadow: 0 0 10px #333;
}
</style>
<div id="page-wrapper">
    <div class="row" style="padding-top:15px;">
        <div class="col-lg-12">
            <div class="modal fade" data-backdrop="static" data-show="true" data-keyboard="false" id="testInstructions" tabindex="-1" role="dialog" aria-hidden="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title text-center" id="myModalLabel">eyeQ Typing Speed Test</h4>


                        </div>
                        <div class="modal-body text-left">
                            <p>This 60 second typing test will calculate your net typing speed (a combination of gross typing speed and accuracy).<br><br>
                                <strong>Instructions:</strong><br>
                                Use your keyboard to quickly and accurately type the passage in the upper box into the lower box.<br><br><em>The timer begins as soon as you start typing.</em>
                        </div>
                        <div class="modal-footer text-center">
                            <button type="button" class="btn btn-success btn-lg btn-block" id="showTest">Begin the Test</button>

                        </div>
                    </div>
                </div>
            </div>
            <form name="JobOp" id="theTest">
                <div id="scores" class="panel panel-default" style="display:block;">
                    <div class="panel-body">
                        <div class="col-md-12 text-center" style="color:#5CB85C;">
                            <h4>Gross Typing Speed : <strong id="stat_wpm">0</strong></h4>
                        </div>
                    </div>
                </div>
                <div id="expectedArea" class="panel panel-default" style="display:block">
                    {{-- Show Description if Content  not found or null--}}
                    <textarea class="blur" name="given" cols="53" rows="7" wrap="on" onFocus="deterCPProtect();" style="width: 90%; border: none; padding:5px; margin: 5px auto; font-family:Arial; font-size:10pt; resize: none; display:block;">{{{ $test->content == '' ? $test->description : $test->content }}}</textarea>
                </div>
                <div id="typeArea" class="panel panel-default" style="display:block">
                    <div class="progress">
                        <div id="stProg" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="4" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        <span id="thisProg"></span>
                        </div>
                    </div>
                    <textarea onkeypress="doCheck();" name="typed" id="typingarea" cols="53" rows="7" wrap="on" spellcheck="false" style="width: 90%; border: none; margin: 5px auto; font-family:Arial; font-size:10pt; resize: none; display:block; border:1px solid #ddd;" placeholder="Type here. The timer will begin when you begin typing."></textarea><br><br>
                </div>
                <div class="modal fade" data-backdrop="static" data-keyboard="false" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title text-center" id="myModalLabel">Your Typing Speed Test Results</h4>
                      </div>

                      <div class="modal-body">
                          <form name="JobOp">
                            <div id="scores" class="panel panel-primary" style="display:block;">
                                <div class="panel-body">
                                    <div class="col-md-4 text-center" style="color:#5CB85C;">
                                        <h4>Typing Errors</h4>
                                        <strong><div id="modal_stat_errors">...</div></strong>
                                    </div>
                                    <div class="col-md-4 text-center" style="color:#5BC0DE;">
                                        <h4>Accuracy</h4>
                                        <strong><div id="modal_stat_score">...</div></strong>
                                    </div>
                                    <div class="col-md-4 text-center" style="color:#D9534F;">
                                        <h4>Net/Gross Speed</h4>
                                        <strong><div id="modal_stat_wpm">0</div></strong>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="afterAction" class="text-center" style="display:none;padding:10px 10%;border:none;">
                        </div>
                      </div>
                      <div class="modal-footer">
		        {{-- <a class="btn btn-default" href="/typingtest">Retake Test</a> --}}
                        <button type="button" class="btn btn-success" id="saveScore">{{ $last_test ? 'Finish' : 'Continue' }}</button>
                    </div>
                    </div>
                  </div>
                </div>


            </form>
        </div>
    </div>
</div>

<form action="/{{ $url }}/session" method="POST" id="testform">
    <input type="hidden" name="session_id" value="{{ $session_id }}">
    <input type="hidden" name="step" value="{{ $current_step }}">
    <input type="hidden" name="test_id" value="{{ $test->id }}">
    <input type="hidden" name="score" value="" id="form_score">
    <input type="hidden" name="net" value="" id="form_net">
    <input type="hidden" name="pct" value="" id="form_pct">
    <input type="hidden" name="time_spend" value="{{ \Carbon\Carbon::now() }}">
</form>

<script>
$( document ).ready(function() {
    $('#testInstructions').modal('toggle');
});
var hasStarted = false;
var timerObj;
var wordCnt;
var testId = {{ $test->id }};
var wpmType;
var netType;
var pctType;

// General function to trim a string to length n
function Left(str, n){
    if (n <= 0)
        return "";
    else if (n > String(str).length)
        return str;
    else
        return String(str).substring(0,n);
}

// Deter Copy and Paste, fired if user attempts to click or apply focus to the text box containing what needs to be typed
function deterCPProtect()
{
    document.JobOp.typed.focus();
}

$(function(){
    document.JobOp.typed.focus();

    $('#showTest').click(function () {
        $('#testInstructions').modal('toggle');
        $("#expectedArea textarea").removeClass('blur');
        $("#typingarea").focus();
    })

    $("#saveScore").click(function() {
        $('#saveScore').html('Saving test results...');

	$("#form_score").val(wpmType);
	$("#form_net").val(netType);
	$("#form_pct").val(pctType/10);
	$("#testform").submit();
    });
});

// Called whenever they type a key to start test (if necessary)
function doCheck()
{
    if (hasStarted == false)
        beginTest();
}

//beginTest Function/Sub initializes the test and starts the timers to determine the WPM and Accuracy
function beginTest()
{
    hasStarted = true;
    day = new Date();
    startType = day.getTime();

    //Count the number of valid words in the testing baseline string
    wordCnt = document.JobOp.given.value.split(" ").length;

    document.JobOp.typed.value = "";
    document.JobOp.typed.focus();
    document.JobOp.typed.select();
    calcStat();
}

function endTest()
{
    clearTimeout(timerObj);
    eDay = new Date();
    endType = eDay.getTime();
    totalTime = ((endType - startType) / 1000)

    // Calculate the typing speed - Number of words is number of characters (including punctuation and spaces) divided by 5
    // by dividing the number of words typed by the total time taken, then multiplying it by 60 seconds to get WPM
    // ignore multiple spaces because people using typewriters were taught to enter 2 spaces after punctuation
    wpmType = Math.round(((document.JobOp.typed.value.replace(/  /g, " ").length/5)/totalTime) * 60);

    //Declare an array of valid words for what NEEDED to be typed and what WAS typed
    var typedValues = document.JobOp.typed.value.replace(/  /g, " ");
    var neededValues = Left(document.JobOp.given.value, typedValues.length).replace(/  /g, " ").split(" ");
    typedValues = typedValues.split(" ");

    //Disable the area where the user types the test input
    document.JobOp.typed.disabled=true;

    var goodWords = 0;
    var badWords = 0;
    var errWords = "";
    var detReport = "<b>Detailed Summary:</b><br><font color=\"DarkGreen\">";

    //Loop through the valid words that were possible (those in the test baseline of needing to be typed)
    var str;
    var i = 0;
    for (var i = 0; i < wordCnt; i++)
    {
        //If there is a word the user typed that is in the spot of the expected word, process it
        if (typedValues.length > i)
        {
            //Declare the word we expect, and the word we received
            var neededWord = neededValues[i];
            var typedWord = typedValues[i];

            //Determine if the user typed the correct word or incorrect
            if (typedWord != neededWord)
            {
                //They typed it incorrectly, so increment the bad words counter
                badWords = badWords + 1;
                errWords += typedWord + " = " + neededWord + "\n";
                detReport += "<font color=\"Red\"><u>" + neededWord + "</u></font> ";
            }
            else
            {
                //They typed it correctly, so increment the good words counter
                goodWords = goodWords + 1;
                detReport += neededWord + " ";
            }
        }
    }
    accuracyPct = ((goodWords / (goodWords+badWords)) * 100).toFixed(1);
    pctType = accuracyPct * 10;  // accuracy in tenths * 10 (so it can be stored as integer)

    // Summary
    detReport += "</font>";

    aftReport = "<b>Typing Summary:</b><br>You typed " + (document.JobOp.typed.value.replace(/  /g, " ").length);
    aftReport += " characters in " + Math.round(totalTime) + " seconds,<br> a speed of about <strong>" + wpmType + " words per minute</strong>.<br><br>You also had " + badWords;
    aftReport += " words with errors, and " + goodWords + " correct words,<br> for a typing accuracy of " + accuracyPct + "%.<br><br>"
    aftReport += detReport;

    netType = wpmType-badWords;
    $('#stat_errors').html(badWords + " Errors");
    $('#modal_stat_errors').html(badWords + " Errors");
    $('#stat_wpm').html(netType + " WPM / " + wpmType + " WPM");
    $('#modal_stat_wpm').html(netType + " WPM / " + wpmType + " WPM");
    $('#stat_timeleft').html(totalTime.toFixed(2) + " sec. elapsed");
    $('#stat_score').html(accuracyPct + "%");
    $('#modal_stat_score').html(accuracyPct + "%");

    $('#afterAction').html(aftReport.replace(/undefined/g, " "));
    $('#afterAction').show();
    $('#myModal').modal('toggle');
}

// calcStat is a function called as the user types to dynamically update the statistical information
function calcStat()
{
    //If something goes wrong, we don't want to cancel the test -- so fallback error proection (in a way, just standard error handling)
    try {

        // remove double spaces
        var thisTyped = document.JobOp.typed.value.replace(/  /g, " ");

        eDay = new Date();
        endType = eDay.getTime();
        totalTime = ((endType - startType) / 1000);
        wpmType = Math.round(((thisTyped.length/5)/totalTime) * 60);
        $('#stat_wpm').html(wpmType + " WPM");

        // Calculate and show the time taken to reach this point of the test and also the remaining time left in the test
        // Colorize it based on the time left (red if less than 5 seconds, orange if less than 15)
        var elapsed = totalTime.toFixed(1);
        var remaining = Number(60-totalTime).toFixed(1);
        var remainstring = String(Number(remaining).toFixed(0)) + ' sec left';

        // Set the progress bar
        if (elapsed >= 60)
            width = 100;
        else
            width = String(((elapsed/60)*100).toFixed(2));
        if ($('#stProg').hasClass('progress-bar-info') && remaining < 10)
        {
            $('#stProg').removeClass('progress-bar-info');
            $('#stProg').addClass('progress-bar-warning');
        }
        if ($('#stProg').hasClass('progress-bar-warning') && remaining < 5)
        {
            $('#stProg').removeClass('progress-bar-warning');
            $('#stProg').addClass('progress-bar-danger');
        }
        $('#stProg').width(width+'%');
        $('#stProg').attr('aria-valuenow',width);
        if (remaining < 55)
            $('#thisProg').html(remainstring);

        // Determine if the test is complete based on them having typed everything exactly as expected
        // or have typed the number of valid words (determined by a space) or exceeded 60 seconds
	if ( thisTyped.value == document.JobOp.given.value || wordCnt <= (thisTyped.split(" ").length) || totalTime >= 60 )
	    endTest();
	else
	    timerObj = setTimeout('calcStat();',200);    // show stats again in 250ms
    }
    // ignore any errors
    catch(e){
    };
}
</script>
@stop
