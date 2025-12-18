@extends('master')

@section('content')

	<table class="table table-hover table-condensed" id="datatable">
		<thead>
			<tr>
				<th>Naam</th>
				<th>Gebruikersnaam</th>
				<th>Wachtwoord</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $u)
				<tr>
					<td>{!! $u->name !!}</td>
					<td>{!! $u->username !!}</td>
					<td><input type="password" class="iWachtwoord" value="{!! Crypt::decrypt($u->password) !!}" /></td>
					<td>
						<a href="/loginas/{!! $u->id !!}/{!! $u->password !!}" class="btn btn-white btn-xs btn-mini" title="Inloggen als {!! $u->name !!}"><i class="fa fa-mail-forward"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection