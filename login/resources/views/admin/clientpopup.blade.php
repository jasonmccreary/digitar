@extends('master')

@section('content')
	
	<table width="100%">
		<tr>
			<td>Naam</td>
			<td>{{ $user->name }} - <small>{{ $subuser->name }}</small></td>
		</tr>
		<tr>
			<td>Adres</td>
			<td>
				{{ $user->address }}<br /> 
				{{ $user->zipcode }} {{ $user->city }}
			</td>
		</tr>
		<tr>
			<td>Telefoon</td>
			<td>{{ $user->tell }}</td>
		</tr>
		<tr>
			<td>E-mail</td>
			<td>{{ $user->email }}</td>
		</tr>
		<tr>
			<td>Laatste login</td>
			<td><?php $date = new DateTime($user->lastlogin); echo $date->format("H:i:s") .' <small>(' . $date->format("j F Y") . ')</small>'; ?></td>
		</tr>
		<tr>
			<td>Facturatie module</td>
			<td>{{ $user->billing ? 'Ja' : 'Nee' }}</td>
		</tr>
		<tr>
			<td>Gebruikte opslag</td>
			<td>{{ $dirsize }}</td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 10px;">
				<a onclick="login('/loginas/{{ $subuser->id }}/{{ $subuser->password }}')" class="btn btn-xs btn-mini" title="Inloggen als {{ $subuser->name }}" tabindex="2"><i class="fa fa-mail-forward"></i> inloggen</a>
			</td>
		</tr>
	</table>
	<script language="Javascript">

        function login(url){
            window.opener.location.href=url;
            self.close();
        }

    </script>
		

@endsection