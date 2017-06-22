function ex21(arrSpeed) //    arrSpeed = Array(speedSlow, speedFast, speedMedium);
{
    console.log(arrSpeed);
    var width = 70;
    var height = 70;
    var arrShape = new Array('circle','square','circle');
    var arrColorSet = new Array('18ff18', '24ff24', '30ff30', '3cff3c', '48ff48', '54ff54', '60ff60', '6cff6c', '78ff78', '84ff84', '90ff90', '9cff9c', 'a8ffa8', 'c0ffc0');
    var arrColorSetAlt = new Array('1818ff', '2424ff', '3030ff', '3c3cff', '4848ff', '5454ff', '6060ff', '6c6cff', '7878ff', '8484ff', '9090ff', '9c9cff', 'a8a8ff', 'ccccff');
    if (Math.random() > 0.5)
        arrColorSet = arrColorSetAlt;
    var speedIndex = 0;
    var currentTime = 0;
    var speedCounter = 0;
    var maxMiliSec = 15000;
    var playFlagAnimation = 0;
    var colorCounter = 0;
    var currentShape;
    var timer;
    var checkSpeedChange=0;

    function init() {
        var getParentHeight = $('.games-inner-container').height();
        var getParentWidth = $('.games-inner-container').width();
        $('#animationArea21').css('height',getParentHeight+'px');
        $('#animationArea21').css('width',getParentWidth+'px');

        currentShape = arrShape.sort(function() {return 0.5 - Math.random()});
        currentShape = currentShape[0];
        if ($.inArray(currentShape, arrShape!=-1))
            arrShape.splice($.inArray(currentShape, arrShape),1);
        if (currentShape == 'circle') {
            $('#center21').css('border-radius','50%');
        }
        $ ('#center21').css('width',width + 'px');
        $ ('#center21').css('height',height + 'px');
        $ ('#center21').css('background-color','#' + arrColorSet[0]);
        centerMeFromMyParent('#animationArea21','#center21', true);
        globalSpeed = arrSpeed[speedIndex];
}

    function draw()
    {
        if (playFlagAnimation == 1)
            return;
        if(($('#animationArea21').width()-30) < width || ($('#animationArea21').height()-30) < height) {
            if(checkSpeedChange==1)
            {
                currentShape = arrShape.sort(function() {return 0.5 - Math.random()});
                currentShape = currentShape[0];
                if($.inArray(currentShape, arrShape!=-1))
                    arrShape.splice($.inArray(currentShape, arrShape),1);
                if (currentShape == 'circle')
                    $('#center21').css('border-radius','50%');
                else
                    $('#center21').css('border-radius','0%');
                $('#animationArea21 .allDiv').remove();
                centerMeFromMyParent('#animationArea21','#center21', true);
            }
            checkSpeedChange=0;
            reDraw();
        }
        width+=20;
        height+=20;
        thisWidth = width;
        thisHeight = height;
        leftVal =  $('#animationArea21').width()/2-(thisWidth/2)-5;
        topVal =  $('#animationArea21').height()/2-(thisHeight/2)-4;
        if(colorCounter >= arrColorSet.length)
            colorCounter = arrColorSet.length-1;
        if (currentShape == 'oval') {
            var str = '<div class="allDiv" style="width:'+thisWidth+'px;height:'+thisHeight+'px;margin-top:'+topVal+'px;margin-left:'+leftVal+'px;border:4px solid #'+arrColorSet[colorCounter]+';border-radius:50%;"></div>';
        } else if (currentShape == 'circle') {
            var str = '<div class="allDiv" style="width:'+thisWidth+'px;height:'+thisHeight+'px;margin-top:'+topVal+'px;margin-left:'+leftVal+'px;border:4px solid #'+arrColorSet[colorCounter]+';border-radius:'+thisHeight+'px;"></div>';
        } else if (currentShape == 'square') {
            var str = '<div class="allDiv" style="width:'+thisWidth+'px;height:'+thisHeight+'px;margin-top:'+topVal+'px;margin-left:'+leftVal+'px;border:4px solid #'+arrColorSet[colorCounter]+';"></div>';
        }
        playTick();
        $(str).appendTo('#animationArea21');
        speedCounter += arrSpeed[speedIndex];
        currentTime = maxMiliSec - speedCounter;
        if(currentTime<=0) {
            speedIndex++;
            speedCounter=0;
            currentTime=0;
            checkSpeedChange=1;
        }
        if(speedIndex>2) {
            playFlagAnimation = 1;

            //Unbinds click event on pauseClick
            $("#cmdpause").unbind('click',pauseClick);

            //Unbinds keydown event
            $(document).off('keydown');

            //Unbinds click event on startAnimation
            $("#begin_exercise").unbind('click',startAnimation);

            $("#ex21").hide();
            exerciseEnded();
        }
        globalSpeed = arrSpeed[speedIndex];
        timer = setTimeout(function(){ draw() }, arrSpeed[speedIndex]);
        colorCounter++;
    }

    function reDraw() {
        colorCounter = 0;
        $('#animationArea21 .allDiv').remove();
        var leftVal =  Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft());
        var topVal =  Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop());
        centerMeFromMyParent('#animationArea21','.allDiv');
        width = 70;
        height = 70;
        leftVal =  $('#animationArea21').width()/2+30;
        topVal =  $('#animationArea21').height()/2+30;
        centerMeFromMyParent('#animationArea21','#center21', true);
    }

    function pauseClick()
    {
        var state = $("#cmdpause").val();
        togglePause();
        if (state == "Pause") {
            playFlagAnimation = 1;
        } else {
            playFlagAnimation = 0;
            draw();
        }
    }

    function startAnimation() {
        hideInstructions();
        $("#ex21").show();
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
        draw();
    };

    init();
    showStart();
    $("#begin_exercise").bind('click',startAnimation);
    //Enables keydown event on startAnimation-->$(document).keydown('space', startAnimation);
    $(document).keypress(function (event) {
        if (event.which == 8) {
            event.preventDefault();
        }
    });
}

