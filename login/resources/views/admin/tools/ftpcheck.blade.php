@extends('master')

@section('content')
	
	@if(count($users) > 0)
	<div class="alert alert-error">
		<button class="close" data-dismiss="alert"></button>
		Er zijn {{ count($users) }} gebruikers gevonden die <span class="semi-bold">geen</span> FTP account hebben!
	</div>

	<table class="table table-hover table-condensed" id="datatable">
		<thead>
			<tr>
				<th>Naam</th>
				<th>Gebruikersnaam</th>
				<th width="140px"></th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $u)
				<tr>
					<td>{{ $u->name }}</td>
					<td>{{ $u->username }}</td>
					<td>
						<form method="post">
							<input type="hidden" name="organization" value="{{ User::getUserUsername($u->oid) }}" />
							<input type="hidden" name="password" value="{{ Crypt::decrypt($u->password) }}" />
							<button name="username" value="{{ $u->username }}" class="btn btn-white btn-xs btn-mini" title="Forwarder creëren"><i class="fa fa-wrench"></i></button>
						</form>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	@else

		<h1>Geen fouten gevonden!</h1>

	@endif

@stop