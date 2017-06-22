function ex33()
{
    var exDiv = '#ex33';
    var mainDiv = '#canvas_ex33';

    function init() {
        var getParentHeight = $('.games-inner-container').height();
        var getParentWidth = $('.games-inner-container').width();
        $(exDiv).css('height',getParentHeight+'px');
        $(exDiv).css('width',getParentWidth+'px');
	$(mainDiv).css('height',getParentHeight+'px');
        $(mainDiv).css('width',getParentWidth+'px');
	random2();
    };

    function random2() {
	var numvalue = new Array();
	for(i = 1; i <= 26; i++)
            numvalue[i-1] = String.fromCharCode(64+i);

	var x = numvalue.sort(function() { return Math.random() - 0.5 });
	$('#gc2 span p').each(function(index, velement) {
            $(this).html(x[index]);
            var randTop = Math.floor((Math.random()*75)+0);
            var randTopFinal = randTop + '%';
            var randLeft = Math.floor((Math.random()*25)*3);
            var randLeftFinal = randLeft + '%';
            $(this).css("top", randTopFinal);
            $(this).css("left", randLeftFinal);
	});
    };
   
    function showLetters() {
	hideInstructions();
	$("#cmdpause").hide();
	$("#ex33").show();
	$("#doneBtn2").on('click', lettersDone);
    };

    function lettersDone() {
	$("#begin_exercise").unbind('click', showLetters);
	$("#doneBtn2").hide();
	$(exDiv).hide();
	exerciseEnded();
    };
  
    init();
    showStart();
    $("#begin_exercise").bind('click', showLetters);
}
