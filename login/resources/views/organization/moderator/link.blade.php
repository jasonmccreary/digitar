@extends('master')

@section('content')

	<table class="table table-striped table-flip-scroll cf">
		<thead>
			<tr>
				<th>Naam</th>
				<th>Beheerder</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $u)
				<tr>
					<td>{!! $u->name !!}</td>
					<td>{!! Usermods::getModNames($u->id) !!}</td>
					<td>
						<a href="/organization/moderator/linkedit/{!! $u->id !!}" class="btn btn-white btn-xs btn-mini" title="Bewerken"><i class="fa fa-pencil"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection