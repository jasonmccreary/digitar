@extends('master')

@section('content')

	<table class="table table-hover table-condensed" id="cleartable" data-sort="3" data-sort-order="desc">
      	<thead>
        	<tr>
	          	<th style="width:5%">Datum</th>
				<th style="width:9%">Debiteur</th>
				<th style="width:22%">Factuurnummer</th>
				<th style="width:6%">Totaal</th>
				<th style="width:6%">Status</th>
				<th style="width:1%"></th>
        	</tr>
      	</thead>
      	<tbody>

	@foreach($invoices as $invoice)
		<tr>
	        <td class="v-align-middle">{!! simple_date($invoice->date) !!}</td>
			<td class="v-align-middle">{!! Debtors::getName($invoice->did) !!}</td>
			<td class="v-align-middle"><span class="muted">{!! $invoice->invoicenumber !!}</span></td>
			<td><span class="muted">{!! euro(Invoices::getTotal($invoice->id)) !!}</span></td>
			<td>{!! App\Models\Invoices::showStatus($invoice->id) !!}</td>
			<td><a href="/billing/pdf/view/{!! $invoice->id !!}" onclick="window.open('/billing/pdf/view/{!! $invoice->id !!}', 'Factuur bekijken', 'width=820,height=850,scrollbars=yes,toolbar=no,location=no'); return false" class="btn btn-white btn-xs btn-mini" title="Factuur bekijken"><i class="fa fa-search"></i></a></td>
    	</tr>
	@endforeach
		</tbody>
	</table>

@endsection
