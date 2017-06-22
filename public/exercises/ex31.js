function ex31()
{
    var exDiv = '#ex31';
    var mainDiv = '#canvas_ex31';

    var numMazes = 5;
    var showAnimTime = 4500;

    function init() {
        //var getParentHeight = $('.games-inner-container').height();
        //var getParentWidth = $('.games-inner-container').width();
        //$(exDiv).css('height',getParentHeight+'px');
        //$(exDiv).css('width',getParentWidth+'px');
    };

    function showMaze() {
	hideInstructions();
	$("#cmdpause").hide();
	$("#ex31").show();
	$("#doneBtn1").on('click', mazeDone);
    };

    function mazeDone() {
	$("#begin_exercise").unbind('click', showMaze);
	$("#doneBtn1").hide();
	$("#m1Black").hide();
	$("#m1Animate").show();
	setTimeout(function() {
            $(exDiv).hide();
            exerciseEnded();
        }, showAnimTime);
    };

    var maze = Math.floor((Math.random() * numMazes) + 1);
    var mazeImg = 'https://d1q45godky4jv2.cloudfront.net/images/maze/h'+maze+'.gif';
    var mazeAnimImg = 'https://d1q45godky4jv2.cloudfront.net/images/maze/animate_h'+maze+'.gif';
    $("#m1Black").attr('src', mazeImg).load(function() {
	$("#m1Animate").attr('src', mazeAnimImg);
	init();
	showStart();
	$("#begin_exercise").bind('click', showMaze);
    });
}
