@extends('master')

@section('content')
	
		<table class="table table-condensed" id="cleartable">
		<thead>
			<tr>
				<th style="width:5%">Debiteurnr.</th>
				<th style="width:15%">Naam</th>
				<th style="width:22%">E-mail</th>
				<th style="width:5%"></th>
			</tr>
		</thead>
		<tbody>
			@foreach($debtors as $d)
				<tr>
					<td>{!! $d->debnumber !!}</td>
					<td>{!! $d->name !!}</td>
					<td>{!! $d->email !!}</td>
					<td>
						<a href="/billing/debtor/edit/{!! $d->id !!}" class="btn btn-white btn-xs btn-mini" title="Bewerken"><i class="fa fa-pencil"></i></a>
						<a href="/billing/debtor/delete/{!! $d->id !!}" class="btn btn-white btn-xs btn-mini danger" title="Verwijderen"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection