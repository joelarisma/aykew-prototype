@extends('layouts.games')

@section('content')
<div style="clear: both;"></div>
<section>
<div class="center_div">
 
 <form action="{{ url('session', $session_level) }}" name="questionFrm" id="questionFrm" method="POST">

    <input type="hidden" name="exercise_id" value="{{ $test_id }}">
    <input type="hidden" name="wpm" value="{{ $wpm }}">
    <input type="hidden" name="session_exercise_id" value="{{ $session_exercise->id }}">
    <input type="hidden" name="session_exercise_type_id" value="{{ $session_exercise->type->id }}">
    <input type="hidden" name="score">
    <input type="hidden" name="is_questions" value="1">
    <input type="hidden" name="time_spend" value="{{ \Carbon\Carbon::now() }}">

<?php
$i = 1;
if ($questionData->count() > 0)
{
    foreach ( $questionData as $data )
    {
        if ($i == 1)
            $style = 'showCls';
        else
            $style = 'hideCls';
?>
        <div id="question_<?php echo $data['id'];?>" class="<?php echo $style;?>">
            <div class="qabox clearfix">
                <div class="question">
                    <p class="q1"><b>Question <?php echo $i; ?></b></p>
                    <div class="seprator"></div>
                    <p class="q2">
                        <span><?php echo $data['question'];?></span>
                    </p>
                </div>
            </div>
            <div class="answerbox clearfix">
                <ul>
                    <li><span class="bullet" questionId="<?php echo $data['id'].'_'.$data['answer']?>" data-val="1">A</span><span class="ans"><?php echo $data['option1']?></span></li>
                    <li><span class="bullet" questionId="<?php echo $data['id'].'_'.$data['answer']?>" data-val="2">B</span><span class="ans"><?php echo $data['option2']?></span></li>
                    <li><span class="bullet" questionId="<?php echo $data['id'].'_'.$data['answer']?>" data-val="3">C</span><span class="ans"><?php echo $data['option3']?></span></li>
                    <li><span class="bullet" questionId="<?php echo $data['id'].'_'.$data['answer']?>" data-val="4">D</span><span class="ans"><?php echo $data['option4']?></span></li>
                </ul>
                <input type="hidden" name="question_<?php echo $i;?>" id="question_<?php echo $data['id'];?>" value="<?php echo $data['id'];?>" >
                <input type="hidden" name="score_<?php echo $i;?>" id="score_<?php echo $data['id'];?>" value="">
                <input type="hidden" name="atempt_<?php echo $i;?>" id="atempt_<?php echo $data['id'];?>" value="">
<?php
            if (count($questionData)==$i)
                echo '<input type="button" id="next_question" value="Finish" style="margin-left: 15px; padding-left: 11px; padding-right: 10px; display:none;" class="shownextBtn btn btn-success" onClick="return urlScore();" />';
            else
                echo '<input type="button" id="next_question" value="Next" style="margin-left: 15px; padding-left: 20px; padding-right: 19px; display:none;" class="shownextBtn btn btn-success" />';
?>
            </div>
        </div>
<?php
    $i++;
    }
}
else
    echo 'No Questions For this test';
?>
        <div id="completeTest" style="display:none">Congratulations! You have completed the test.</div>
      </div>
      <input type="hidden" name="totalCount" value="<?php echo count($questionData);?> ">
      </form>
      <input type="hidden" name="tempVal" id="tempVal" value="">
</div>
<div id="overlay" style="display:none">
    <div class="overlaybox">
        <div id="test_result_div" class="borderless_centernew">
            <div style="width:252px; margin:0 auto; color:#fff; text-align:center;">
                <div class="game_timer_top_div">Your Effective Reading Speed is:</div>
                    <div class="game_timer_center_div">
                        <h2 id="ers" style="color:#fff;"></h2>
                        <h5 style="color:#fff;">ERS</h5>
                    </div>

                    <div class="game_timer_bottom_div">
                    </div>

                <div class="button-box">
                    <input type="button" value="Close" id="closeBtn" class="btn btn-success"/>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<div style="clear: both;"></div>

<script>

var answerClicked = false;

$(document).ready(function(){
    $(".shownextBtn").click(function(){
        if($("#tempVal").val()=="" || $("#tempVal").val()==null || $("#tempVal").val()==0)
            return false;
        else{
            $(this).parent().parent().addClass("hideCls");
            $(this).parent().parent().removeClass("showCls");
            $(this).parent().parent().next().addClass("showCls");
            $(this).parent().parent().next().removeClass("hideCls");
            $("#tempVal").val("");
            $("input#next_question").hide();

            // Reset flag
            answerClicked = false;
        }
    });

    $('.answerbox li .bullet').click(function(){

        // Don't do anything if user clicked already
        if (answerClicked === true) {
            return;
        }

        // Set flag for one click
        answerClicked = true;

        //  Highlight feedback gray bar
        $('li').addClass('white').css("background-color", "#eee");
        $(this).parent('li').removeClass('white').addClass('gray').css("background-color", "#999");

        var data_val = $(this).attr('data-val');
        var questionAnswerId=$(this).attr('questionId');
        var QueArray=questionAnswerId.split('_');
        var questionNo=QueArray[0];
        var answerId=QueArray[1];
        $("#atempt_"+questionNo).val(data_val);
        if(answerId==data_val)
            $("#score_"+questionNo).val("1");
        else
            $("#score_"+questionNo).val("0");
        $("#tempVal").val(data_val);
        $("input#next_question").show();
        $("#selectOption").val($('.answerbox li .bullet[data-val="'+answerId+'"]').html());

        // Show Xs for wrong choices
        $(this).parent('li').parent('ul').find('.bullet').addClass('wrong_bg').html("<i class='fa fa-times'></i>");

        $(this).parent('li').parent('ul').find('.bullet[data-val='+answerId+']').addClass('correct_bg').html("<i class='fa fa-check'></i>");
        $('#next_question').attr('class','shownextBtn btn btn-success');
        $(this).parent('li').parent('ul').parent('.answerbox').next('.btn_next_box').show();
    });

    $("#closeBtn").click(function(){
        $("#questionFrm").submit();
    });
});

function urlScore()
{
    if($("#tempVal").val()=="" || $("#tempVal").val()==null || $("#tempVal").val()==0)
        return false;
    $("#completeTest").show();


    var score = 0;
    var ers = 0;
    var percent = 0;
    var count = <?php echo $questionData->count(); ?>;
    var wpm = <?php echo $wpm; ?>;

    for(i = 1; i <= count; i++) {
        if($("input[name='score_"+i+"']").val() == 1) {
	    score++;
	}
    }
    
    if(score >= 1 && count >= 1) {
        //percent = (score * 100 / count);
        //ers = Math.round(wpm * percent / 100);
        percent = (score / count);
        ers = Math.round(wpm * percent);
    }

    //actual calculation should be sent to backend
    $('input[name="score"]').val(score);

    $("#overlay").show();

    //to show only
    $("#ers").html(ers);
    $("#form_pct").val(percent);
    $("#form_ers").val(ers);
}
</script>
@stop
