@extends('master')

@section('content')

	@if(Auth::user()->lookonly == 0)
	<form action="/user/upload/post" class="dropzone2">
	    <div class="fallback">
	        <input name="file" type="file" multiple />
	    </div>
	</form>
	@else
		<b>U mag niets uploaden</b>
	@endif

@endsection