@extends('master')

@section('content')
	
	<h4>Weet je zeker dat je de volgende bestanden wilt verwijderen?</h4>

	<form method="post" action="/user/files/bulk">

		<ul>
		@foreach($filesArray as $id => $file) 
			<li>
				{{ $file }}
				<input type="hidden" name="fileid[{{ $id }}]" value="{{ $file }}" />
			</li>			
		@endforeach
		</ul>

		<br/><br/>

		<input type="hidden" name="delete" value="true" />
		<button type="submit" name="sure" value="true" class="btn btn-danger btn-cons">Verwijderen</button>
		<button type="submit" name="sure" value="false" class="btn btn-success btn-cons">Behouden</button>

	</form>

@endsection