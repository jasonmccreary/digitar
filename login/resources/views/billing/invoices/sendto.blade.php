@extends('master')

@section('content')

	{{ Form::open() }}
		<h4 class="m-b-20">Kies een mail layout</h4>

		<div class="row form-row m-l-5 m-b-30">
			<div class="form-group sm-select">
				{{ Form::select('maillayout', Layouts::getMailselect(), '0', array('style' => 'width:100%;')) }}
			</div>
		</div>

		<button name="send" value="mail" class="btn btn-success btn-cons">Verstuur</button>
		<button name="send" value="false" class="btn btn-danger btn-cons pull-right">Annuleren</button>
	{{ Form::close() }}

@endsection