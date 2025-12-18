@extends('master')

@section('content')

	<table class="table table-striped table-flip-scroll cf">
		<thead>
			<tr>
				<th>Bedrijfsnaam</th>
				<th>Adres</th>
				<th>Postcode</th>
				<th>Plaats</th>
				<th>Telefoon nr</th>
				<th>E-mail</th>
				<th>Website</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($organizations as $org)
				<tr>
					<td>{!! $org->name !!}</td>
					<td>{!! $org->address !!}</td>
					<td>{!! $org->zipcode !!}</td>
					<td>{!! $org->city !!}</td>
					<td>{!! $org->tell !!}</td>
					<td>{!! $org->email !!}</td>
					<td>{!! $org->website !!}</td>
					<td>
						<a href="/admin/organization/edit/{!! $org->id !!}" class="btn btn-white btn-xs btn-mini" title="Bewerken"><i class="fa fa-pencil"></i></a>
						<a href="/admin/organization/delete/{!! $org->id !!}" class="btn btn-white btn-xs btn-mini" title="Verwijderen"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection