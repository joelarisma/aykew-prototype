function ex32()
{
    var exDiv = '#ex32';
    var mainDiv = '#canvas_ex32';

    function init() {
        var getParentHeight = $('.games-inner-container').height();
        var getParentWidth = $('.games-inner-container').width();
        $(exDiv).css('height',getParentHeight+'px');
        $(exDiv).css('width',getParentWidth+'px');
	$(mainDiv).css('height',getParentHeight+'px');
        $(mainDiv).css('width',getParentWidth+'px');
	random1();
    };

    function random1() {
	var numvalue = new Array();
	for(i = 1; i <= 25; i++)
            numvalue[i-1] = i;
	
	var x = numvalue.sort(function() { return Math.random() - 0.5 });

	$('#gc1 span p').each(function(index, element) {
            $(this).html(x[index]);
            var randTop = Math.floor((Math.random()*75)+0);
            var randTopFinal = randTop + '%';
            var randLeft = Math.floor((Math.random()*25)*3);
            var randLeftFinal = randLeft + '%';
            $(this).css("top", randTopFinal);
            $(this).css("left", randLeftFinal);
	});
    };

    function showNums() {
	hideInstructions();
	$("#cmdpause").hide();
	$("#ex32").show();
	$("#doneBtn1").on('click', numsDone);
    };

    function numsDone() {
	$("#begin_exercise").unbind('click', showNums);
	$("#doneBtn1").hide();
	$(exDiv).hide();
	exerciseEnded();
    };
  
    init();
    showStart();
    $("#begin_exercise").bind('click', showNums);
}
