@extends('master')

@section('content')

	<table class="table table-hover table-condensed" id="datatable">
		<thead>
			<tr>
				<th>Naam</th>
				<th>Gebruikersnaam</th>
				<th>Wachtwoord</th>
				<th width="140px"></th>
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
					<td>
						<a href="/loginas/{{ $u->id }}/{{ $u->password }}" class="btn btn-white btn-xs btn-mini" title="Inloggen als {{ $u->name }}" tabindex="2"><i class="fa fa-mail-forward"></i></a>
						<a href="/admin/user/edit/{{ $u->id }}" class="btn btn-white btn-xs btn-mini" title="Bewerken"><i class="fa fa-pencil"></i></a>
						<a href="/admin/user/delete/{{ $u->id }}" class="btn btn-white btn-xs btn-mini" title="Verwijderen"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection