/* Common Exercise Functions */

// tick users howler.js - http://goldfirestudios.com/blog/104/howler.js-Modern-Web-Audio-Javascript-Library
var tick = new Howl({
	urls: ['/media/audio/click03.ogg', '/media/audio/click03.mp3'],
	autoplay: false
});

function playTick(){
	tick.pos(.04);
	tick.play();
}

function setGamesAreaHeight(){
	var body_h = $('body').height();
	var set_body_h = parseFloat(body_h*2/100);
	var game_footer_h = $('body').find('.games-footer').height();	
	var set_h = body_h-game_footer_h;
	var get_top_val = $('body').find('.games-inner-container').offset().top;
	set_h = set_h-get_top_val;
	set_h = set_h-10;
	$('.games-inner-container').height(set_h);
	$('.games-inner-container').find('.exercise-area').height(set_h);
}
setGamesAreaHeight();

$(window).resize(function(){
	setGamesAreaHeight();
});

$.fn.disableSelection = function() {
    return this
        .attr('unselectable', 'on')
        .css('user-select', 'none')
        .on('selectstart', false);
};

function moveTextToDynamic(mainDiv, dynamicDiv)
{
	$(mainDiv).show();
	$(dynamicDiv).hide();
	$('.games-inner-container').css('width',$(".games-inner-container").width());	
	var mWidth = $('.games-inner-container').width();
	var mHeight = $(mainDiv).height();
	$(mainDiv).css('width',mWidth+'px');
	$(dynamicDiv).css('width',mWidth+'px');
	$(dynamicDiv).css('height',mHeight+'px');
	var fontsSelectedValue = 47;	
	if (mWidth < 1200)
		fontsSelectedValue = 44;	
	if (mWidth < 460)
		fontsSelectedValue = 20;	
	$(dynamicDiv).css('font-size',fontsSelectedValue+'px');
	$(mainDiv).css('font-size',fontsSelectedValue+'px');
	var totalWords = $(mainDiv).text().split(/\b\S+\b/g).length-1;
	var split = new SplitText($(mainDiv),{type:"lines",linesClass:"line_++"});
	var totalLines = split.lines.length;
	$(dynamicDiv).html(split.lines)
	$(mainDiv).html('');
	return {
		fontSize : fontsSelectedValue,
		words : totalWords,
		lines : totalLines
	};
}

function centerMeFromMyParent(mParent, mChild, width)
{
	$(mChild).css({position: 'absolute'}); 
	$(mParent).css({position: 'relative'}); 
	var parent = $(mParent);
	var child = $(mChild);
	if (width)
		child.css({top: parent.height()/2 - child.height()/2 , left: parent.width()/2 - child.width()/2 }); 
	child.css({top: parent.height()/2 - child.height()/2});
}
