function ex30(arrSpeed) //    arrSpeed = Array(speedSlow, speedFast, speedMedium);
{
    var exDiv = '#ex30';
    var dynamicDiv = '#mainDivDynamic30';

    var getParentHeight = $('.games-inner-container').height();
    var getParentWidth = $('.games-inner-container').width();
    $(exDiv).css('height',getParentHeight+'px');
    $(exDiv).css('width',getParentWidth+'px');

    var totalRows;
    var rImages;
    var imgPath;
    var speedIndex = 0;
    var totalLinesPerPage = 1;
    var timer;
    var currentRow = 0;
    var pauseFlag = false;
    var finishFlag = false;
    var speedCounter = 0;
    var maxMiliSec = 15000;

    function highLightRow(rowNum)
    {
        // hide previous row
        if (rowNum > 0) {
            i = rowNum-1;
            $('#ex30_'+i).css('display','none');
        }
        if (rowNum == totalRows)
            finishFlag = true;
    }

    function doAnimation()
    {
        if (pauseFlag)
            return;
        if (currentRow <= totalRows)
        {
            playTick();
            highLightRow(currentRow);
            checkSpeedChange();
        }
        else
            finishFlag = true;

        if (!finishFlag)
        {
            currentRow++;
            timer = setTimeout(function(){doAnimation()}, arrSpeed[speedIndex]);
        }
        else
        {
            clearTimeout(timer);

            //Unbinds click event for pauseClick
            $("#cmdpause").unbind('click',pauseClick);

            //Unbinds keydown event for pauseClick
            $(document).off('keydown');

            //Unbinds click event for startAnimation
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
        var currentTime = maxMiliSec - speedCounter;
        if (currentTime <=0 ) {
            speedIndex++;
            speedCounter = 0;
            if (speedIndex > 2) {
                finishFlag = true;
                return false;
            }
        }
    }

    function init()
    {
        centerMeFromMyParent(exDiv, dynamicDiv);
        var mWidth = getParentWidth + .3; //.2 has been added to fill right side area with white bg.
        $(dynamicDiv).css('width', mWidth+'px');
        $(dynamicDiv).disableSelection();

        totalRows = maxMiliSec/arrSpeed[0];
        totalRows += maxMiliSec/arrSpeed[1];
        totalRows += maxMiliSec/arrSpeed[2];
        totalRows = parseInt(totalRows);
        var max_count = parseInt(getParentWidth / 64);
        var str = '';
        for (var i=0; i<totalRows; i++)
        {
            var images = rImages.sort(function() {return 0.5 - Math.random()});
            str += '<span id="ex30_'+i+'">';
            for (var j=0; j<max_count; j++)
                str += '<img src="'+imgPath+images[j]+'" />';
            str += '</span>';
        }
        $(dynamicDiv).html(str);
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

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: api_dir+'exercise-image-list',
        data: 'images=32',
        success: function(data){
            rImages = data['images'];
            imgPath = data['path'];
            init();
            showStart();
            $("#begin_exercise").bind('click',startAnimation);
            //Enables keydown event on startAnimation-->$(document).keydown('space', startAnimation);
        }
    });

}
