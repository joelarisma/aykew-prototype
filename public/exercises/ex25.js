function ex25(arrSpeed) //    arrSpeed = Array(speedSlow, speedFast, speedMedium);
{
    var exDiv = '#ex25';
    var objImages = '#objImages_ex25';
    var speedIndex = 0;
    var speed = 0; //Speed of the category running

    var finishFlag = false;
    var pauseFlag = false;
    var items;
    var imgToDisplay = null; // Current Image
    var imgIx = 0; // Current Image index
    var currentPosIndex = 0; // Position index
    var myCanvas;
    var ctx;
    var frontback = 0; //1=Left, 2=Right.. you can say 1=SideOne, 2=SideTwo
    var timer;
    var speedCounter = 0;
    var maxMiliSec = 15000;
    var stepX, stepY, maxX;

    function init(){
        myCanvas = document.getElementById("myCanvas25");
        ctx = myCanvas.getContext("2d");
        ctx.strokeStyle = "black";
        ctx.lineWidth = 2;
        var img = $(objImages).find("img");
        items = jQuery.makeArray(img);
        AdjustHeightWidthOfMyCanvas();
        GetImage();
        speed = arrSpeed[speedIndex];
    };


    /*
     * Update height/width of canvas
     */
    function AdjustHeightWidthOfMyCanvas(){
        var c = $("#myCanvas25");
        var container = $(c).parent();
        var maxWidth = $(container).width();
        var maxHeight = $(container).height();
        c.attr('width', maxWidth);
        c.attr('height', maxHeight);
        stepX = Math.floor((maxWidth-50)/9);
        stepY = Math.floor((maxHeight-50)/9);
        maxX = stepX * 9;
    };

    function GetImage(){
        imgToDisplay = items[imgIx];
        imgIx++;
        if (imgIx >= items.length)
            imgIx = 0;
    };

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
        }
        else
        {
            checkDiagonalComplete();
            PaintCanvas(frontback);
            currentPosIndex++;
            timer = setTimeout(function(){doAnimation();}, speed);
        }
    }


    function PaintCanvas()
    {
        myCanvas.width = myCanvas.width;    // looks useless, but this blanks the canvas
        var path = Array(4,5,3,6,2,7,1,8,0,9);
        playTick();
        if (frontback == 0)
            ctx.drawImage(imgToDisplay,(path[currentPosIndex]*stepX),(path[currentPosIndex]*stepY),50,50);
        else
            ctx.drawImage(imgToDisplay,(maxX)-(stepX*path[currentPosIndex]),(path[currentPosIndex]*stepY),50,50);
    }

    function checkDiagonalComplete()
    {
        if (currentPosIndex == (10)) {  // number of moves
            // diagonal complete
            currentPosIndex = 0;
            frontback++;
            if (frontback >= 2)
                frontback = 0;
            GetImage();
        }
    }

    function checkSpeedChange()
    {
        speedCounter += parseInt(speed);
        var currentTime = maxMiliSec - speedCounter;
        if (currentTime <=0 ) {
            speedIndex++;
            speedCounter = 0;
            if (speedIndex > 2) {
                finishFlag = true;
                return false;
            }
        speed = arrSpeed[speedIndex];
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
    }

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

    $(objImages).load(api_dir+"exercise-images", {images:10}, function() {
        init();
        showStart();
        $("#begin_exercise").bind('click',startAnimation);
        //Enables keydown event on startAnimation-->$(document).keydown('space', startAnimation);
    });
}
