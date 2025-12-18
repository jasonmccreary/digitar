@extends('master')

@section('content')

	<table class="table table-striped table-flip-scroll cf">
		<thead>
			<tr>
				<th>Naam</th>
				<th>Gebruikersnaam</th>
				<th>Wachtwoord</th>
				<th>Onverwerkt</th>
				<th>Niet ingeboekt</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $u)
				<?php
				if ($u->lastlogin != '') {
					$Date = new DateTime($u->lastlogin);
					$lastlogin = 'Laatst ingelogd: '. $Date->format("j F");
				}else{
					$lastlogin = '';
				}
				?>
				<tr>
					<td>{{ $u->name }}</td>
					<td><span class="tip" title="{{ $lastlogin }}" data-placement="bottom">{{ $u->username }}</span></td>
					<td><input type="password" class="iWachtwoord" value="{{ Crypt::decrypt($u->password) }}" /></td>
					<td>{{ Files::getNumUnsorted($u->id) }}</td>
					<td>{{ Files::getNumUnbooked($u->id) }}</td>
					<td>
						<a href="/loginas/{{ $u->id }}/{{ $u->password }}" class="btn btn-white btn-xs btn-mini" title="Inloggen als {{ $u->name }}"><i class="fa fa-mail-forward"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection