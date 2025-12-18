@extends('master')

@section('content')

	{!! Form::open() !!}
		@if(strlen(Debtors::get($i->did)->email) > 5)
			<h4 class="m-b-40">Hoe wilt u de factuur versturen?</h4>
			<button name="send" value="mail" class="btn btn-success btn-cons">Per mail</button>
		@else
			<h4 class="m-b-40">Verstuur de factuur per post!<br /><small>Versturen per e-mail kan alleen als er een e-mailadres is ingevuld bij de debiteur!</small></h4>
		@endif
		<button name="send" value="print" class="btn btn-success btn-cons">Per post</button>
		<button name="send" value="false" class="btn btn-danger btn-cons pull-right">Annuleren</button>
	{!! Form::close() !!}

@endsection