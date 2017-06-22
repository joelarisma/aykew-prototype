function ex29(arrSpeed) //    arrSpeed = Array(speedSlow, speedFast, speedMedium);
{
    var exDiv = '#ex29';
    var dynamicDiv = '#mainDivDynamic29';

    var getParentHeight = $('.games-inner-container').height();
    var getParentWidth = $('.games-inner-container').width();
    $(exDiv).css('height',getParentHeight+'px');
    $(exDiv).css('width',getParentWidth+'px');

    var rImages;
    var imgPath;
    var speedIndex = 0;
    var timer;
    var pauseFlag = false;
    var finishFlag = false;
    var speedCounter = 0;
    var maxMiliSec = 15000;

    var changeRow = 0;
    var max_count;
    var goTilMax = 1;
    var totalImages;
    var countdown = 0;

    function highLightRow()
    {
        $('#ex29_'+changeRow+' img:nth-child('+goTilMax+')').show();
        if (goTilMax == max_count) {
            setTimeout(function(){
                goTilMax = 1;
                $('#ex29_'+changeRow).css('display','none');
                playTick();
                changeRow++;
            }, arrSpeed[speedIndex]);
        }
        goTilMax++;
    }

    function doAnimation()
    {
        if (pauseFlag)
            return;
        if (countdown <= totalImages)
        {

            highLightRow();
            checkSpeedChange();
        }
        else
            finishFlag = true;
        if (!finishFlag)
        {
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

        totalImages = maxMiliSec/arrSpeed[0];
        totalImages += maxMiliSec/arrSpeed[1];
        totalImages += maxMiliSec/arrSpeed[2];
        totalImages = parseInt(totalImages);
        max_count = parseInt(getParentWidth / 64);
        var totalRows = Math.ceil(totalImages/max_count);
        totalRows++;
        var str = '';
        for (var i=0; i<totalRows; i++)
        {
            var images = rImages.sort(function() {return 0.5 - Math.random()});
            str += '<span id="ex29_'+i+'">';
            for (var j=0; j<max_count; j++)
                str += '<img src="'+imgPath+images[j]+'" />';
            str += '</span>';
        }
        $(dynamicDiv).html(str);
    }

    function startAnimation() {
        playTick();
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
        //    console.log(data);
            rImages = data['images'];
            imgPath = data['path'];
            init();
            showStart();
            $("#begin_exercise").bind('click',startAnimation);
            //Enables keydown event on startAnimation-->$(document).keydown('space', startAnimation);
        }
    });

}
