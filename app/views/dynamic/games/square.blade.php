@extends('layouts.home')

@section('content')
<link href="/games/square/perfect_square_games_style.css" rel="stylesheet" type="text/css">
<script src="/games/square/json2.js"></script>
<script src="/games/square/jstorage.js"></script>
<script src="/games/square/Timer.js"></script>
<script src="/games/square/TimeFormatter.js"></script>
<script src="/games/square/perfect_square.js"></script>

<div id="page-wrapper" style="min-height:0px;">
    <div class="row" style="padding-top:15px;">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
            <div class="panel panel-default">
                <div class="panel-body">
        		<div class="square_game_container">
                	<div class="game_opt_tab_box text-center">
                    	<a class="btnNew btn btn-success btn-lg" href="#"><i class="fa fa-gamepad fa-lg"></i> New Game</a>
                        <a class="btnReset btn btn-danger btn-lg" href="#"><i class="fa fa-undo fa-lg"></i> Clear Board</a>
                        <a class="btnHints btn btn-warning btn-lg" href="#"><i class="fa fa-lightbulb-o fa-lg"></i> Hints ON/OFF</a>
                        <a class="btnHelp btn btn-info btn-lg" href="#"><i class="fa fa-question-circle fa-lg"></i> How to Play</a>
                    </div>

                      <div style="clear:both;"></div>

                      <div class="square_game_level_box text-center">
                      	<label>Level :</label>
                        <select id="u_gameLevel" class="selectpicker">
                            <option>Level 1</option>
                            <option>Level 2</option>
                            <option>Level 3</option>
                            <option>Level 4</option>
                            <option>Level 5</option>
                            <option>Level 6</option>
                            <option>Level 7</option>
                        </select>
                      </div>
                      <div style="clear:both;"></div>
                      <div class="squareGameBox clearfix">
                      		<div class="squareGameBoxLeft">
                                <div class="hintsBox clearfix">
                                    <div class="hintsBox_title text-center">Hints Off</div>
                                    <div style="clear:both;"></div>
                                    <div class="hintsBoxInnerLeft" id="hintGrid_1"></div>
                                    <div class="hintsBoxInnerRight" id="hintGrid_2"></div>
                                    <div class="hintsBoxInnerLeft" id="hintGrid_3"></div>
                                    <div class="hintsBoxInnerRight" id="hintGrid_4"></div>
                                    <div class="hintsBoxInnerLeft" id="hintGrid_5"></div>
                                    <div class="hintsBoxInnerRight" id="hintGrid_6"></div>
                                    <div class="hintsBoxInnerLeft" id="hintGrid_7"></div>
                                    <div class="hintsBoxInnerRight" id="hintGrid_8"></div>
                                </div>
                            <div style="clear:both;"></div>


                            <div class="timer_and_button_box clearfix">
                            	<div class="timer_box">
                                	<img src="/games/square/images/small_timer.gif" alt="" />
                                    <span class="timer_text">00:05</span>
                                </div>
                                <div id="btnTimer" class="btn btn-default btn-lg pause"><i class='fa fa-pause'></i> Pause</div>
                            </div>
                            </div>

                            <div class="squareGameBoxRight">
                                <div class="gameBox">
                                <ul style="margin-bottom:0px;">



                                <div class="game_pausedSmallBox" style="display:none;">
                                    <h2>Game<br> Paused</h2>
                                    <h4><a href="javascript:void(0);" class="clicktoplay">Click <img src="/games/square/images/play_btn.png" alt=""> to Play</a></h4>
                                </div>

                                <div class="new_gameSmallBox" style="display:none;">
                                    <h2>Start a new Game?</h2>
                                    <input type="button" value="Yes" class="btn_yes" /><br>
                                    <input type="button" class="new_gameSmallBox_nobtn btn_no" value="No"/>
                                </div>

                                    <!--<li class="toBeFilled" id="gridCell9">?</li>
                                    <li class="defaultBlank" id="gridCell10"></li>
                                    <li class="defaultFilled" id="gridCell10"></li>
                                    <li class="nowFilled" id="gridCell11">7</li>-->

                                    <li class="defaultBlank gridCell" id="gridCell_1" cell_state="empty"></li>
                                    <li class="defaultBlank gridCell" id="gridCell_2" cell_state="empty"></li>
                                    <li class="defaultBlank gridCell" id="gridCell_3" cell_state="empty"></li>
                                    <li class="defaultBlank gridCell" id="gridCell_4" cell_state="empty"></li>

                                    <li class="defaultBlank gridCell" id="gridCell_5" cell_state="empty"></li>
                                    <li class="defaultBlank gridCell" id="gridCell_6" cell_state="empty"></li>
                                    <li class="defaultBlank gridCell" id="gridCell_7" cell_state="empty"></li>
                                    <li class="defaultBlank gridCell" id="gridCell_8" cell_state="empty"></li>

                                    <li class="defaultBlank gridCell" id="gridCell_9" cell_state="empty"></li>
                                    <li class="defaultBlank gridCell" id="gridCell_10" cell_state="empty"></li>
                                    <li class="defaultBlank gridCell" id="gridCell_11" cell_state="empty"></li>
                                    <li class="defaultBlank gridCell" id="gridCell_12" cell_state="empty"></li>

                                    <li class="defaultBlank gridCell" id="gridCell_13" cell_state="empty"></li>
                                    <li class="defaultBlank gridCell" id="gridCell_14" cell_state="empty"></li>
                                    <li class="defaultBlank gridCell" id="gridCell_15" cell_state="empty"></li>
                                    <li class="defaultBlank gridCell" id="gridCell_16" cell_state="empty"></li>
                                </ul>

                                <div class="squareGridBlockerDiv" style="display:none; border:1px hidden #fff; position:absolute; width:371px; height:371px; background:#CCC; opacity:.0;">
                                </div>


                            </div>

                                <div style="clear:both;"></div>

                                <div class="selectNumberBox clearfix">
                               		<ul>
                                        <div class="congratulationbox" data-game-id="{{$userGame}}" data-date="{{ \Carbon\Carbon::now() }}">
                                            <a href="javascript:void(0);" class="btn_congratulation" >Congratulation!<br>
                                            <span>Click to Exit</span><img src="/games/square/images/icon_continue.png"  alt="" style="margin:2px 0 0 5px;" /></a>
                                        </div>

                                    	<li id="numGrid_1" cell_state="active">1</li>
                                        <li id="numGrid_2" cell_state="active">2</li>
                                        <li id="numGrid_3" cell_state="active">3</li>
                                        <li id="numGrid_4" cell_state="active">4</li>
                                        <li id="numGrid_5" cell_state="active">5</li>
                                        <li id="numGrid_6" cell_state="active">6</li>
                                        <li id="numGrid_7" cell_state="active">7</li>
                                        <li id="numGrid_8" cell_state="active">8</li>
                                        <li id="numGrid_9" cell_state="active">9</li>
                                        <li id="numGrid_10" cell_state="active">10</li>
                                        <li id="numGrid_11" cell_state="active">11</li>
                                        <li id="numGrid_12" cell_state="active">12</li>
                                        <li id="numGrid_13" cell_state="active">13</li>
                                        <li id="numGrid_14" cell_state="active">14</li>
                                        <li id="numGrid_15" cell_state="active">15</li>
                                        <li id="numGrid_16" cell_state="active">16</li>
                                     </ul>

                                     <div class="numberGridBlockerDiv" style="display:none; border:1px solid #ff0; position:absolute; width:300px; height:70px; background:#CCC; opacity:.0;">
        						</div>

                                </div>
                                <div class="text-center">
                                <div class="btn btn-link btn-lg" id="btnRemove" style="margin:20px auto;"><span class="text-danger" style="text-decoration:none;"><i class="fa fa-eraser"></i> Erase Number</span></div>
                                </div>
                                <div class="removeBtnBlockerDiv" style="display:none; border:1px solid #ff0; position:absolute; width:300px; height:70px; background:#CCC; opacity:.0;">
        						</div>
                            </div>

                     </div>

                </div>
			<div class="topBlockerDiv" style="display:none; border:1px hidden #fff; width:100%; height:300px; position:absolute; z-index:100; left:0px; top:0px; background:#CCC; opacity:.0;">
			</div>
</div>
<div class="howtoplay_overlaybox"></div>
<div class="howtoplay_box" style="top:10px!important;">
	<div class="button_close btn_close_hwtp_box">
		<a href="javascript:void(0);" title="Close"><img src="/games/square/images/close_button.png" alt="Close" title="Close" /></a>
	</div>
	<div class="howtoplay_box_inner">
		<h1 class="title">How to Play</h1>
		<p>The goal of the eyeQ Perfect Square game is to place the numbers 1 to 16 on the board squares in such a way that any group of 4 squares (including wrap around) sum to 34. The following examples show the basic four groupings:</p>
		<div class="howtoplay_example">
                                    	<div class="col1 clearfix">
                                        	<div class="tinyTbl">
                                            	<ul>
                                                	<li>1</li><li>15</li><li></li><li></li>
                                                    <li>8</li><li>10</li><li></li><li></li>
                                                    <li></li><li></li><li></li><li></li>
                                                    <li></li><li></li><li></li><li></li>
                                                 </ul>
                                                <span>Cluster</span>
                                            </div>
                                            <div class="tinyTbl"><ul>
                                                	<li></li><li></li><li></li><li></li>
                                                    <li></li><li></li><li></li><li></li>
                                                    <li>11</li><li>5</li><li>16</li><li>2</li>
                                                    <li></li><li></li><li></li><li></li>
                                                 </ul>
                                                  <span>Row</span>
                                                 </div>

                                              <div class="tinyTbl"><ul>
                                                	<li></li><li></li><li>6</li><li></li>
                                                    <li></li><li></li><li>3</li><li></li>
                                                    <li></li><li></li><li>16</li><li></li>
                                                    <li></li><li></li><li>9</li><li></li>
                                                 </ul>
                                                  <span>Column</span>
                                                 </div>


                                              <div class="tinyTbl"><ul>
                                                	<li></li><li></li><li></li><li>12</li>
                                                    <li></li><li></li><li>3</li><li></li>
                                                    <li></li><li>5</li><li></li><li></li>
                                                    <li>14</li><li></li><li></li><li></li>
                                                 </ul>
                                                  <span>Diagonal</span>
                                                 </div>

                                              <div class="tinyTbl"><ul>
                                                	<li></li><li></li><li></li><li></li>
                                                    <li></li><li></li><li></li><li></li>
                                                    <li>11</li><li></li><li></li><li>2</li>
                                                    <li>14</li><li></li><li></li><li>7</li>
                                                 </ul>
                                                  <span>Wrap Cluster</span>
                                                 </div>

                                                 <div class="tinyTbl"><ul>
                                                	<li>1</li><li></li><li></li><li></li>
                                                    <li></li><li></li><li></li><li>13</li>
                                                    <li></li><li></li><li></li><li></li>
                                                    <li>4</li><li></li><li></li><li></li>
                                                 </ul>
                                                  <span>Wrap Diagonal</span>
                                                 </div>

                                        </div>
        </div>

                                    <p>To place numbers, click on a square on the game board then click on a number from the number grid to put into the square. To change a number click on the occupied square and then either click on the remove button or click on a different number.</p>
                                     		<div class="signTbl"><ul>
                                                	<li>1</li><li>15</li><li>6</li><li>12</li>
                                                    <li>8</li><li>10</li><li>3</li><li>13</li>
                                                    <li>11</li><li>5</li><li>16</li><li>2</li>
                                                    <li>14</li><li>4</li><li>9</li><li>7</li>
                                                 </ul>
                                                 </div>
        <p>The game is complete when all 16 squares are filled in with a different number such that every adjacent <em><strong>cluster</strong></em>, each row, each <em><strong>column</strong></em>, and every <em><strong>diagonal</strong></em> sum to 34 foming a "Perfect Square".</p>
		<p><strong>Levels:</strong> The number of squares filled in at the start of the game is determined by the game level. The game level can be increased or decreased by clicking on the respective arrow. The highter the level the fewer the squares.</p>
        <p><strong>Hints:</strong> Clicking on square while hints are on will display the sum of the various groups of 4 associated with the selected square. If hints are used during the solution of puzzle the result will not be eligible for the best time list.</p>
	</div>
</div>

</div></div>
<div class="col-lg-1"></div>
</div></div></div>
@stop

@section('script')
<script>
    $(document).ready(function() {
        var timeSpentDelta = 15; // Seconds
        var timeSpentTimer = setInterval(updateTimeSpent, timeSpentDelta*1000);

        function updateTimeSpent() {
            var game_id = $(".congratulationbox").attr('data-game-id');
            var dateTime = $(".congratulationbox").attr('data-date');
            var url = '/update-game-activity';
            
            $.ajax({
                method: 'post',
                url: url,
                dataType: 'json',
                data: {
                    game_id:game_id,
                    dateTime:dateTime
                },
                success:function(data){
                    console.log('Data successfully added');
                }
            });
        };
    });
</script>
@stop
