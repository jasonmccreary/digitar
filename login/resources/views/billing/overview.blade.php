@extends('master')

@section('content')
	<?php
	$monthTotal = 0;
	foreach(Invoices::where( DB::raw('MONTH(date)'), '=', date('n') )->where( DB::raw('YEAR(date)'), '=', date('Y') )->where('cid','=', Auth::user()->cid)->get() as $i) {
		$monthTotal += Invoices::getTotal($i->id,false,false);
	}
	$yearTotal = 0;
	foreach(Invoices::where( DB::raw('YEAR(date)'), '=', date('Y') )->where('cid','=', Auth::user()->cid)->get() as $i) {
		$yearTotal += Invoices::getTotal($i->id,false,false);
	}
	?>
		<div class="row" style="padding: 0 15px;">
			<div class="col-md-6 col-sm-6" style="padding:0;">
				<div class="tiles blue ">
					<div class="tiles-body">
						<div class="tiles-title"> VERKOPEN DEZE MAAND </div>
						<div class="heading"> {!! euro($monthTotal) !!} </div>
						<div class="description"><!-- <i class="icon-custom-up"></i><span class="text-white mini-description ">&nbsp; 5% meer dan vorige maand</span> --></div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6" style="padding:0;">
				<div class="tiles green ">
					<div class="tiles-body">
						<div class="tiles-title"> VERKOPEN DIT JAAR </div>
						<div class="heading"> {!! euro($yearTotal) !!} </div>
						<div class="description"><!-- <i class="icon-custom-up"></i><span class="text-white mini-description ">&nbsp; 2% meer dan vorig jaar</span> --></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="grid simple ">
	<div class="grid-body ">

		<div class="pull-right">
			<a href="/billing/invoices/add" class="btn btn-success">Nieuwe factuur</a>
		</div>	

		<?php 
		$openInv = Invoices::where('cid','=',Auth::user()->cid)->where('status','=',1)->orWhere('cid','=',Auth::user()->cid)->where('status','=',5)->where('date', 'like', Session::get('year').'%')->orderBy('date', 'desc')->limit(250); 
		?>
		@if ($openInv->count() > 0)
			<h1>Openstaande posten</h1>
			<table class="table no-more-tables m-t-30" id="datatable" data-sort-order="desc">
				<thead>
					<tr>
						<th  style="width:5%">Datum</th>
						<th style="width:9%">Debiteur</th>
						<th style="width:22%">Factuurnummer</th>
						<th style="width:6%">Totaal</th>
						<th style="width:6%">Status</th>
						<th style="width:1%"></th>
					</tr>
				</thead>
				<tbody>
					@foreach($openInv->get() as $i)
						<tr>
							<td class="v-align-middle"><span style="display:none;">{!!$i->date!!}</span> {!! simple_date($i->date) !!}</td>
							<td class="v-align-middle">{!! Debtors::getName($i->did) !!}</td>
							<td class="v-align-middle"><span class="muted">{!! $i->invoicenumber !!}</span></td>
							<td><span class="muted">{!! euro(Invoices::getTotal($i->id)) !!}</span></td>
							<td>{!! Invoices::showStatus($i->id) !!}</td>
							<td><a href="/billing/pdf/view/{!! $i->id !!}" onclick="window.open('/billing/pdf/view/{!! $i->id !!}', 'Factuur bekijken', 'width=820,height=850,scrollbars=yes,toolbar=no,location=no'); return false" class="btn btn-white btn-xs btn-mini" title="Factuur bekijken"><i class="fa fa-search"></i></a></td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<center><h1>Er zijn geen openstaande posten <div class="smiley">:)</div></h1></center>
		@endif

@endsection