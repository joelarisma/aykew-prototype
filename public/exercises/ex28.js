function ex28(arrSpeed) //    arrSpeed = Array(speedSlow, speedFast, speedMedium);
{
    var exDiv = '#ex28';
    var mainDiv = '#mainDiv28';
    var dynamicDiv = '#mainDivDynamic28';

    var pauseFlag = false;
    var finishFlag = false;
    var speedIndex = 0;        // current speed (0-slow, 1-fast, 2-medium)
    var countdown = 0;
    var speedCounter = 0;    // ms at current speed
    var maxMiliSec = 15000; // max ms at current speed (15 sec)
    var timer;

    var setMaxWords = 8;
    var start = 0;
    var end = setMaxWords;
    var prevStart = 0;
    var prevEnd = setMaxWords;
    var arr_left = [];
    var arr_top = [];

    var mWidth = $('.games-inner-container').width();
    var mHeight = $('.games-inner-container').height()-10;
    var offset = $('.games-inner-container').offset();
    var mainDivWidth = mWidth;

    function init()
    {
        $(mainDiv).hide();
        $(dynamicDiv).disableSelection();

        //Manoj->Please organise this function
        $(mainDiv).each(function(){
        	var text = $(this).html();
        	var strippedText = text.replace(/&nbsp;|(<([^>]+)>)/ig,"");
        	var strippedText = strippedText.split(" ");
        	
            //var text = $(this).html().split(' '),
            var len = text.length,
            result = [];
            if(len>3200){
                len = 3201;
            }
            for( var i = 0; i < len-1; i++ ) {
                result[i] = '<span id="word_'+i+'">' + strippedText[i] + '</span>';
            }
            $(dynamicDiv).html(result.join(' '));
        });
        $(mainDiv).html('');
        $(dynamicDiv+' span').hide();
        fontsSelectedValue = 44;
        if(mainDivWidth<930) {  fontsSelectedValue=30; }
        if(mainDivWidth<730) {  fontsSelectedValue=25; }
        if(mainDivWidth<460) {  fontsSelectedValue=20; }
        $(dynamicDiv).css('font-size',fontsSelectedValue+'px');
        var fontSize = $(dynamicDiv).css('font-size');
        var lineHeight = Math.floor(parseInt(fontSize.replace('px','')) * 1.2);
        var getLineHeight = parseInt(mHeight/parseInt(mHeight/lineHeight));
        var maxPxIncrement = 15;
        var maxWidth = 0;
        if (mainDivWidth>1200)
            maxWidth = parseInt(mWidth - 150);
        else
            maxWidth = parseInt(mWidth - 100);
        for (var j=0; j<maxWidth; j=j+(maxPxIncrement-8)) //1366*768 my laptop
            arr_left.push(j);
        for (var j=0; j<parseInt(mHeight); j=j+getLineHeight) //47px
            arr_top.push(j);
        if (arr_top.length < setMaxWords)
        {
            setMaxWords = arr_top.length;
            end = setMaxWords-1;
            prevEnd = setMaxWords-1;
        }
    }

    function highLightRow(rowNum)
    {
        for(var i=prevStart; i<prevEnd; i++)
            $('#word_'+i).hide();
        arr_left = arr_left.sort(function() {return 0.5 - Math.random()});
        arr_top = arr_top.sort(function() {return 0.5 - Math.random()});
        var chk=0
        for (var i=start; i<end; i++)
        {
            $('#word_'+i).css({left: arr_left[chk]+'px'});
            $('#word_'+i).css({top: arr_top[chk]+'px' });
            $('#word_'+i).css({position: "absolute", margin: '12px'});
            $('#word_'+i).show();
            chk++;
        }
        prevStart = start;
        prevEnd = end;
        start = end+1;
        end = (end+setMaxWords);
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
    };

    $(mainDiv).load(api_dir+"exercise-text", {words: wordsNeeded}, function() {
        init();
        showStart();
        $("#begin_exercise").bind('click',startAnimation);
        //Enables keydown event on startAnimation-->$(document).keydown('space', startAnimation);
    })
}
