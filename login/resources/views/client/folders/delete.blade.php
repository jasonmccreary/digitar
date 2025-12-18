@extends('master')

@section('content')

	{!! Form::open() !!}
		<h4>Weet u zeker dat u deze map wilt verwijderen? Alle bestanden die gebruikers in deze map hebben staan zullen verloren gaan!</h4>
		<button name="delete" value="true" class="btn btn-danger btn-cons">Verwijderen</button>
		<button name="delete" value="false" class="btn btn-success btn-cons">Behouden</button>
	{!! Form::close() !!}

@endsection