@extends('master')

@section('content')

	{{ html()->form()->open() }}
	<div class="row">
		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-8 form-group">
					<label class="form-label">Naam</label>
					{{ html()->text('name', Request::old('name'))->class('form-control')->maxlength('100') }}
				</div>
				<div class="col-md-4 form-group">
					<label class="form-label">Artikelnummer</label>
					{{ html()->text('productnumber', strlen(Request::old('productnumber')) > 0 ? Request::old('productnumber') : "P" . str_pad(Products::newProdNumber(), 4, "0", STR_PAD_LEFT))->class('form-control')->maxlength('10') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Omschrijving op factuur</label>
					{{ html()->text('description', Request::old('description'))->class('form-control')->maxlength('250') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-4 form-group">
					<label class="form-label">Prijs excl. Btw</label>
					{{ html()->text('price', Request::old('price'))->class('form-control auto')->data('a-sign', '€ ') }}
				</div>
				<div class="col-md-4 form-group">
					<label class="form-label">Btw percentage</label>
					{{ html()->text('tax', strlen(Request::old('tax')) > 0 ? Request::old('tax') : 21)->class('form-control auto')->data('v-min', '0')->data('v-max', '99') }}
				</div>
				<div class="col-md-4 form-group">
					<label class="form-label">Omzetrekening</label>
					{{ html()->text('ledger', strlen(Request::old('ledger')) > 0 ? Request::old('ledger') : 8000)->class('form-control auto')->data('v-min', '0')->data('v-max', '99999')->data('a-sep', '') }}
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{{ html()->form()->close() }}

@endsection