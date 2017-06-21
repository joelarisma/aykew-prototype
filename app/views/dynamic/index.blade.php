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
<form action="{{ url('session', $current_session->session) }}" method="get" id="submit">
	<button>start</button>
</form>
@endsection