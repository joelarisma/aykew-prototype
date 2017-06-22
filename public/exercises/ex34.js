function ex34()
{
    var exDiv = '#ex34';
    var mainDiv = '#canvas_ex34';

    var wordsRead;
    var seconds;
    var objTimer;
    var lines;

    function init() {
        var getParentHeight = $('.games-inner-container').height();
        var getParentWidth = $('.games-inner-container').width();
        $(exDiv).css('height',getParentHeight+'px');
        $(exDiv).css('width',getParentWidth+'px');
	$(mainDiv).css('height',getParentHeight+'px');
        $(mainDiv).css('width',getParentWidth+'px');
    };

    function countVisibleWords() {
	var wrap = $('#training-animation-div');
	var wrapbot = wrap.height() + wrap.offset().top;
	var cntnr = $("#testText");
	cntnr.html('<span>'+cntnr.html().replace(/ /g,'</span> <span>')+'</span>');
	var words = Array.prototype.slice.call(cntnr.find("span"),0);
	var lastw = null;    // last word
	for(var i = 0, c = words.length; i < c; i++) {
            var w = $(words[i]);
            var wbot = w.height() + w.offset().top;
            if (wbot > wrapbot) {
		lastw = $(words[i-1]).text();
		break;
            }
	}
	cntnr.html(cntnr.text());
	return i;  // i = total number of words
    };

    function startTimer() {
        seconds = 0;
        objTimer = setInterval(function() { seconds++; }, 100); // count .1 second intervals
    }

    function stopTimer() {
        clearInterval(objTimer);
        return seconds;
    }

    function showStep1() {
	hideInstructions();
	$("#cmdpause").hide();
	$(exDiv).show();
	$("#begin_exercise").unbind('click', showStep1);
	$("#doneBtn").bind('click', step1Done);

	var el = $('#training-animation-div');
	var h = el.height();
	lines = Math.floor((h + 10) / 45);
	el.css({height: lines * 45});
    };

    function showStep2() {
	$("#step1Done").modal('hide');
	$("#training-animation-div").show();
	$("#doneBtn").show();
	$("#beginStep2").unbind('click', showStep2);
	$("#doneBtn").bind('click', step2Done);
	startTimer();
    };

    function showStep3() {
	$("#step2Done").modal('hide');
	$("#loremText").hide();
	$("#testText").show();
	$("#training-animation-div").show();
  $('.listing_line').addClass('light_background');
	wordsRead = countVisibleWords();
	$("#doneBtn").show();
	$("#beginStep3").unbind('click', showStep3);
	$("#doneBtn").bind('click', step3Done);
	startTimer();
    };

    function step1Done() {
	$("#training-animation-div").hide();
	$("#doneBtn").hide();
	$("#step1Done").modal('show');
	$("#doneBtn").unbind('click', step1Done);
	$("#beginStep2").bind('click', showStep2);
    };

    function step2Done() {
	var time = stopTimer();
	$("#training-animation-div").hide();
	$("#doneBtn").hide();
	$("#lines").html(lines);
	$("#secs").html(time/10);
	$("#step2Done").modal('show');
	$("#doneBtn").unbind('click', step2Done);
	$("#beginStep3").bind('click', showStep3);
    };

    function step3Done() {
	var time = stopTimer();
	var wpm = Math.floor(wordsRead*600/time);
	$("#training-animation-div").hide();
	$("#doneBtn").hide();
	$("#wpm").html(wpm);
	$("#words").html(wordsRead);
	$("#wordsecs").html(time/10);
	$("#step3Done").modal('show');
	$("#doneBtn").unbind('click', step3Done);
	$("#continueBtn").bind('click', allDone);
    };

    function allDone() {
	$("#step3Done").on('hidden.bs.modal', function() {
	    exerciseEnded();
	});
	$("#step3Done").modal('hide');
    };

    init();
    showStart();
    $("#begin_exercise").bind('click', showStep1);
}
