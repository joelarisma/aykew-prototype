function ex22(arrSpeed) //    arrSpeed = Array(speedSlow, speedFast, speedMedium);
{
    var width = 70;
    var height = 70;
    var objectCounter = 0;
    var checkSpeedChange = 0;
    var arrShape = new Array('oval','oval','oval');
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

    function init() {
        var getParentHeight = $('.games-inner-container').height();
        var getParentWidth = $('.games-inner-container').width();
        $('#animationArea22').css('height',getParentHeight+'px');
        $('#animationArea22').css('width',getParentWidth+'px');
        currentShape = arrShape.sort(function() {return 0.5 - Math.random()});
        currentShape = currentShape[0];
        if($.inArray(currentShape, arrShape!=-1))
            arrShape.splice($.inArray(currentShape, arrShape),1);
        if (currentShape == 'oval') {
            width = width + 30;
            height = width/2;
            $('#center22').css('border-radius','50%');
        }
        $('#center22').css('width',width + 'px');
        $('#center22').css('height',height + 'px');
        $('#center22').css('background-color','#' + arrColorSet[0]);
        centerMeFromMyParent('#animationArea22','#center22',true);
        globalSpeed = arrSpeed[speedIndex];
    }

    function draw(){
        if (playFlagAnimation == 1)
            return;
        if(($('#animationArea22').width()-30) < width || ($('#animationArea22').height()-30) < height) {
            if(checkSpeedChange==1)
            {
                currentShape = arrShape.sort(function() {return 0.5 - Math.random()});
                currentShape = currentShape[0];
                if($.inArray(currentShape, arrShape!=-1))
                    arrShape.splice($.inArray(currentShape, arrShape),1);
                $('#animationArea22 .allDiv').remove();
                centerMeFromMyParent('#animationArea22','#center22',true);
            }
        checkSpeedChange=0;
        reDraw();
        }
        if(currentShape == 'oval') {
            width+=40;
            height+=20;
            thisWidth = width;
            thisHeight = height;
        } else {
            width+=20;
            height+=20;
            thisWidth = width;
            thisHeight = height;
        }
        leftVal =  $('#animationArea22').width()/2-(thisWidth/2)-5;
        topVal =  $('#animationArea22').height()/2-(thisHeight/2)-4;
        if(colorCounter >= arrColorSet.length)
            colorCounter = arrColorSet.length-1;
        objectCounter++;
        if (currentShape == 'oval') {
            $('#oval'+(objectCounter-1)).remove();
            var str = '<div id="oval'+objectCounter+'" class="allDiv" style="width:'+thisWidth+'px;height:'+thisHeight+'px;margin-top:'+topVal+'px;margin-left:'+leftVal+'px;border:4px solid #'+arrColorSet[colorCounter]+';border-radius:50%;"></div>';
        } else if (currentShape == 'circle') {
            var str = '<div class="allDiv" style="width:'+thisWidth+'px;height:'+thisHeight+'px;margin-top:'+topVal+'px;margin-left:'+leftVal+'px;border:4px solid #'+arrColorSet[colorCounter]+';border-radius:'+thisHeight+'px;"></div>';
        } else if (currentShape == 'square') {
            var str = '<div class="allDiv" style="width:'+thisWidth+'px;height:'+thisHeight+'px;margin-top:'+topVal+'px;margin-left:'+leftVal+'px;border:4px solid #'+arrColorSet[colorCounter]+';"></div>';
        }
        playTick();
        $(str).appendTo('#animationArea22');
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
        var timer = setTimeout(function(){ draw() }, arrSpeed[speedIndex]);
        colorCounter++;
    }

    function reDraw() {
        colorCounter = 0;
        $('#animationArea22 .allDiv').remove();
        var leftVal =  Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft());
        var topVal =  Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop());
        centerMeFromMyParent('#animationArea22','.allDiv',true);
        width = 70;
        height = 70;
        if (currentShape == 'oval') {
            width = width + 30;
            height = width/2;
        }
        leftVal =  $('#animationArea22').width()/2+30;
        topVal =  $('#animationArea22').height()/2+30;
        centerMeFromMyParent('#animationArea22','#center22', true);
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
        $("#ex22").show();
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
