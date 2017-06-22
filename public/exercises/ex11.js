function ex11() {

    var exDiv = '#ex11';
    var mainDiv = '#mainDiv11';
    var dynamicDiv = '#mainDivDynamic11';

    var linesPerBlock = 3;
    var totalBlocks;
    var lastLineThisPage;
    var linesPerPage;
    var currentPage = 0;
    var currentBlock = 0;

    var speed_slow;
    var speed_medium;
    var speed_fast;
    var speed_superfast;
    var speedSelectedValue;
    var timer;

    var pauseFlag = false;
    var finishFlag = false;
    var speedsWpm = [10000, 5000, 2500, 1250, 600];
    var speeds = [0,0,0,0,0];
    var speedIndex = 0;        // current speed (0-5 fastest to slowest)
    var speedCounter = 0;    // ms at current speed
    var maxMiliSec = 10000; // max ms at current speed (10 sec)

    function init()
    {
        $(exDiv).show();
        result = moveTextToDynamic(mainDiv, dynamicDiv);
        var totalLines = result.lines;
        totalBlocks = Math.round(totalLines/linesPerBlock);
        var totalWords = result.words;
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
        for (var i=0; i<5; i++)
            speeds[i] = (avgWordsBlock/speedsWpm[i])*60*1000;
        speedSelectedValue = speeds[0];


        // determine how many lines will fit in the div
        // and make them fit evenly
        var divHeight = $(dynamicDiv).outerHeight();
        var lineHeight = Math.floor(parseInt(fontSize * 1.2));
        linesPerPage = parseInt(divHeight/lineHeight);
        var exactLineHeight = divHeight/linesPerPage;
        if (linesPerPage%linesPerBlock ==1)
        {
            linesPerPage--;
            exactLineHeight = divHeight/linesPerPage;
        }
        if (linesPerPage%linesPerBlock==2)
        {
            linesPerPage++;
            exactLineHeight = divHeight/linesPerPage;
        }
        $(dynamicDiv).css('line-height',parseInt(exactLineHeight)+'px');
        lastLineThisPage = linesPerPage;
    }

    function paging(hideStartRow,hideEndRow)
    {
        currentPage++;
        for (var i=hideStartRow; i<hideEndRow; i++)
            $('.line_'+i).css('display','none');
    }

    // 'clear' all lines
    function clearCurPage()
    {
        for (var p=0; p < linesPerPage; p++)
        {
            var i = currentPage*linesPerPage + p;
            $('.line_'+i).css('color','#C0C0C0');
        }
    }

    function highLightBlock(blockNum)
    {
        // next page?
        var lastBlock = Math.round(lastLineThisPage/linesPerBlock);
        if (blockNum==lastBlock)
        {
            paging((lastLineThisPage-linesPerPage),lastLineThisPage);
            lastLineThisPage = lastLineThisPage + linesPerPage;
        }
        clearCurPage();
        for (var p=0; p < linesPerBlock; p++)
        {
            var i = blockNum*linesPerBlock + p;
            $('.line_'+i).css('color','#000');
        }
        // final block?
        if (blockNum == totalBlocks)
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
            speedIndex++;
            if (speedIndex >4)
                finishFlag = true;
            else
            {
                speedSelectedValue = speeds[speedIndex];
                showWpm(speedsWpm[speedIndex]);
            }
            speedCounter = 0;
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
        showWpm(speedsWpm[0]);
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

    // ex 11 needs ~3400 words at current fixed wpm speeds
    $(mainDiv).load(api_dir+"exercise-text", {words: 3450}, function() {
        init();
        showStart();
        $("#begin_exercise").bind('click',startAnimation);
        //Enables keydown event on startAnimation-->$(document).keydown('space', startAnimation);
    })
}
