function ex2() {

    var exDiv = '#ex2';
    var mainDiv = '#mainDiv2';
    var dynamicDiv = '#mainDivDynamic2';

    var speed_slow;
    var speed_medium;
    var speed_fast;
    var speed_superfast;
    var speedSelectedValue;

    var totalLines;

    var pauseFlag = false;
    var finishFlag = false;
    var speedIndex = 0;        // current speed (0-slow, 1-fast, 2-medium, 3-superfast, 4-fast)
    var currentLine = 0;
    var speedCounter = 0;    // ms at current speed
    var maxMiliSec = 15000; // max ms at current speed (15 sec)

    var getParentHeight = $('.games-inner-container').height();
    var getParentWidth = $('.games-inner-container').width();
    $(exDiv).css('height',getParentHeight+'px');
    $(exDiv).css('width',getParentWidth+'px');

    function init()
    {
        $(exDiv).show();
        result = moveTextToDynamic(mainDiv, dynamicDiv);
        totalLines = result.lines;
        var totalWords = result.words;
        var fontSize = result.fontSize;
        if (fontSize > 20)
            $(dynamicDiv).css('height','55px');
        else
            $(dynamicDiv).css('height','25px');

        centerMeFromMyParent(exDiv,dynamicDiv);
        $(mainDiv).hide();
        $(dynamicDiv).show();
        $(dynamicDiv).disableSelection();
        var avgWordsBlock = Math.round(totalWords/totalLines);
        speed_slow = (avgWordsBlock/slowWpm)*60*1000;
        speed_medium = (avgWordsBlock/mediumWpm)*60*1000;
        speed_fast = (avgWordsBlock/fastWpm)*60*1000;
	speed_superfast = (avgWordsBlock/superfastWpm)*60*1000;
        speedSelectedValue = speed_slow;
    }

    function highLightRow(lineNum)
    {
        if (lineNum > 0)
        {
            var n = lineNum-1;
            $('.line_'+n).css('display','none');
        }
        if (lineNum == totalLines)
            finishFlag = true;
    }

    function doAnimation()
    {
        if (pauseFlag)
            return;

        if (currentLine <= totalLines)
        {
            playTick();
            highLightRow(currentLine);
            checkSpeedChange();
        }
        else
            finishFlag = true;

        if (!finishFlag)
        {
            currentLine++;
            timer = setTimeout(function(){doAnimation()}, speedSelectedValue);
        }
        else
        {
            clearTimeout(timer);
            //Unbinds click event on pauseClick
            $("#cmdpause").off('click',pauseClick);

            //Unbinds keypress event
            $(document).off('keydown');

            //Unbinds click event on startAnimation
            $("#begin_exercise").off('click',startAnimation);
            $(exDiv).hide();
            exerciseEnded();
        }
    }

    function checkSpeedChange()
    {
        speedCounter += speedSelectedValue;
        var timeLeft = maxMiliSec - speedCounter;
	console.log(timeLeft);
        if (timeLeft <= 0)
        {
            if (speedIndex == 0) // currently slow
            {
                speedSelectedValue = speed_fast;
                showFastWpm();
            }
            else if (speedIndex == 1) // currently fast
            {
                speedSelectedValue = speed_medium;
                showMediumWpm();
            }
            else if (speedIndex == 2) // currently medium
	    {
		speedSelectedValue = speed_superfast;
	        showSuperfastWpm();
	    }
	    else if (speedIndex == 3) // currently super fast
	    {
		speedSelectedValue = speed_fast;
	        showFastWpm();
	    }
	    else
                finishFlag = true;

	    speedCounter = 0;
            speedIndex++;
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

    function startAnimation() {
        hideInstructions();
        showSlowWpm();
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
    };

    $(mainDiv).load(api_dir+"exercise-text", {words: wordsNeeded}, function() {
        init();
        showStart();
        $("#begin_exercise").on('click',startAnimation);
        //$(document).keydown('space', startAnimation);
    })
}

