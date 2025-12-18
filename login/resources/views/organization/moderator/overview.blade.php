@extends('master')

@section('content')

	<table class="table table-hover table-condensed" id="datatable">
		<thead>
			<tr>
				<th>Naam</th>
				<th>Gebruikersnaam</th>
				<th>Wachtwoord</th>
				<th>Aantal klanten</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $u)
				<tr>
					<td>{!! $u->name !!}</td>
					<td>{!! $u->username !!}</td>
					<td><input type="password" class="iWachtwoord" value="{!! Crypt::decrypt($u->password) !!}" /></td>
					<td>{!! Usermods::countClients($u->id) !!}</td>
					<td>
						<a href="/loginas/{!! $u->id !!}/{!! $u->password !!}" class="btn btn-white btn-xs btn-mini" title="Inloggen als {!! $u->name !!}"><i class="fa fa-mail-forward"></i></a>
						<a href="/organization/moderator/edit/{!! $u->id !!}" class="btn btn-white btn-xs btn-mini" title="Bewerken"><i class="fa fa-pencil"></i></a>
						<a href="/organization/moderator/delete/{!! $u->id !!}" class="btn btn-white btn-xs btn-mini" title="Verwijderen"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection