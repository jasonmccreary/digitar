@extends('master')

@section('content')

	{!! Form::open() !!}
	<div class="row">
		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-8 form-group">
					<label class="form-label">Naam</label>
					{!! Form::text('name', Request::old('name'), array('class' => 'form-control', 'maxlength' => '100')) !!}
				</div>
				<div class="col-md-4 form-group">
					<label class="form-label">Artikelnummer</label>
					{!! Form::text('productnumber', strlen(Request::old('productnumber')) > 0 ? Request::old('productnumber') : "P".str_pad(Products::newProdNumber(), 4, "0", STR_PAD_LEFT), array('class' => 'form-control', 'maxlength' => '10')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Omschrijving op factuur</label>
					{!! Form::text('description', Request::old('description'), array('class' => 'form-control', 'maxlength' => '250')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-4 form-group">
					<label class="form-label">Prijs excl. Btw</label>
					{!! Form::text('price', Request::old('price'), array('class' => 'form-control auto', 'data-a-sign' => '€ ')) !!}
				</div>
				<div class="col-md-4 form-group">
					<label class="form-label">Btw percentage</label>
					{!! Form::text('tax', strlen(Request::old('tax')) > 0 ? Request::old('tax') : 21, array('class' => 'form-control auto', 'data-v-min' => '0', 'data-v-max' => '99')) !!}
				</div>
				<div class="col-md-4 form-group">
					<label class="form-label">Omzetrekening</label>
					{!! Form::text('ledger', strlen(Request::old('ledger')) > 0 ? Request::old('ledger') : 8000, array('class' => 'form-control auto', 'data-v-min' => '0', 'data-v-max' => '99999', 'data-a-sep' => '')) !!}
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{!! Form::close() !!}

@endsection