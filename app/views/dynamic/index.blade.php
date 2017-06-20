@extends('layouts.home')

@section('content')
<table>
	<tbody>
		@foreach($sessions as $i => $session)
		<tr>
			<th>
				Session {{ $session->session }} 
				@if($current_session->session == $i)
					<span>check</span>
				@endif
			</th>
		</tr>
		@endforeach
	</tbody>
</table>
<form action="{{ url('session', $current_session->session) }}" method="post" id="submit">
	<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
	<button>start</button>
</form>
@endsection