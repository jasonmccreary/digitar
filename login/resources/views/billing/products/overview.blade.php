@extends('master')

@section('content')
	
		<table class="table table-condensed" id="cleartable">
		<thead>
			<tr>
				<th style="width:5%">Productnr.</th>
				<th style="width:10%">Naam</th>
				<th style="width:7%">Prijs excl. BTW</th>
				<th style="width:7%">Omzetrekening</th>
				<th style="width:4%"></th>
			</tr>
		</thead>
		<tbody>
			@foreach($products as $p)
				<tr>
					<td>{{ $p->productnumber }}</td>
					<td>{{ $p->name }}</td>
					<td>{{ euro($p->price) }}</td>
					<td>{{ $p->ledger }}</td>
					<td>
						<div class="btn-group"> 
							<a class="btn btn-white btn-mini dropdown-toggle" data-toggle="dropdown" href="#"> Acties <span class="caret"></span> </a>
							<ul class="dropdown-menu">									
								<li><a href="/billing/product/edit/{{ $p->id }}"><i class="fa fa-pencil"></i> Bewerken</a></li>
								<li><a href="/billing/product/delete/{{ $p->id }}" class="red"><i class="fa fa-trash-o"></i> Verwijderen</a></li>
							</ul>
						</div>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection

