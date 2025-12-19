@extends('master')

@section('content')

	{{ html()->form()->open() }}
	<div class="row">
		<div class="col-md-6">
			<h3>Gegevens</h3>
			<div class="form-group">
				<label class="form-label">Organisatie</label>
				<div class="controls">{{ html()->select('oid', $organizations, Request::old('oid'))->style('width:100%') }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Gebruikersnaam</label>
				<div class="controls">{{ html()->text('username', Request::old('username'))->class('form-control')->maxlength('24') }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Volledige naam</label>
				<div class="controls">{{ html()->text('name', Request::old('name'))->class('form-control') }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Adres</label>
				<div class="controls">{{ html()->text('address', Request::old('address'))->class('form-control') }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Postcode</label>
				<div class="controls">{{ html()->text('zipcode', Request::old('zipcode'))->class('form-control') }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Plaats</label>
				<div class="controls">{{ html()->text('city', Request::old('city'))->class('form-control') }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Telefoon nr</label>
				<div class="controls">{{ html()->text('tell', Request::old('tell'))->class('form-control') }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">E-mail</label>
				<div class="controls">{{ html()->text('email', Request::old('email'))->class('form-control') }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Website</label>
				<div class="controls">{{ html()->text('website', Request::old('website'))->class('form-control') }}</div>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-success btn-cons">Toevoegen</button>
			</div>
		</div>
		<div class="col-md-6">
			<h3>Instellingen</h3>
			<div class="checkbox check-success 	">
				{{ html()->checkbox('billing', false, '1')->id('billing') }}
	          <label for="billing">Facturatie</label>
	        </div>
		</div>
	</div>
	{{ html()->form()->close() }}

@endsection