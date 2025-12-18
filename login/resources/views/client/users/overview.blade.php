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
				<?php
				if ($u->lastlogin != '') {
					$Date = new DateTime($u->lastlogin);
					$lastlogin = 'Laatst ingelogd: '. $Date->format("j F");
				}else{
					$lastlogin = '';
				}
				?>
				<tr>
					<td>{!! $u->name !!}</td>
					<td><span class="tip" title="{!! $lastlogin !!}" data-placement="bottom">{!! $u->username !!}</span></td>
					<td><input type="password" class="iWachtwoord" value="{!! Crypt::decrypt($u->password) !!}" /></td>
					<td>
						<a href="/loginas/{!! $u->id !!}/{!! $u->password !!}" class="btn btn-white btn-xs btn-mini" title="Inloggen als {!! $u->name !!}"><i class="fa fa-mail-forward"></i></a>
						<a href="/client/user/edit/{!! $u->id !!}" class="btn btn-white btn-xs btn-mini" title="Bewerken"><i class="fa fa-pencil"></i></a>
						<a href="/client/user/delete/{!! $u->id !!}" class="btn btn-white btn-xs btn-mini" title="Verwijderen"><i class="fa fa-trash-o"></i></a>
						<a href="/client/user/credentials/{!! $u->id !!}" class="btn btn-white btn-xs btn-mini" title="Verstuur inlog gegevens"><i class="fa fa-envelope-o"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection