function ex14() {

    var exDiv = '#ex14';
    var mainDiv = '#mainDiv14';
    var dynamicDiv = '#mainDivDynamic14';

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
        var mWidth = $('.games-inner-container').width();
        $(dynamicDiv).css('width',mWidth+'px');
        $(dynamicDiv).css('font-size','44px');
        $(dynamicDiv).css('height','55px');
        if (mWidth < 460) {
            $(dynamicDiv).css('font-size','20px');
            $(dynamicDiv).css('height','25px');
        }

        var words = [];
        $(mainDiv).each(function(){
            words = words.concat($(this).html().split(' '));
        });
        var totalWords = words.length;
        result = [];
        totalLines = totalWords/4;
        for (var line = 0; line < totalLines; line++ ) {
            ix = line * 4;
            phrase = words[ix] + ' ' + words[ix+1] + ' ' + words[ix+2] + ' ' + words[ix+3];
            result[line] = '<div class="line_' + line + '">' + phrase + '</div>';
        }
        $(dynamicDiv).html(result.join(' '));
        $(mainDiv).hide();
        centerMeFromMyParent(exDiv,dynamicDiv);
        $(exDiv).show();
        $(dynamicDiv).show();
        $(dynamicDiv).disableSelection();
        var avgWordsBlock = 4;
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
            $("#cmdpause").unbind('click',pauseClick);

            //Unbinds keydown event
            $(document).off('keydown');

            //Unbinds click event on startAnimation
            $("#begin_exercise").unbind('click',startAnimation);
            $(exDiv).hide();
            exerciseEnded();
        }
    }

    function checkSpeedChange()
    {
        speedCounter += speedSelectedValue;
        var timeLeft = maxMiliSec - speedCounter;
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
	    else if (speedIndex == 3) // currently superfast
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
        $("#begin_exercise").bind('click',startAnimation);
        //Enables keydown event on startAnimation-->$(document).keydown('space', startAnimation);
    })
}

