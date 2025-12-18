@extends('master')

@section('content')

	{!! Form::open() !!}
	<div class="form-group">
		<label class="form-label">Bedrijfsnaam</label>
		<div class="controls">{!! Form::text('businessname', $organization->name, array('class'=>'form-control')) !!}</div>
	</div>
	<div class="form-group">
		<label class="form-label">Adres</label>
		<div class="controls">{!! Form::text('address', $organization->address, array('class'=>'form-control')) !!}</div>
	</div>
	<div class="form-group">
		<label class="form-label">Postcode</label>
		<div class="controls">{!! Form::text('zipcode', $organization->zipcode, array('class'=>'form-control')) !!}</div>
	</div>
	<div class="form-group">
		<label class="form-label">Plaats</label>
		<div class="controls">{!! Form::text('city', $organization->city, array('class'=>'form-control')) !!}</div>
	</div>
	<div class="form-group">
		<label class="form-label">Telefoon nr</label>
		<div class="controls">{!! Form::text('tell', $organization->tell, array('class'=>'form-control')) !!}</div>
	</div>
	<div class="form-group">
		<label class="form-label">E-mail</label>
		<div class="controls">{!! Form::text('email', $organization->email, array('class'=>'form-control')) !!}</div>
	</div>
	<div class="form-group">
		<label class="form-label">Website</label>
		<div class="controls">{!! Form::text('website', $organization->website, array('class'=>'form-control')) !!}</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{!! Form::close() !!}

@endsection