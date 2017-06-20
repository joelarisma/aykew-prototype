@extends('layouts.home')

@section('content')
	@include('flash::message')
	<div id="page-wrapper">
	    <div class="row" style="padding-top:15px;">
    	        <div class="col-md-8">
            	    <div class="panel panel-default">
                        <div class="panel-body text-center">
			    @if ($section)
			    <div class="row text-left">
			        <div class="col-sm-2"><img src="/uploads/section_image/{{ $section->image }}" style="width:100%;"/></div>
                		<div class="col-sm-8"><h3>{{ $section->name }}</h3><em>{{ $section->short_desc }}</em></div>
                		<div class="col-sm-2"><button type="button" class="btn btn-success btn-block" disabled="">LEVEL {{ $section->level_number() }}</button></div>
               		    </div>
			    @endif

			    @if ($day)
			    <!--show box if any daily activity-->
			    <div class="row text-left">
			        <div class="col-sm-8 col-sm-offset-2">
				    <ul class="list-group">
				    @for ($i = $section->begSessions->session; $i <= $section->endSessions->session; $i++)
				        @if ($i < $session->session)
				        <li class="list-group-item">
					    Exercise Session {{ $i }}
					    <span class="label label-success pull-right" style="border-radius: 25px;">
					        <i class="fa fa-check fa-2x"></i>
					    </span>
					@elseif ($i == $session->session && $todo)
					<li class="list-group-item">
					    <strong>Exercise Session {{ $i }}</strong>
					    @if ($todo)
					    <span class="label label-warning pull-right" style="border-radius: 25px;font-size: 14px;">
					        Today's <!-- ' --> Training
					    </span>
					    <br><br>
					    <!-- <em>Did you know? {{ $tip }}</em> -->
					    @else
					    <span class="label label-success pull-right" style="border-radius: 25px;">
					        <i class="fa fa-check fa-2x"></i>
					    </span>
					    @endif
					@else
				        <li class="list-group-item disabled">
					    Exercise Session {{ $i }}
					@endif
					</li>
				    @endfor
				    </ul>
				</div>
			    </div>
			    @endif
			    @if ($todo)
						@if($do_comptest)
							<!-- if comprehension test time, show it alone first without the daily activity-->
							<h4>Up Next: Reading Comprehension Test
								<a class="label label-info popoverlink" style="border-radius: 25px;" data-toggle="popup" data-content="
									You will occasionally take a comprehension test
									to measure your your reading speed and comprehension level.
									The combination of reading speed and comprehension level establishes your effective reading speed (ERS). <br><br>
									<em>Comprehension tests are usually done before doing any other activities.</em>">
									<i class="fa fa-question "></i>
								</a>
							</h4>
					</div>
					<div class='panel-footer'>
						<a class='btn btn-warning btn-lg btn-block' href='/comptest'>Comprehension Test</a>
					</div>
						@elseif($do_typetest)
							<!-- if typing test time, show it alone first without the daily activity-->
							<h4>Up Next: Typing Test
								<a class="label label-info popoverlink" style="border-radius: 25px;" data-toggle="popup" data-content="
									Typing tests are shown occassionally during your training.
									These tests will measure your gross typing speed and typing accuracy.
									Your gross typing WPM and accuracy are used to calculate your net typing speed. <br><br>
									<em>Typing tests are usually done before doing any other activities.</em>">
									<i class="fa fa-question "></i>
								</a>
							</h4>
					</div>
					<div class='panel-footer'>
						<a class='btn btn-warning btn-lg btn-block' href='/typingtest'>Typing Test</a>
					</div>
						@else
							@if($do_session)
								<h4>Up Next: Eye Exercise Session
									<a class="label label-info popoverlink" style="border-radius: 25px;" data-toggle="popup" data-content="
										Eye exercise training sessions consist of a pre-training speed test, then various different eye and reading
										exercises to improve the eye/brain connection, followed by a post-training speed test. During the training session,
										each exercise is preceded by a short instruction screen. This screen is there not only to give guidance on the
										upcoming exercise, but it's also an opportunity to rest your eyes between exercises. You may also pause an exercise
										at any time if you feel eye strain. <br><br>
										<em>If you are experiencing anything more than mild eye strain during or after
										exercises, you may have an underlying eye condition, and should see a vision specialist.</em>">
										<i class="fa fa-question "></i>
									</a>
								</h4>
					</div>
							@endif
							@if ($do_twopoint)
								<h4>Up Next: Two Point Eye Training
									<a class="label label-info popoverlink" style="border-radius: 25px;" data-toggle="popup" data-content="
										These exercises has proven to be one of the most effective tools to improve	your eye-brain connection.
										They will help you with your reading speed, typing speed, and sports activities.">
										<i class="fa fa-question "></i>
									</a>
								</h4>
					</div>
							@endif
					<div class='panel-footer'>
						<a class='btn btn-warning btn-lg btn-block' href='/{{ $package->url }}/session'>&gt;&gt; Begin Today's <!--'--> Training &lt;&lt;</a>
					</div>
						@endif

					@else <!-- otherwise today's training has been done-->
						@if ($section)

							<h1><i class='fa fa-check-circle fa-2x' style='color:#47A447;'></i> You've completed today's training</h1>
								<p>If you have some extra time, we recommend that you spend an additional 10 to 20 minutes reading a magazine, novel, newspaper or textbook.</p>
								<em>Eye training sessions are purposely limited to prevent eye strain - like any muscle, your eyes need time to recover between exercises</em>.
							</div>
						@else
							<h3><i class="fa fa-check-circle fa-6x" style="color:#47A447; font-size:150%;"></i> Congratulations - You've completed all scheduled training!</h3><br>
                                <p style="padding:0 10%;">To continue improving and maintaining your speed skills, we recommend spending 7-15 minutes each day completing one or more of the following activities:</p><br>
                                <div class="row">
                                    <div class="col-lg-4">
                                         <a class="btn btn-danger btn-block" href="{{route('miniexercises')}}"><h1><i class="fa fa-eye fa-6x" style="font-size:300%;"></i></h1>
                                       <p style="color:#fff">Eye Training Exercise</p></a>
                                    </div>
                                    <div class="col-lg-4">
                                    <a class="btn btn-warning btn-block" href="/reading"><h1><i class="fa fa-book fa-6x" style="font-size:300%;"></i></h1>
                                       <p style="color:#fff">Paced Reading</p></a>
                                    </div>
                                    <div class="col-lg-4">
                                    <a class="btn btn-primary btn-block" href="/activities"><h1><i class="fa fa-gamepad fa-6x" style="font-size:300%;"></i></h1>
                                       <p style="color:#fff">Skills Games</p></a>
                                    </div>
                                    <br><br>
                                </div>
							</div></div>
						@endif
					@endif
			</div>

@if ($nextsection)
			<div class="row text-center">
               	<div class="col-xs-12">
					<h4 style="color:#eee;">Your Next Training Level:</h4>
				</div>
           	</div>
 			<div class="panel panel-default">
                <div class="panel-body">
                	<div class="row">
			        <div class="col-sm-2"><img src="/images/levels/{{ $nextsection->id or '_' }}.png" style="width:100%; opacity:0.5;"/></div>
                		<div class="col-sm-8"><h3>{{ $nextsection->name }}</h3><em>{{ $nextsection->short_desc }}</em></div>
                		<div class="col-sm-2"><button type="button" class="btn btn-success btn-block" disabled="">LEVEL {{ $nextsection->level_no }}</button></div>
                	</div>
                </div>
            </div>
@endif

@if ($section)
	<?php
	$beg_day = $section->begSessions->session;
	$end_day = $section->endSessions->session;
	$tot_step = ($end_day-$beg_day) + 1;
	$cur_step = $session->session - $beg_day + 1;
	$pct = ($cur_step/$tot_step) * 100;
        $level_num = $section->level_number();
	?>
		</div>
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading text-center">
					<strong>Current Level Progress</strong>
				</div>
				<div class="panel-body text-center">
					<img src="/images/levels/{{ $section->level_image or '_.png' }}" style="width:65%; display:none">
					<div style="width:100%;position:relative;">
						<span class="btn btn-success" style="position:absolute;left:-5px;">{{ $level_num }}</span>
						<div class="progress pull-left" style="width:100%;margin-top:6px;">
							<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $pct }}%;">
								<span class="sr-only">{{ $pct }}% Complete</span>
							</div>
						</div>
						<span class="btn btn-default" style="position:absolute;right:-5px;">{{ $level_num+1 }}</span>
					</div>
					<br><br><br>
					<button class="btn btn-lg btn-info btn-outline btn-block" data-toggle="modal" data-target="#videoModal">
						{{ $section->name }} &nbsp; <i class="fa fa-play"></i>
					</button>
					<button class="btn btn-lg btn-info btn-outline btn-block" data-toggle="modal" data-target="#reminderModal">
						Set Reminder</i>
					</button>
				</div>
			</div>
		</div>


<!-- New Level Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title text-center" id="videoModalLabel">Welcome to {{ $section->name }}</h4>
				</div>
				<div class="modal-body text-center">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist" id="myTab">
					@if ($section->youtube)
						<li class="active"><a href="#home" id="homelink" role="tab" data-toggle="tab">Watch the Video</a></li>
					@endif
						<li><a href="#about" id="aboutlink" role="tab" data-toggle="tab">Read Intro to {{ $section->name }}</a></li>
					</ul>
					<!-- Tab panes -->
					<div class="tab-content">
					@if ($section->youtube)
						<div class="tab-pane active" id="home">
							<div class="embed-responsive embed-responsive-16by9">
								<div class="wel_video_sec_video" id="ytplayer"></div>
							</div>
						</div>
					@endif
						<div class="tab-pane text-left" id="about"><br>
							{{ $section->description }}<br>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
	<div class="modal fade" id="reminderModal" >
		<div class="modal-dialog">
			<form method="post" action="{{route('reminder.store')}}" class="form-horizontal" id="addForm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title text-center" id="reminderModalLabel">Set Your Reminder</h4>
					</div>
					<div class="modal-body">
                                        <p style="padding:20px">
					    We recommend training daily. Set a reminder to your calendar (Google Calendar, iCal, Outlook) today!
					</p>
								       
						<div class="form-group">
							<label class="col-sm-3 control-label">Event</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="event" id="event" value="My eyeQ Training" placeholder="Event">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Description</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="description" id="description" value="Increasing my brain performance at home, work and school" placeholder="Description">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Location</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="location" id="location" value="www.eyeqadvantage.org" placeholder="Location">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Start Date</label>
							<div class="col-sm-8">
								<div class='input-group date' id='datetimepicker6'>
									<input type='text' class="form-control" name="reminder_from" id="reminder_from">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar" style="z-index:100"></span>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group ">
							<label class="col-sm-3 control-label">End Date</label>
							<div class="col-sm-8">
								<div class='input-group date' id='datetimepicker7'>
									<input type='text' class="form-control" name="reminder_to" id="reminder_to">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar" style="z-index:100"></span>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Repeat</label>
							<div class="col-sm-8">
								<select class="form-control" name="interval" id="interval">
									<option disabled> Select Interval </option>
									<option value="1"> Daily </option>
									<option value="2"> Every 2 days </option>
									<option value="3"> Every 3 days </option>
									<option value="4"> Every 4 days </option>
									<option value="5"> Every 5 days </option>
									<option value="weekly"> Weekly </option>
									<option value="monthly"> Monthly </option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-8">
								<div title="Add to Calendar" class="addeventatc">
									Add to Calendar
									<span class="start">10/21/2016 09:00 AM</span>
									<span class="end">10/21/2016 11:00 AM</span>
									<span class="timezone">{{ \Illuminate\Support\Facades\Auth::user()->userTZ() }}</span>
									<span class="title">Summary of the event</span>
									<span class="description">Description of the event<br>Example of a new line</span>
									<span class="location">Location of the event</span>
									<span class="organizer">{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>
									<span class="organizer_email">{{ \Illuminate\Support\Facades\Auth::user()->email }}</span>
									<span class="facebook_event">https://www.facebook.com/events/703782616363133</span>
									<span class="date_format">MM/DD/YYYY</span>
									<span class="recurring">FREQ=DAILY;COUNT=10</span>
									<span class="status">confirmed</span>
									<span class="client">aTtNefDsuzzxqhBmgmnd21881</span>
									{{--<span class="reminders"></span>--}}
								</div>
							</div>
						</div>

						{{--<hr>--}}
						{{--<div class="modal-footer">--}}
							{{--<button type="button" data-dismiss="modal" class="btn" id="cancel">Cancel</button>--}}
							{{--<button type="submit"  class="btn btn-primary">Submit</button>--}}
						{{--</div>--}}
					</div>
				</div>
			</form>
		</div>
	</div>
@endif


@if ($show_lti_modal)
    <!--modal start for showing error for next session -->
    <div class="modal fade" id="sessionStartModal" tabindex="-1" role="dialog" aria-labelledby="sessionStartModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center" id="sessionStartModalLabel">Welcome {{ Auth::user()->name }}</h4>
                </div>
                <div class="modal-body text-center">
                    <p style="padding:20px">
                        You have already completed an eyeQ session today.
                        You must wait {{ Session::get('timeUntilNextSession') }} until your next session.
                        Please log out of eyeQ and return to Canvas to begin your next eyeQ assignment.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!--modal end for showing error for next session -->
@endif

    <div class="col-md-4">


    @if ($unlocks['u_reporting'])

        <div class="panel panel-default">
            <div class="panel-heading text-center">
                Reading Speed Snapshot
            </div>
            <div class="panel-body">
                <!-- Beginning Speed -->
                <div class="row">
                    <div class="col-xs-8 text-left">
                        <p style="vertical-align:middle;line-height: 35px;">Initial Speed</p>
                    </div>
                    <div class="col-xs-4 text-right">
                        <span class="btn btn-outline btn-info pull-right" style="cursor:default;">
                        <strong>{{ $speeds['initial_wpm'] }} WPM</strong></span>
                    </div>
                </div>
                <!-- Current Speed -->
                <div class="row">
                    <div class="col-xs-8 text-left">
                        <p style="vertical-align:middle;line-height: 35px;">Current Speed (Avg)<sup>1</sup></p>
                    </div>
                    <div class="col-xs-4 text-right">
                        <span class="btn btn-outline btn-success pull-right" style="cursor:default;">
                        <strong>{{ $speeds['average_wpm'] }} WPM</strong></span>
                    </div>
                </div>
                <small><em><sup>1</sup>Average of last 5 post-training speed tests.</em></small>
            </div>

            @if ($speeds['initial_ers'])
                <div class="panel-heading text-center">
                    Comprehension Snapshot
                </div>
                <div class="panel-body">
                    <!-- Beginning Speed -->
                    <div class="row">
                        <div class="col-xs-8 text-left">
                            <p style="vertical-align:middle;line-height: 35px;">Initial ERS</p>
                        </div>
                        <div class="col-xs-4 text-right">
                            <span class="btn btn-outline btn-info pull-right" style="cursor:default;">
                            <strong>{{ $speeds['initial_ers'] }} WPM</strong></span>
                        </div>
                    </div>
                    <!-- Current Speed -->
                    <div class="row">
                        <div class="col-xs-8 text-left">
                            <p style="vertical-align:middle;line-height: 35px;">Current ERS (Avg)<sup>2</sup></p>
                        </div>
                        <div class="col-xs-4 text-right">
                            <span class="btn btn-outline btn-success pull-right" style="cursor:default;">
                            <strong>{{ $speeds['average_ers'] }} WPM</strong></span>
                        </div>
                    </div>
                    <small><em><sup>2</sup>Average of last 3 comprehension tests.</em></small>
                </div>
            @endif

            @if ($speeds['initial_typing'])
                <div class="panel-heading text-center">
                    Typing Speed Snapshot
                </div>
                <div class="panel-body">
                    <!-- Beginning Speed -->
                    <div class="row">
                        <div class="col-xs-8 text-left">
                            <p style="vertical-align:middle;line-height: 35px;">Initial Speed</p>
                        </div>
                        <div class="col-xs-4 text-right">
                            <span class="btn btn-outline btn-info pull-right" style="cursor:default;">
                            <strong>{{ $speeds['initial_typing'] }} Net WPM</strong></span>
                        </div>
                    </div>
                    <!-- Current Speed -->
                    <div class="row">
                        <div class="col-xs-8 text-left">
                            <p style="vertical-align:middle;line-height: 35px;">Current Speed (Avg)<sup>3</sup></p>
                        </div>
                        <div class="col-xs-4 text-right">
                            <span class="btn btn-outline btn-success pull-right" style="cursor:default;">
                            <strong>{{ $speeds['average_typing'] }} Net WPM</strong></span>
                        </div>
                    </div>
                    <small><em><sup>3</sup>Average of last 3 typing speed tests.</em></small>
                </div>
            @endif

            <div class="panel-footer">
                <a href="/{{ $package->url }}/reports" class="btn btn-link btn-block">View Progress Details</a>
            </div>
        </div>

	<?php
	/* Achievement Panel
	for ($i=0; $i<3; $i++)
		$image[$i] = '';
	$i = 0;
	foreach ($this->badges as $badge) {
		$imageurl = $badge['image'];
		$image[$i] = "<img src='/images/badges/$imageurl' style='width:90%'/>";
		$i++;
		echo '
					<div class="panel panel-default">
                        <div class="panel-heading text-center">
                            Recent Achievements
                        </div>
                        <div class="panel-body text-center">
                            <!--We show 3 at the most-->
                            <div class="row">

                                <div class="col-xs-4 text-center">'. $image[0] .'</div>
                                <div class="col-xs-4 text-center">'. $image[1] .'</div>
                                <div class="col-xs-4 text-center">'. $image[2] .'</div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                            <a href="/eyeq/reports/achievements" class="btn btn-link btn-block">See All Achievements</a>

                        </div>
                    </div>';
    }
	*/
    ?>
@endif
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            Latest from the eyeQ Blog
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<script src="/js/feedget-1.1.2.min.js"></script>
							<script>
								$(function() {
									$('#rssblog').feedget(
										{
											feed: 'http://infinitemind.io/mind-resources/feed/',
						                    direction: 'vertical',
						                    entries: 1,
						                    images: true,
						                    buttons: true,
						                    facebook: false,
						                    reverse: false
										});
								});
							</script>

							<div id="rssblog" style="width:100%;float:left;"></div>



                           <div class="text-center">
                                <a href="https://twitter.com/eyeQsuccess" target="_blank"><button type="button" class="btn btn-info btn-circle"><i class="fa fa-twitter"></i></button></a>
                                <a href="https://www.facebook.com/eyeQbyInfiniteMind" target="_blank"><button type="button" class="btn btn-primary btn-circle"><i class="fa fa-facebook"></i></button></a>
                                <a href="https://plus.google.com/+Eyeqadvantage/posts" target="_blank"><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-google-plus"></i></button></a>

                                <!-- Move the discount link to billing page
                                <br><br>
                                Give friends &amp; family 50% off!<br>
                                <a href="https://eyeqadvantage.com/join/family-discount" target="_blank" class="btn btn-success">Get the Family Discount >></a>
                                -->
                            </div>
                        </div>
                        <!-- /.panel-body -->
                </div>
                <!-- /.col-lg-4 -->
        </div>
            <!-- /.row -->
    </div>
</div>
<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
{{ HTML::script('/packages/bootstrapvalidator/js/bootstrapValidator.min.js') }}
{{ HTML::script('js/jquery.bootstrap-growl.min.js') }}

<!-- AddEvent Settings -->
<script type="text/javascript">
	window.addeventasync = function(){
		addeventatc.settings({
			license    : "aTtNefDsuzzxqhBmgmnd21881",
			css        : true,
			outlook    : {show:false, text:"Outlook"},
			google     : {show:true, text:"Google <em>(online)</em>"},
			yahoo      : {show:false, text:"Yahoo <em>(online)</em>"},
			outlookcom : {show:true, text:"Outlook.com <em>(online)</em>"},
			appleical  : {show:true, text:"Apple Calendar"},
			facebook   : {show:false, text:"Facebook Event"},
			dropdown   : {order:"appleical,google,outlookcom"}
		});

		// Capture click on button

		addeventatc.register('button-click', function(obj) {
			var event	= $('#event').val();
			var description = $('#description').val();
			var location 	= $('#location').val();
			var dateFrom 	= $('#reminder_from').val();
			var dateTo 	= $('#reminder_to').val();
			var interval 	= $('#interval').val();

			if (interval == 'weekly') {
				interval = 'FREQ=WEEKLY;INTERVAL=1';
			} else if (interval == 'monthly') {
			        interval = 'FREQ=MONTHLY;INTERVAL=1';
			} else {
				interval = 'FREQ=DAILY;INTERVAL='+interval;
			}

			$('.addeventatc').find('.title').text(event);
			$('.addeventatc').find('.description').text(description);
			$('.addeventatc').find('.start').text(dateFrom);
			$('.addeventatc').find('.end').text(dateTo);
			$('.addeventatc').find('.location').text(location);
			$('.addeventatc').find('.recurring').text(interval);
			//$('.addeventatc').find('.description').text(description);

		});
	};



</script>

<script type="text/javascript">
	var dateToday = new Date();
        dateToday.setMinutes(dateToday.getMinutes() - 15);
        var dateDefault = new Date();
        dateDefault.setMinutes(dateDefault.getMinutes() + 15);

	$(function () {
		$('#datetimepicker6').datetimepicker({
		        minDate: dateToday,
		        defaultDate: dateDefault, 
		        stepping: 15
		});
		$('#datetimepicker7').datetimepicker({
			useCurrent: false, //Important! See issue #1075
		        //minDate: dateToday,
		        stepping: 15,
			disabledDates: [
				$('#datetimepicker6').val()
			]
		});

		/*
		$("#datetimepicker6").on("dp.change", function (e) {
			$('#datetimepicker7').data("DateTimePicker").minDate(e.date);
		});
		$("#datetimepicker7").on("dp.change", function (e) {
			$('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
		});
		*/

	});


var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag)

@if ($section)
	var player;
	function onYouTubeIframeAPIReady() {
		player = new YT.Player('ytplayer', {
			height: '480',
			width: '853',
			videoId: '{{ $section->youtube }}',
			playerVars: {
				'rel': 0,
				'modestbranding': 1,
				'controls': 0,
				},
			events: {
				'onReady': onPlayerReady,
				'onStateChange': onPlayerStateChange
			}
		});
		setPlayerControls();
	}

	function onPlayerReady(event) {
	@if ($video)

	    @if ($level_num == 1)
	        var hidecount = 0
	        $('#reminderModal').modal('show');
	        $('#reminderModal').on('hidden.bs.modal', function() {
		    if (hidecount == 0) {
		        $('#videoModal').modal('show');
                        $.ajax({
                            type: 'POST',
                            url: "/setvideoshown",
                            dataType: "json"
                        });
		    }
		    hidecount++;
		});
	    @else
		$('#videoModal').modal('show');
                $.ajax({
                    type: 'POST',
                    url: "/setvideoshown",
                    dataType: "json"
                });
	    @endif
	@endif
	}

	function onPlayerStateChange(state) {
		if(state.data === 0) {
			$('#videoModal').modal('hide');
		}
	}
	function setPlayerControls() {
		$('#videoModal').on('show.bs.modal', function(e) {player.seekTo(0); player.playVideo();} );
		$('#videoModal').on('hide.bs.modal', function(e) {player.pauseVideo();} );
		$('#aboutlink').click(function(e) {player.pauseVideo()} );
		$('#homelink').click(function(e) {player.playVideo()} );
	}
@endif

$(function() {

        $('a').popover({
                trigger: 'hover',
                html: true,
                placement: 'right',
                container: 'body'
        });

        $('#myTab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

    @if ($show_lti_modal)
        $('#sessionStartModal').modal('show');
    @endif

        $('#addForm').bootstrapValidator({
                message: 'This value is not valid',
                submitButtons: 'button[type="submit"]',
                feedbackIcons: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                        event: {
                                validators: {
                                        notEmpty: {
                                                message: 'The event is required and cannot be empty'
                                        }
                                }
                        },
                        description: {
                                validators: {
                                        notEmpty: {
                                                message: 'The description is required and cannot be empty'
                                        }
                                }
                        },
                        interval: {
                                validators: {
                                        notEmpty: {
                                                message: 'The repeat interval is required and cannot be empty'
                                        }
                                }
                        }
                }
        })

	.on('error.field.bv', function(e, data) {
	    $(".addeventatc").hide();
	})
	.on('success.field.bv', function(e, data) {
	    $(".addeventatc").show();
	})
        .on('success.form.bv', function(e) {
                // Prevent form submission
                e.preventDefault();
                // Get the form instance
                var $form = $(e.target);
                console.log($form.serialize());
                // Get the BootstrapValidator instance
                var bv = $form.data('bootstrapValidator');

                // Use Ajax to submit form data
                $.post($form.attr('action'), $form.serialize(), function(result) {
                        // ... Process the result ...

                        $.bootstrapGrowl("reminder added successfully", { type:'success' });

                        $('#reminderModal').modal('hide');
                        $('#addForm')[0].reset();
                        $('form').bootstrapValidator('resetForm', true);
                }, 'json');
        });

});
</script>
@stop
