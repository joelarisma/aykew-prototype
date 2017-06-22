function ex7() {

    var exDiv = '#ex7';
    var mainDiv = '#mainDiv7';
    var dynamicDiv = '#mainDivDynamic7';

    var linesPerBlock = 3;
    var totalBlocks;
    var lastLineThisPage;
    var linesPerPage;
    var currentBlock = 0;

    var speed_slow;
    var speed_medium;
    var speed_fast;
    var speed_superfast;
    var speedSelectedValue;
    var timer;

    var pauseFlag = false;
    var finishFlag = false;
    var speedIndex = 0;        // current speed (0-slow, 1-fast, 2-medium, 3-superfast, 4-fast)
    var speedCounter = 0;    // ms at current speed
    var maxMiliSec = 15000; // max ms at current speed (15 sec)

    function init()
    {
        $(exDiv).show();
        result = moveTextToDynamic(mainDiv, dynamicDiv);
        var totalLines = result.lines;
        var totalWords = result.words;
        totalBlocks = Math.round(totalLines/linesPerBlock);
        var fontSize = result.fontSize;

        $(mainDiv).hide();
        var mWidth = $('.games-inner-container').width();
        var mHeight = $('.games-inner-container').height();
        $('.games-inner-container').css('width',$(".games-inner-container").width());
        $(dynamicDiv).css('width',mWidth+'px');
        $(dynamicDiv).css('height',mHeight+'px');
        $(dynamicDiv).show();
        $(dynamicDiv).disableSelection();
        var avgWordsLine = Math.round(totalWords/totalLines);
        var avgWordsBlock = avgWordsLine * linesPerBlock;
        speed_slow = (avgWordsBlock/slowWpm)*60*1000;
        speed_medium = (avgWordsBlock/mediumWpm)*60*1000;
        speed_fast = (avgWordsBlock/fastWpm)*60*1000;
	speed_superfast = (avgWordsBlock/superfastWpm)*60*1000;
        speedSelectedValue = speed_slow;

        // determine how many lines will fit in the div
        // and make them fit evenly
        var divHeight = $(dynamicDiv).outerHeight();
        var lineHeight = Math.floor(parseInt(fontSize * 1.2));
        linesPerPage = parseInt(divHeight/lineHeight);
        var exactLineHeight = divHeight/linesPerPage;
        if (linesPerPage%linesPerBlock == 1)
        {
            linesPerPage--;
            exactLineHeight = divHeight/linesPerPage;
        }
        if (linesPerPage%linesPerBlock == 2)
        {
            linesPerPage++;
            exactLineHeight = divHeight/linesPerPage;
        }
        $(dynamicDiv).css('line-height',parseInt(exactLineHeight)+'px');
        lastLineThisPage = linesPerPage;
    }

    function paging(hideStartRow,hideEndRow)
    {
        for (var i=hideStartRow; i<hideEndRow; i++)
            $('.line_'+i).css('display','none');
    }

    function highLightBlock(blockNum)
    {

        for (var i=0; i<lastLineThisPage; i++)
            $('.line_'+i).css('color','#FFF');
        for (var p=0; p<linesPerBlock; p++)
        {
            var i = blockNum*linesPerBlock + p;
            $('.line_'+i).css('color','#000');
        }
        var lastRowCol = Math.round(lastLineThisPage/linesPerBlock);
        if (blockNum==lastRowCol)
        {
            paging((lastLineThisPage-linesPerPage),lastLineThisPage);
            lastLineThisPage = lastLineThisPage + linesPerPage;
        }
        if (blockNum==totalBlocks)
            finishFlag = true;
    }

    function doAnimation()
    {
        if (pauseFlag)
            return;
        if (currentBlock <= totalBlocks)
        {
            playTick();
            highLightBlock(currentBlock);
            checkSpeedChange();
        }
        else
            finishFlag = true;
        if (!finishFlag)
        {
            currentBlock++;
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
        //Enables keydown event on startAnimation -->$(document).keydown('space', startAnimation);
    })
}

