@extends('layouts.home')

@section('content')
<div id="page-wrapper">
    <div class="row" style="padding-top:15px;">
        <div class="col-md-10 col-md-offset-1">
    	    <div class="panel panel-default">
                <div class="panel-body text-center">
			    	<div style="padding: 0px 5px;" class="row text-left">
                		<h3>Reports: <i>{{ ucwords($type) }} {{ $no }}</i></h3>

                		@if(count($reports) < 1)
                			<div class="alert alert-danger">No reports found on this level!</div>
                		@endif

                		<!-- table for results -->
                		@foreach($reports as $session => $report)
                		<div style="padding-bottom: 10px;" class="col-md-12">
                		<p><strong>Session {{ $session }}</strong></p>
	                		<table style="width:100%;">
	                			<tbody>
	                				<tr>
	                					<th>TIME SPENT (secs)</th>
	                					<th>WORD COUNT</th>
	                					<th>WPM</th>
	                					<th>ERS</th>
	                					<th>NET</th>
	                					<th>SCORE</th>
	                					<th>PERCENTAGE</th>
	                					<th>EYE POWER</th>
	                					<th>TEST TYPE</th>
	                				</tr>
                					@foreach($report as $exercise)
                						<tr>
                							<td>{{ $exercise->seconds }}</td>
                							<td>{{ $exercise->wordcount }}</td>
                							<td>{{ $exercise->wpm }}</td>
                							<td>{{ $exercise->ers }}</td>
                							<td>{{ $exercise->net }}</td>
                							<td>{{ $exercise->score }}</td>
											<td>{{ $exercise->percentage }}</td>
											<td>{{ $exercise->eye_power }}</td>
											<td>{{ $exercise->session_exercise_type_id }}</td>
                						</tr>
                					@endforeach
	                			</tbody>
	                		</table>
	                	</div>
                		@endforeach
                		<!-- table for results -->

                	</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection