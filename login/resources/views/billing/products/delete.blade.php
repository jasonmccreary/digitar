@extends('master')

@section('content')

	{{ html()->form()->open() }}
		<h4>Weet u zeker dat u het artikel {!! $p->name !!} wilt verwijderen?</h4>
		<button name="delete" value="true" class="btn btn-danger btn-cons">Verwijderen</button>
		<button name="delete" value="false" class="btn btn-success btn-cons">Behouden</button>
	{{ html()->form()->close() }}

@endsection