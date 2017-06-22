$(document).ready(function() {
	 columnSelectedValue = 14;
	var fontsSelectedValue = 14;
	var blocksSelectedValue;
    var previousBlockValue;
	var calcTimerValue;
	var speedSelectedValue;
	 charPerLine = new Array();
	var charIncrement = 0;
	var totalLinesPerPage = 12;
	var totalLinesPerPagePage = 12;
	var totalLinesPerPageStatic = 12; //It will not change
	var totalLinesInPage = 0;
	var clearTimer;
	var remaining;
	var start;
	var timer;
    var getTotalPages = 0;
    var getCurrentPageNumber = 1;
    var countdown = 0;
    var pauseFlag = false;
    var finishFlag = false;
	var deviceWidth = $(window).width();
	 heighLightedRow = "";
	 
    var timeSpentDelta = 15; // Seconds
    var timeSpentTimer;

	//	columnSelectedValue = $('#column_width option:selected').val();
	//	fontsSelectedValue = $('#font_size option:selected').val();
	blocksSelectedValue = parseInt($('#block_size option:selected').val());
	previousBlockValue = blocksSelectedValue;
    speedSelectedValue = parseInt($('#wpm_speed option:selected').val());	

    /********     Start Vendor Code Added by SS          ********/

    $("#block_size").focus(function () 
    {
        // Store the prev value of dropdown block

    });
    /********     End Vendor Code Added by SS          ********/
        
	$('#column_width').change(function()
	{
		$('.reading_contentbox').css('margin','10px auto');
		$('#reading-data').css('text-align','left');
		$('#reading-data').css('width',$('#column_width').val());
		columnSelectedValue = $('#column_width option:selected').val();
		getWords(charPerLine[columnSelectedValue],columnSelectedValue);
        totalLinesPerPage = 12;
		window.clearInterval(timer);
        /***  Vendor code on 31-05-2013  ***/
        countdown = 0;
        timer = setTimeout(function(){DoAnimation()}, calcTimerValue);
        PauseTimer(); 
        /***  Vendor code on 31-05-2013  ***/
	});

	$('#font_size').change(function()
	{
		$('.reading_contentbox').css('font-size',$('#font_size').val());
		fontsSelectedValue = $('#font_size option:selected').val();
		init();
	});

	$('#block_size').change(function()
	{
		blocksSelectedValue = parseInt($('#block_size option:selected').val());
        calcTimerValue = ((columnSelectedValue/speedSelectedValue*blocksSelectedValue)*60*1000);
		var getForSingle = countdown*previousBlockValue;
		countdown = parseInt(getForSingle/blocksSelectedValue);
        previousBlockValue = blocksSelectedValue;
	});

	$('#wpm_speed').change(function()
	{
		speedSelectedValue = parseInt($('#wpm_speed option:selected').val());
        calcTimerValue = ((columnSelectedValue/speedSelectedValue*blocksSelectedValue)*60*1000);
	});
        
    calcTimerValue = ((columnSelectedValue/speedSelectedValue*blocksSelectedValue)*60*1000);
     
	function mediaQuery(screenWidth,mediaWords,mediaFonts,mediaSelectedRow,mediaSpeed,removeSelectOption)
	{
		if(deviceWidth<=screenWidth)
		{
			/*OVERWRITE THE DEFAULT VALUES HERE BASED ON DEVICE WIDTH*/
			columnSelectedValue = mediaWords;  
			fontsSelectedValue = mediaFonts;   
			blocksSelectedValue = mediaSelectedRow;   
			calcTimerValue = mediaSpeed; 
			if(removeSelectOption>0 && removeSelectOption<=10)
			{	
				removeSelectOption = removeSelectOption - 1;
				$('#column_width').find("option:gt("+removeSelectOption+")").remove();
				columnSelectedValue = 3;
			}
		}
	}
	function disableMe()
	{
		$('#block_size').attr('disabled', 'disabled');
		$('#wpm_speed').attr('disabled', 'disabled');
		$('#column_width').attr('disabled', 'disabled');
		$('#font_size').attr('disabled', 'disabled');
	}
	function enableMe()
	{
		$('#block_size').removeAttr('disabled');
		$('#wpm_speed').removeAttr('disabled');
		$('#column_width').removeAttr('disabled');
		$('#font_size').removeAttr('disabled');
	}
	
    function getMaxLengthOfWord(paraG)    
    {
        var charLengthArray = [];
        var theString = removeBreaks($(paraG).html());
        $.each(theString.split(" ").slice(0,-1), function(index, item) {
            charLengthArray.push(item.length);
        });    
        function sortA(a,b){ 
            return a - b;
        }
        var sort_asc = charLengthArray.slice(0).sort(sortA);
        if(sort_asc.length>0)
        {
            var maxLengthOfWord = sort_asc[sort_asc.length-1];
            return maxLengthOfWord+2;
        }
    }
  
	function removeBreaks(text){
		var noBreaksText = text;
		var nopara = true;
		noBreaksText = noBreaksText.replace(/(\r\n|\n|\r)/gm,"<1br />");
		re1 = /<1br \/><1br \/>/gi;
		re1a = /<1br \/><1br \/><1br \/>/gi;
		if(nopara == 1 || nopara ==  true){
			noBreaksText = noBreaksText.replace(re1," ");
		}else{
			noBreaksText = noBreaksText.replace(re1a,"<1br /><2br />");
			noBreaksText = noBreaksText.replace(re1,"<2br />");
		}
		re2 = /\<1br \/>/gi;
		noBreaksText = noBreaksText.replace(re2, " ");
		re3 = /\s+/g;
		noBreaksText = noBreaksText.replace(re3," ");
		re4 = /<2br \/>/gi;
		noBreaksText = noBreaksText.replace(re4,"\n\n");
		return noBreaksText;
	}
	
	window.getWords = function(charsPerRow, wordInLine)
	{
		var text = $('#mainDiv').html();
        text = text + ' &nbsp;';
		text = text.replace(/[<]p[^>]*[>]/gi,"[NEWLN]");
		text = text.replace('</p>','');
		text = text.replace(/[<]br[^>]*[>]/gi,"[NEWLN]");
		text = text.replace('<br/>','[NEWLN]');
		text = text.replace('<br />','[NEWLN]');
		text = removeBreaks(text);
		var rowEnd=0;
		len = text.length;
		width = $('#mainDiv').width();
		var breakingIndexes = [];
		gRowStart = 0;
        if(charsPerRow>len)
			{ charsPerRow = len;}
        var iGetMaxLn = getMaxLengthOfWord('#mainDiv');
        if(iGetMaxLn>charsPerRow)
			{ charsPerRow = iGetMaxLn;}
        gRowEnd = charsPerRow;
        var checkonce = 0;
		while(gRowEnd <= len){
			var limitWord = text.substring(gRowStart, gRowEnd);
			var countSpace = 0;
			var additionalWords = 0;
			for(var j=0;j<limitWord.length;j++)
			{
				if(limitWord[j]==' ')
					countSpace++;
				if(countSpace==wordInLine)
					additionalWords = limitWord.length - j;
			}
			gRowEnd = gRowEnd - additionalWords;
			rowEnd = text.substring(gRowStart, gRowEnd).lastIndexOf(' ');
			breakingIndexes.push(gRowStart + rowEnd);
			gRowStart = gRowStart + rowEnd + 1;
			gRowEnd = gRowStart + charsPerRow;
            if(checkonce==0)
            {
                if(gRowEnd>len)
                {
					gRowEnd = len;
                    checkonce = 1;
                }
            }
		}
		if(gRowEnd>len){
			gRowEnd = len;
			var limitWord = text.substring(gRowStart, gRowEnd);
			var countSpace = 0;
			var additionalWords = 0;
			for(var j=0;j<limitWord.length;j++)
			{
				if(limitWord[j]==' ')
					countSpace++;
				if(countSpace==wordInLine)
					additionalWords = limitWord.length - j;
			}
			gRowEnd = gRowEnd - additionalWords;
			rowEnd = text.substring(gRowStart, gRowEnd).lastIndexOf(' ');
			breakingIndexes.push(gRowStart + rowEnd);
			gRowStart = gRowStart + rowEnd + 1;
			gRowEnd = gRowStart + charsPerRow;
		}
		var text2 = $('#mainDiv').html();
        text2 = text2 + ' &nbsp;';
		text2 =  text2.replace('</p>','');
		text2 = text2.replace(/[<]p[^>]*[>]/gi,"[NEWLN]");
		text2 = text2.replace(/[<]br[^>]*[>]/gi,"[NEWLN]");
		text2 = text2.replace('<br/>','[NEWLN]');
		text2 = text2.replace('<br />','[NEWLN]');
		text2= removeBreaks(text2);
		var start = 0, newText = '';
		var newArr = new Array();
		var newArrText = '';
		var getArrayIndex = -1;
		var bi = 0;
		var additionalLines = 0;
		for(var i=0; i < breakingIndexes.length; i++){
			newArrText = text2.substring(start, breakingIndexes[i]);
			if (newArrText.indexOf("[NEWLN]") >= 0) {
				getArrayIndex = 0;
				newArr = newArrText.split('[NEWLN]');
			}
			if(getArrayIndex==0)
			{
				for(var nt=0;nt<newArr.length;nt++)
				{
					newText += '<span id="'+bi+'">'+ newArr[nt] + '<br />'+'</span>';
					bi++;
					additionalLines++;
				}
				additionalLines--;
			}
			else
			{
				newText += '<span id="'+bi+'">'+ newArrText + '<br />'+'</span>';
				bi++;
			}
			start = breakingIndexes[i];
			getArrayIndex = -1;
		}
		$('#mainDivDynamic').html(newText);
		totalLinesInPage = breakingIndexes.length + additionalLines; //actual array items are 2 more
		getTotalPages = parseInt(Math.round(totalLinesInPage/totalLinesPerPageStatic));
		getTotalPagesExeat = (totalLinesInPage/totalLinesPerPageStatic).toFixed(1);
		if(getTotalPagesExeat>getTotalPages)
			{getTotalPages = getTotalPages+1; }

		/*
		 * BOOKMARK PAGING FUNCTIONALITY -------
		 */

		// Set up the page based on the bookmark
		var wordCount = 0;
		var lineNumber = 0;

		// Count everything and figure out which line we're on
		$.each($('#mainDivDynamic span'), function(index, span) {
			var words = $(span).html().replace("<br>", "").length;

			if(bookmarkWord > wordCount && bookmarkWord < (wordCount + words)) {
				lineNumber = span.id;
			}
			wordCount += words;
		});

		// Set the countdown
		countdown = lineNumber;

		// Put on the right page
		var previousPages = Math.floor(lineNumber/totalLinesPerPageStatic);
		getCurrentPageNumber = previousPages + 1;

		// The paging func needs to be called for each page to be skipped to get to the bookmark
		for(i = 0; i < previousPages; i++) {
			var start = i*totalLinesPerPageStatic;
			paging(start, start+totalLinesPerPageStatic);
			totalLinesPerPage += totalLinesPerPageStatic;
		}

		highLightRow(countdown, blocksSelectedValue);
	}

	function complete()
	{
		$('#mainDivDynamic').html('<span style="color:#000;"><b>You have completed the exercise.</b></span>');
		$("#pause").hide();
		$("#pause2").hide();
		$("#resume").hide();
		$("#resume2").hide();
	}

	function highLightRow(rowNum,animatedLines)
	{

		var p,b=1;

		var exitLoop = animatedLines;
		//THIS FORLOOP WILL CHANGE ALL BOLD FONTS TO NORMAL FONTS
		for(var pp=0;pp<totalLinesPerPage;pp++)
			$('#'+pp).css('color','#C0C0C0');

		//THIS FORLOOP WILL CHANGE REQUIRED SELECTED LINES TO BOLD
		for(p=0;p<animatedLines;p++)
		{
			var totRow = rowNum*animatedLines + p;
			$('#'+totRow).css('color','#000');
			heighLightedRow = totRow;
		}
		//CALL FOR PAGING STARTS HERE
        var lastRowCol = Math.round(totalLinesPerPage/animatedLines);
		if(rowNum==lastRowCol)
		{
			paging((totalLinesPerPage-totalLinesPerPageStatic),totalLinesPerPage);
			totalLinesPerPage = totalLinesPerPage + totalLinesPerPageStatic;
            getCurrentPageNumber++;
		}

		if(rowNum > totalLinesPerPage){
			paging((totalLinesPerPage-totalLinesPerPageStatic),totalLinesPerPage);
			totalLinesPerPage = totalLinesPerPage + totalLinesPerPageStatic;
			getCurrentPageNumber++;
		}
		//FOR PAGINATION
		if (rowNum==(Math.round(totalLinesInPage/animatedLines)))
			complete();

	}

	function paging(hideStartRow,hideEndRow)
	{
		for(i=hideStartRow;i<hideEndRow;i++)
			$('#'+i).css('display','none');
		j=hideEndRow;
		totoal = parseInt(hideEndRow)+parseInt(totalLinesPerPagePage);
		for(j>hideEndRow;j<totoal;j++)
			$('#'+j).css('display','');
	}

	function onPageLoadStartTimer()
	{
		function DoAnimation(){
			if(pauseFlag)
				return;
			if(countdown<=totalLinesInPage)
				doTheTask();
			else
				finishFlag = true;
			if (finishFlag) {
				clearTimeout(timer);
				clearInterval(timeSpentTimer);
				updateTimeSpent();
			}
			else {
				timer = setTimeout(function(){DoAnimation()}, calcTimerValue);
			}
			countdown++;
		}

		function doTheTask()
		{
		//	console.log('Speed='+calcTimerValue);
		// 	console.log("countdown: "+countdown);
			// console.log(bookmarkWord);
			// if(bookmarkWord != ''){
			// 	var lineNo = parseInt(bookmarkWord/charPerLine[columnSelectedValue]);
			// 	countdown = lineNo;
			// 	bookmarkWord = '';

			// 	// alert(lineNo);
			// 	paging(lineNo, lineNo+10)
			// }

			highLightRow(countdown,blocksSelectedValue);
		}

		function PauseTimer(){
			if(pauseFlag)
				return;
			pauseFlag = true;
			clearTimeout(timer);
			clearInterval(timeSpentTimer);
		};
		function ResumeTimer(){
			if(pauseFlag == false)
				return;
			pauseFlag = false;
			timeSpentTimer = setInterval(updateTimeSpent, timeSpentDelta*1000);
			DoAnimation();
		};

		DoAnimation();

		$("#pause,#pause2").click(function () {
			PauseTimer();
			$("#pause").hide();
			$("#pause2").hide();
			$("#resume").show();
			$("#resume2").show();
			enableMe();
		});

		$("#resume,#resume2").click(function () {
			ResumeTimer();
			$("#resume").hide();
			$("#resume2").hide();
			$("#pause").show();
			$("#pause2").show();
			disableMe();
		});
	}

	function hideSelectColVal(removeSelectOption,screenWidth)
	{
		if(deviceWidth==screenWidth){
			var startVal = 10;
			var fninishVal = startVal - removeSelectOption;
			for(i=startVal;i>fninishVal;i--)
				$('#column_width').children(':nth-child('+i+')').prop('disabled', true);
		}
	}

	function init()
	{
		//ASSIGNING CHARACTER LENGTH LIMIT TO AN ARRAY
		for(var k=3;k<=16;k++)
		{
			if(k>8)
				k=k+1;
			charPerLine[k]=20 + charIncrement;
			charIncrement = charIncrement + 9;
		}
		//SETTING MAIN DIV
		$('#mainDivDynamic').css('font-size',fontsSelectedValue+'px');
		hideSelectColVal(9,320);
		hideSelectColVal(4,568); //iPhone5
		hideSelectColVal(4,480); //iPhone4
	}

    function bookmark(pagenumber)
    {
        var startVal =0;
        var endVal = pagenumber*totalLinesPerPageStatic;
        paging(startVal,endVal);
    }

	init();
	getWords(charPerLine[columnSelectedValue],columnSelectedValue);
    if($.cookie("CurrentPageNumber")){
		if(typeof checkIfBookmark != 'undefined'){
			if(checkIfBookmark=='beginning')
				$.cookie("CurrentPageNumber", '1', { path: '/' });
			if(typeof bookmark_id != 'undefined'){
				if(bookmark_id-1>=0 && checkIfBookmark=='bookmark')
				{
                    if((bookmark_id-1)>getTotalPages+1)
                        bookmark_id = getTotalPages;
					bookmark(bookmark_id-1);
				}
			}
		}
    }
	$('#btnstart,#btnstart2').click(function(){
		onPageLoadStartTimer();
		$('#btnstart').hide();
		$('#btnstart2').hide();
		$('#pause').show();
		$('#pause2').show();
	      
        // Time spent timer
		timeSpentTimer = setInterval(updateTimeSpent, timeSpentDelta*1000);
	});
	function updateTimeSpent() {
	    if (pauseFlag || finishFlag) {
	        return;
	    }
		
		$.ajax({
			method: 'post',
			url: '/update-book-activity',
			dataType: 'json',
			data: {
				id: $('#reading_align').attr('user-book-activity'),
				dateTime: timeSpentDelta
			},
			success:function(data){
				console.log('Book time successfully updated'+countdown);
			}
		});
	}

});
