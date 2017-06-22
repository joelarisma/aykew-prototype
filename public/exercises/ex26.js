function ex26(arrSpeed) //    arrSpeed = Array(speedSlow, speedFast, speedMedium);
{
    var exDiv = '#ex26';
    var objImages = '#objImages_ex26';

    var finishFlag = false;
    var pauseFlag = false;
    var currentItemIndex = 0;
    var speedIndex = 0;    // is also image index
    var img_array;
    var ImgToDisplay;
    var myCanvas = null;
    var ctx = null;
    var imgHeight = 80;
    var imgWidth = 80;
    var currentTime = 0;
    var speedCounter = 0;
    var maxMiliSec = 15000;
    var timer;

    // Dynamic Positions
    var TL = {X:0,Y:0}
    var TR = {X:400,Y:0}
    var BL = {X:0,Y:500}
    var BR = {X:400,Y:500}

    var c = $("#myCanvas26");
    var container = $(c).parent();
    var maxWidth = $(container).width();
    var maxHeight = $(container).height();
    c.attr('width', maxWidth);
    c.attr('height', maxHeight);
    TR.X = maxWidth - imgWidth;
    BR.X = maxWidth - imgWidth;
    BL.Y = maxHeight - imgHeight;
    BR.Y = maxHeight - imgHeight;

    // Animation Sequence
    var AS = Array(TL,TR,BL,BR);
    var ASAlt = Array(TL,BL,TR,BR);

    function init() {

        if (Math.random() > 0.6)  // 40% chance of using Alt Sequence
            AS = ASAlt;
        myCanvas = $("#myCanvas26").get(0);
        ctx = myCanvas.getContext("2d");
        var img = $(objImages).find("img");
        img_array = jQuery.makeArray(img);
        ImgToDisplay = img_array[speedIndex];
    }

    function PlotImage(e) {
        myCanvas.width = myCanvas.width;  // looks useless, but this clears the canvas
        playTick();
        ctx.drawImage(ImgToDisplay,e.X,e.Y,imgWidth,imgHeight);
    }

    function doAnimation(){
        if (pauseFlag)
            return;
        checkSpeedChange();
        if (finishFlag)
        {
            clearTimeout(timer);

            //Unbinds click event on pauseClick
            $("#cmdpause").unbind('click',pauseClick);

            //Unbinds keydown event
            $(document).off('keydown');

            //Unbinds click event on startAnimation
            $("#begin_exercise").unbind('click',startAnimation);

            $(exDiv).hide();
            exerciseEnded();
        } else {
            PlotImage(AS[currentItemIndex]);
            currentItemIndex++;
            if (currentItemIndex > 3)
                currentItemIndex = 0;
            timer = setTimeout(function(){doAnimation()}, arrSpeed[speedIndex]);
        }
    }

    function checkSpeedChange()
    {
        speedCounter += arrSpeed[speedIndex];
        var timeLeft = maxMiliSec - speedCounter;
        if (timeLeft <= 0)
        {
            speedIndex++;
            speedCounter=0;
            if (speedIndex > 2) {
                finishFlag = true;
                return false;
            }
        ImgToDisplay = img_array[speedIndex]; //Get New Image To Display
        }
    }

    function PauseTimer(){
        pauseFlag = true;
    }

    function ResumeTimer(){
        if (pauseFlag == false)
            return;
        pauseFlag = false;
        doAnimation();
    };

    function pauseClick()
    {
        var state = $("#cmdpause").val();
        togglePause();
        if (state == "Pause")
            PauseTimer();
        else
            ResumeTimer();
    }

    function startAnimation() {
        hideInstructions();
        $(exDiv).show();
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
        doAnimation();
    }

    $(objImages).load(api_dir+"exercise-images", {images:3}, function() {
        init();
        showStart();
        $("#begin_exercise").bind('click',startAnimation);
        //Enables keydown event on startAnimation-->$(document).keydown('space', startAnimation);
    });
}

