@extends('master')

@section('content')

	{{ Form::open() }}
	<div class="row">
		<div class="col-md-6">
			<h3>Gegevens</h3>
			<div class="form-group">
				<label class="form-label">Organisatie</label>
				<div class="controls">{{ Form::select('oid', $organizations, Input::old('oid'), array('style'=>'width:100%')) }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Gebruikersnaam</label>
				<div class="controls">{{ Form::text('username', Input::old('username'), array('class'=>'form-control', 'maxlength'=>'24')) }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Volledige naam</label>
				<div class="controls">{{ Form::text('name', Input::old('name'), array('class'=>'form-control')) }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Adres</label>
				<div class="controls">{{ Form::text('address', Input::old('address'), array('class'=>'form-control')) }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Postcode</label>
				<div class="controls">{{ Form::text('zipcode', Input::old('zipcode'), array('class'=>'form-control')) }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Plaats</label>
				<div class="controls">{{ Form::text('city', Input::old('city'), array('class'=>'form-control')) }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Telefoon nr</label>
				<div class="controls">{{ Form::text('tell', Input::old('tell'), array('class'=>'form-control')) }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">E-mail</label>
				<div class="controls">{{ Form::text('email', Input::old('email'), array('class'=>'form-control')) }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Website</label>
				<div class="controls">{{ Form::text('website', Input::old('website'), array('class'=>'form-control')) }}</div>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-success btn-cons">Toevoegen</button>
			</div>
		</div>
		<div class="col-md-6">
			<h3>Instellingen</h3>
			<div class="checkbox check-success 	">
				{{ Form::checkbox('billing', '1', false, array('id'=>'billing')) }}
	          <label for="billing">Facturatie</label>
	        </div>
		</div>
	</div>
	{{ Form::close() }}

@endsection