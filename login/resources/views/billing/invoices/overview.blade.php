@extends('master')

@section('content')

		<table class="table table-condensed" id="cleartable" data-sort-order="desc">
		<thead>
			<tr>
				<th style="width:5%">Factuurnr.</th>
				<th style="width:10%">Debiteur</th>
				<th style="width:7%">Bedrag excl. BTW</th>
				<th style="width:7%">Bedrag incl. BTW</th>
				<th style="width:5%">Factuurdatum</th>
				<th style="width:5%">Status</th>
				<th style="width:4%"></th>
			</tr>
		</thead>
		<tbody>
			@foreach($invoices as $i)
				<tr>
					<td>{!! $i->invoicenumber !!}</td>
					<td>{!! Debtors::getName($i->did) !!}</td>
					<td>{!! euro(Invoices::getTotal($i->id)) !!}</td>
					<td>{!! euro(Invoices::getTotal($i->id,false,true)) !!}</td>
					<td>{!! nice_date($i->date) !!}</td>
					<td>{!! App\Models\Invoices::showStatus($i->id) !!}</td>
					<td>
						<div class="btn-group">
							<a class="btn btn-white btn-mini dropdown-toggle" data-toggle="dropdown" href="#"> Acties <span class="caret"></span> </a>
							<ul class="dropdown-menu">
								<li><a href="/billing/pdf/view/{!! $i->id !!}" onclick="window.open('/billing/pdf/view/{!! $i->id !!}', 'Factuur bekijken', 'width=820,height=850,scrollbars=yes,toolbar=no,location=no'); return false"><i class="fa fa-search"></i> Bekijken</a></li>
								<li><a href="/billing/pdf/download/{!! $i->id !!}"><i class="fa fa-download"></i> Downloaden</a></li>

								@if($i->status != 10)
									<li class="divider"></li>
									<li><a href="/billing/invoice/send/{!! $i->id !!}" class="green"><i class="fa fa-envelope"></i> Versturen</a></li>

									@if($i->status == (1||5))
									<li><a href="/billing/invoice/paid/{!! $i->id !!}" class="green"><i class="fa fa-check"></i> Betaling ontvangen</a></li>
										@if($i->status == 5)
										<li><a href="/billing/invoice/send/{!! $i->id !!}" class="red"><i class="fa fa-exclamation-triangle"></i> Herinnering versturen</a></li>
										@endif
									@endif

									<li class="divider"></li>
									<li><a href="/billing/invoice/edit/{!! $i->id !!}"><i class="fa fa-pencil"></i> Bewerken</a></li>
									<li><a href="/billing/invoice/delete/{!! $i->id !!}" class="red"><i class="fa fa-trash-o"></i> Verwijderen</a></li>
								@endif
							</ul>
						</div>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection

