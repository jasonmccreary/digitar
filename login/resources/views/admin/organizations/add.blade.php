@extends('master')

@section('content')

	{{ html()->form()->open() }}
	<div class="form-group">
		<label class="form-label">Bedrijfsnaam</label>
		<div class="controls">{{ html()->text('businessname', Request::old('businessname'))->class('form-control') }}</div>
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
	{{ html()->form()->close() }}

@endsection