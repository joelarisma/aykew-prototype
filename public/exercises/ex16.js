function ex16() {

    var exDiv = '#ex16';
    var mainDiv = '#mainDiv16';
    var dynamicDiv = '#mainDivDynamic16';

    var linesPerBlock = 1;
    var totalLines;
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
        $(exDiv).show();
        $(mainDiv).hide();
        fontSize = 44;
        var mWidth = $('.games-inner-container').width();
        if (mWidth < 460)
            fontSize = 20;
        $(dynamicDiv).css('font-size',fontSize+'px');
        var mHeight = $('.games-inner-container').height();
        $(dynamicDiv).css('width',mWidth+'px');
        $(dynamicDiv).css('height',mHeight+'px');
        $(dynamicDiv).show();
        $(dynamicDiv).disableSelection();
        var avgWordsLine = 4;
        var avgWordsBlock = 4;
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
        $(dynamicDiv).css('line-height',parseInt(exactLineHeight)+'px');
        lastLineThisPage = linesPerPage;
    }

    function paging(hideStartRow,hideEndRow)
    {
        for(i=hideStartRow;i<hideEndRow;i++)
            $('.line_'+i).css('display','none');
    }

    function highLightBlock(blockNum)
    {
        // reset all lines
        for (var i=0; i<lastLineThisPage; i++)
            $('.line_'+i).css('color','#FFF');
        // highlight block
        for (var p=0; p < linesPerBlock; p++)
        {
            var i = blockNum*linesPerBlock + p;
            $('.line_'+i).css('color','#000');
        }
        // next page?
        var lastBlock = Math.round(lastLineThisPage/linesPerBlock);
        if (blockNum==lastBlock)
        {
            paging((lastLineThisPage-linesPerPage),lastLineThisPage);
            lastLineThisPage = lastLineThisPage + linesPerPage;
        }
        // final block?
        if (blockNum==(Math.round(totalLines/linesPerBlock)))
            finishFlag = true;
    }

    function doAnimation()
    {
        if (pauseFlag)
            return;
        if (currentBlock <= totalLines)
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
        //Enables keydown event on startAnimation-->$(document).keydown('space', startAnimation);
    })
}
