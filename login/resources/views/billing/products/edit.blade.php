@extends('master')

@section('content')

	{!! Form::open() !!}
	<div class="row">
		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-8 form-group">
					<label class="form-label">Naam</label>
					{!! Form::text('name', Input::old('name') ? Input::old('name') : $product->name, array('class' => 'form-control', 'maxlength' => '100')) !!}
				</div>
				<div class="col-md-4 form-group">
					<label class="form-label">Artikelnummer</label>
					{!! Form::text('productnumber', Input::old('productnumber') ? Input::old('productnumber') : $product->productnumber, array('class' => 'form-control', 'maxlength' => '10')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Omschrijving op factuur</label>
					{!! Form::text('description', Input::old('description') ? Input::old('description') : $product->description, array('class' => 'form-control', 'maxlength' => '250')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-4 form-group">
					<label class="form-label">Prijs excl. Btw</label>
					{!! Form::text('price', Input::old('price') ? Input::old('price') : $product->price, array('class' => 'form-control auto', 'data-a-sign' => '€ ')) !!}
				</div>
				<div class="col-md-4 form-group">
					<label class="form-label">Btw percentage</label>
					{!! Form::text('tax', Input::old('tax') ? Input::old('tax') : $product->tax, array('class' => 'form-control auto', 'data-v-min' => '0', 'data-v-max' => '99')) !!}
				</div>
				<div class="col-md-4 form-group">
					<label class="form-label">Omzetrekening</label>
					{!! Form::text('ledger', Input::old('ledger') ? Input::old('ledger') : $product->ledger, array('class' => 'form-control auto', 'data-v-min' => '0', 'data-v-max' => '99999', 'data-a-sep' => '')) !!}
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{!! Form::close() !!}

@endsection