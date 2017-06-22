function ex27(arrSpeed) //    arrSpeed = Array(speedSlow, speedFast, speedMedium);
{
    var exDiv = '#ex27';
    var dynamicDiv = '#mainDivDynamic27';

    var pauseFlag = false;
    var finishFlag = false;
    var speedIndex = 0;        // current speed (0-slow, 1-fast, 2-medium)
    var countdown = 0;
    var speedCounter = 0;    // ms at current speed
    var maxMiliSec = 15000; // max ms at current speed (15 sec)
    var timer;

    var img_count = 32;
    var getImageHeight = 80;
    var maxPxIncrement = 15;
    var maxImages = 8;        // max number of images to show each view

    var start = 0;
    var prevStart = 0;
    var end = maxImages;
    var prevEnd = maxImages;
    var arr_left = [];
    var arr_top = [];

    var mWidth = $('.games-inner-container').width();
    var mHeight = $('.games-inner-container').height();

    function init()
    {
        $(dynamicDiv).disableSelection();
        maxWidth = parseInt(mWidth - getImageHeight);
        maxHeight = parseInt(mHeight - getImageHeight);
        // array of x coordinates
        for (var j=0; j<maxWidth; j=j+(maxPxIncrement))
            arr_left.push(j);
        // array of y coordinates
        for (var j=0; j<parseInt(mHeight)-80; j=j+maxPxIncrement)
            arr_top.push(j);
        if (arr_top.length < maxImages)
        {
            maxImages = arr_top.length;
            end = maxImages-1;
            prevEnd = maxImages-1;
        }
    }

    function highLightRow(rowNum)
    {
        for (var i=prevStart; i<prevEnd; i++)
            $('#image_'+i).hide();
        arr_left = arr_left.sort(function() {return 0.5 - Math.random()});
        arr_top = arr_top.sort(function() {return 0.5 - Math.random()});
        var ix = 0;
        for (var i=start; i<end; i++)
        {
            $('#image_'+i).css({left: arr_left[ix]+'px'});
            $('#image_'+i).css({top: arr_top[ix]+'px' });
            $('#image_'+i).css({position: "absolute", margin: '12px'});
            $('#image_'+i).show();
            ix++;
        }
        prevStart = start;
        prevEnd = end;
        start = end;
        end = end + maxImages;
        if ( end > img_count)
        {
            start = 0;
            end = maxImages;
        }
    }

    function doAnimation()
    {
        if (pauseFlag)
            return;
        if (!finishFlag)
        {
            playTick();
            highLightRow(countdown);
            checkSpeedChange();
            countdown++;
            timer = setTimeout(function(){doAnimation()}, arrSpeed[speedIndex]);
        }
        else
        {
            clearTimeout(timer);

            //Unbinds click event on pauseClick
            $("#cmdpause").unbind('click',pauseClick);

            //Unbinds keydown event
            $(document).off('keydown');

            //Unbinds click event on startAnimation
            $("#begin_exercise").unbind('click',startAnimation);

            exerciseEnded();
        }
    }

    function pauseClick()
    {
        var state = $("#cmdpause").val();
        togglePause();
        if (state == "Pause") {
            pauseFlag = true;
            clearTimeout(timer);
        } else {
            pauseFlag = false;
            doAnimation();
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
        }
        if (speedIndex > 2)
            finishFlag = true;
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

    $(dynamicDiv).load(api_dir+"exercise-images", {images:img_count, ids:true}, function() {
        init();
        showStart();
        $("#begin_exercise").bind('click',startAnimation);
        //Enables keydown event on startAnimation-->$(document).keydown('space', startAnimation);
    });
}
