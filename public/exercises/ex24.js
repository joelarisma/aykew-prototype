function ex24(arrSpeed) //    arrSpeed = Array(speedSlow, speedFast, speedMedium);
{
    var exDiv = '#ex24';
    var mainDiv = '#canvas_ex24';

    var top_position = 0;
    var left_position = 0;
    var directionRound = 10;
    var mRoundCount = 0;
    var opptDirectionRound = 10;
    var mOpptRoundCount = 0;

    var dir_LR = "R";
    var dir_UD = "D";
    var directionStyle = 1; // 1=>Left/Right, 2=>Top/Bottom
    var currentImgeNo = 0; //Image Index Number which is Getting Displayed

    var timer;
    var currentTime = 0;
    var speedCounter = 0;
    var maxMiliSec = 15000;
    var anim_round = 0;
    var xRows = 0;
    var yRows = 0;

    function init() {
        var getParentHeight = $('.games-inner-container').height();
        var getParentWidth = $('.games-inner-container').width();
        $(exDiv).css('height',getParentHeight+'px');
        $(exDiv).css('width',getParentWidth+'px');
        $('#image_0').css({left:0, top:0, position:"relative"});
        $('#image_1').css({left:0, top:0, position:"relative"});
        $('#image_2').css({left:0, top:0, position:"relative"});
    }

    function WhichAnimationShouldRun(){
        directionStyle = 2;
        if (anim_round == 1)
            directionStyle = 1;
        dir_RL = 'R';
        dir_UD = 'U';
    }

    function ReSizeObjects(){
        layer_width = $(exDiv).width();
        layer_height = $(exDiv).height();
        elem_width = $('#image_'+currentImgeNo).width();
        elem_height = $('#image_'+currentImgeNo).height();
        draw_width = layer_width - elem_width;
        draw_height = layer_height - elem_height;
        yRows = (draw_width / directionRound);
        xRows = (draw_height / directionRound);
    }

    function ChangeImage(){
        currentImgeNo++;
        if (currentImgeNo < 3) {
            $('#image_'+currentImgeNo).show();
            $('#image_'+currentImgeNo).nextAll('img').hide();
            $('#image_'+currentImgeNo).prevAll('img').hide();
            var elem_width = $('#image_'+currentImgeNo).width();
            var elem_height = $('#image_'+currentImgeNo).height();
            draw_width = layer_width-elem_width;
            draw_height = layer_height-elem_height;
        }
    }

    function moveImage(){
        speedCounter += parseInt(arrSpeed[anim_round]);
        currentTime = maxMiliSec - speedCounter;
        if (currentTime <=0)
        {
            ChangeImage();
            currentTime = 0;
            speedCounter = 0;
            LookForNextAnimationType();
        }

        if (mRoundCount == directionRound)
        {  // done forward, now backward
            if (mOpptRoundCount == opptDirectionRound) {
                mOpptRoundCount = 0
                mRoundCount = 0;
            }
            else
                mOpptRoundCount++;
        }
        else
            mRoundCount++;

        if (directionStyle == 1)
        {
            top_position = (mRoundCount - mOpptRoundCount) * (xRows-1);
            if (dir_RL == 'R') {
                playTick();
                $('#image_'+currentImgeNo).css({left:draw_width, top: top_position});
                dir_RL = 'L';
                dir_UD = 'D';
            }
            else if (dir_RL == 'L'){
                playTick();
                $('#image_'+currentImgeNo).css({left: 0, top: top_position});
                dir_RL = 'R';
                dir_UD = 'U';
            }
        } else {
            left_position = (mRoundCount - mOpptRoundCount) * yRows;
            if (dir_UD == 'D') {
                playTick();
                $('#image_'+currentImgeNo).css({left: left_position, top: 0});
                dir_UD = 'U';
                dir_RL = 'R';
            } else if (dir_UD == 'U'){
                playTick();
                $('#image_'+currentImgeNo).css({left: left_position, top:draw_height});
                dir_UD = 'D';
                dir_RL = 'L';
            }
        }
    }

    function LookForNextAnimationType(){
        anim_round++;
        if (anim_round > 2){
            clearInterval(timer);

            //Unbinds click event on pauseClick
            $("#cmdpause").unbind('click',pauseClick);

            //Unbinds keydown event
            $(document).off('keydown');

            //Unbinds click event for startAnimation
            $("#begin_exercise").unbind('click',startAnimation);

            $('#image_'+currentImgeNo).hide();
            $("#ex24").hide();
            exerciseEnded();
        } else {
            mOpptRoundCount = 0
            mRoundCount = 0;
            WhichAnimationShouldRun();
            clearInterval(timer);
            doAnimation();
        }
    }

    function doAnimation(){
        timer = setInterval(function(){moveImage()},arrSpeed[anim_round]);
    }

    function pauseClick()
    {
        var state = $("#cmdpause").val();
        togglePause();
        if (state == "Pause")
            clearInterval(timer);
        else
            doAnimation();
    }

    function startAnimation() {
        hideInstructions();
        $('#image_0').show();
        $('#image_0').nextAll('img').hide();
        $('#image_0').prevAll('img').hide();
        $("#ex24").show();

        //Unbinds keydown event for startAnimation-->$(document).off('keydown', startAnimation);

        // Allows for click event to call pauseClick
        $("#cmdpause").on('click',pauseClick);

        //Allows for all keydown events to call pauseClick
        $(document).keydown(function (e) {
            if (e.which == 32 || e.which == 13) {
                e.preventDefault();
                pauseClick();

            } else if (e.which == 8) {
                e.preventDefault();
            }
        });

        playTick();
        ReSizeObjects();
        WhichAnimationShouldRun();
        doAnimation();
    };
    $(canvas_ex24).load(api_dir+"exercise-images", {images:3, ids: true}, function() {
        init();
        showStart();
        $("#begin_exercise").bind('click',startAnimation);
        //Enables keydown event on startAnimation-->$(document).keydown('space', startAnimation);
    });
}
