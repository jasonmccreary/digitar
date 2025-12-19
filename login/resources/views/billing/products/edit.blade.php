@extends('master')

@section('content')

	{{ html()->form()->open() }}
	<div class="row">
		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-8 form-group">
					<label class="form-label">Naam</label>
					{{ html()->text('name', Request::old('name') ? Request::old('name') : $product->name)->class('form-control')->maxlength('100') }}
				</div>
				<div class="col-md-4 form-group">
					<label class="form-label">Artikelnummer</label>
					{{ html()->text('productnumber', Request::old('productnumber') ? Request::old('productnumber') : $product->productnumber)->class('form-control')->maxlength('10') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Omschrijving op factuur</label>
					{{ html()->text('description', Request::old('description') ? Request::old('description') : $product->description)->class('form-control')->maxlength('250') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-4 form-group">
					<label class="form-label">Prijs excl. Btw</label>
					{{ html()->text('price', Request::old('price') ? Request::old('price') : $product->price)->class('form-control auto')->data('a-sign', '€ ') }}
				</div>
				<div class="col-md-4 form-group">
					<label class="form-label">Btw percentage</label>
					{{ html()->text('tax', Request::old('tax') ? Request::old('tax') : $product->tax)->class('form-control auto')->data('v-min', '0')->data('v-max', '99') }}
				</div>
				<div class="col-md-4 form-group">
					<label class="form-label">Omzetrekening</label>
					{{ html()->text('ledger', Request::old('ledger') ? Request::old('ledger') : $product->ledger)->class('form-control auto')->data('v-min', '0')->data('v-max', '99999')->data('a-sep', '') }}
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{{ html()->form()->close() }}

@endsection