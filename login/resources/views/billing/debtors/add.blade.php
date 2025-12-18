@extends('master')

@section('content')
	
	<style>
	.form-group { margin-bottom: 0; }
	h3 { margin-bottom: 15px; border-bottom: 1px solid rgba(0, 0, 0, .0); }
	.form-label { margin-top: 0;}
	</style>

	{!! Form::open() !!}
	<div class="row">
		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<div class="pull-left">
						<label class="form-label" style="line-height:37px;margin-right:15px;">Debiteur nummer</label>
					</div>
					<div class="pull-left">
						{!! Form::text('debnumber', (strlen(Input::old('debnumber')) > 0 ? Input::old('debnumber') : "D".str_pad(Debtors::newDebNumber(), 5, "0", STR_PAD_LEFT)), array('class' => 'form-control')) !!}
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">

			<h3 class="semi-bold">Debiteurgegevens</h3>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Bedrijfsnaam</label>
					{!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Adres</label>
					{!! Form::text('address', Input::old('address'), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-4 form-group">
					<label class="form-label">Postcode</label>
					{!! Form::text('zipcode', Input::old('zipcode'), array('class' => 'form-control')) !!}
				</div>
				<div class="col-md-8 form-group">
					<label class="form-label">Plaats</label>
					{!! Form::text('city', Input::old('city'), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Land</label>
					{!! Form::text('country', (strlen(Input::old('country')) > 0 ? Input::old('country') : 'NL'), array('class' => 'form-control')) !!}
				</div>
			</div>

			<h3 class="m-t-60 semi-bold">Contactgegevens</h3>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Contact persoon</label>
					{!! Form::text('contact', Input::old('contact'), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">Telefoon nummer</label>
					{!! Form::text('phone', Input::old('phone'), array('class' => 'form-control')) !!}
				</div>
				<div class="col-md-6 form-group">
					<label class="form-label">Mobiel nummer</label>
					{!! Form::text('mobile', Input::old('mobile'), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">E-mail</label>
					{!! Form::text('email', Input::old('email'), array('class' => 'form-control')) !!}
				</div>
				<div class="col-md-6 form-group">
					<label class="form-label">Website</label>
					{!! Form::text('website', Input::old('website'), array('class' => 'form-control')) !!}
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<h3>&nbsp;</h3>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">KvK nummer</label>
					{!! Form::text('kvk', Input::old('kvk'), array('class' => 'form-control')) !!}
				</div>
				<div class="col-md-6 form-group">
					<label class="form-label">BTW nummer</label>
					{!! Form::text('btw', Input::old('btw'), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">Betalingstermijn</label>
					<span class="help">in dagen</span>
					{!! Form::text('payterm', (strlen(Input::old('payterm')) > 0 ? Input::old('payterm') : 30), array('class' => 'form-control')) !!}
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{!! Form::close() !!}

@endsection