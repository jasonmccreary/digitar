@extends('master')

@section('content')

	{{ html()->form()->open() }}
	<div class="form-group">
		<label class="form-label">Bedrijfsnaam</label>
		<div class="controls">{{ html()->text('businessname', $organization->name)->class('form-control') }}</div>
	</div>
	<div class="form-group">
		<label class="form-label">Adres</label>
		<div class="controls">{{ html()->text('address', $organization->address)->class('form-control') }}</div>
	</div>
	<div class="form-group">
		<label class="form-label">Postcode</label>
		<div class="controls">{{ html()->text('zipcode', $organization->zipcode)->class('form-control') }}</div>
	</div>
	<div class="form-group">
		<label class="form-label">Plaats</label>
		<div class="controls">{{ html()->text('city', $organization->city)->class('form-control') }}</div>
	</div>
	<div class="form-group">
		<label class="form-label">Telefoon nr</label>
		<div class="controls">{{ html()->text('tell', $organization->tell)->class('form-control') }}</div>
	</div>
	<div class="form-group">
		<label class="form-label">E-mail</label>
		<div class="controls">{{ html()->text('email', $organization->email)->class('form-control') }}</div>
	</div>
	<div class="form-group">
		<label class="form-label">Website</label>
		<div class="controls">{{ html()->text('website', $organization->website)->class('form-control') }}</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{{ html()->form()->close() }}

@endsection