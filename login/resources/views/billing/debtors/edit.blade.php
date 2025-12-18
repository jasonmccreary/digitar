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
						{!! Form::text('debnumber', (strlen(Request::old('debnumber')) > 0 ? Request::old('debnumber') : $d->debnumber), array('class' => 'form-control')) !!}
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">

			<h3 class="semi-bold">Debiteurgegevens</h3>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Bedrijfsnaam</label>
					{!! Form::text('name', (strlen(Request::old('name')) > 0 ? Request::old('name') : $d->name), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Adres</label>
					{!! Form::text('address', (strlen(Request::old('address')) > 0 ? Request::old('address') : $d->address), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-4 form-group">
					<label class="form-label">Postcode</label>
					{!! Form::text('zipcode', (strlen(Request::old('zipcode')) > 0 ? Request::old('zipcode') : $d->zipcode), array('class' => 'form-control')) !!}
				</div>
				<div class="col-md-8 form-group">
					<label class="form-label">Plaats</label>
					{!! Form::text('city', (strlen(Request::old('city')) > 0 ? Request::old('city') : $d->city), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Land</label>
					{!! Form::text('country', (strlen(Request::old('country')) > 0 ? Request::old('country') : $d->country), array('class' => 'form-control')) !!}
				</div>
			</div>

			<h3 class="m-t-60 semi-bold">Contactgegevens</h3>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Contact persoon</label>
					{!! Form::text('contact', (strlen(Request::old('contact')) > 0 ? Request::old('contact') : $d->contact), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">Telefoon nummer</label>
					{!! Form::text('phone', (strlen(Request::old('phone')) > 0 ? Request::old('phone') : $d->phone), array('class' => 'form-control')) !!}
				</div>
				<div class="col-md-6 form-group">
					<label class="form-label">Mobiel nummer</label>
					{!! Form::text('mobile', (strlen(Request::old('mobile')) > 0 ? Request::old('mobile') : $d->mobile), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">E-mail</label>
					{!! Form::text('email', (strlen(Request::old('email')) > 0 ? Request::old('email') : $d->email), array('class' => 'form-control')) !!}
				</div>
				<div class="col-md-6 form-group">
					<label class="form-label">Website</label>
					{!! Form::text('website', (strlen(Request::old('website')) > 0 ? Request::old('website') : $d->website), array('class' => 'form-control')) !!}
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<h3>&nbsp;</h3>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">KvK nummer</label>
					{!! Form::text('kvk', (strlen(Request::old('kvk')) > 0 ? Request::old('kvk') : $d->kvknr), array('class' => 'form-control')) !!}
				</div>
				<div class="col-md-6 form-group">
					<label class="form-label">BTW nummer</label>
					{!! Form::text('btw', (strlen(Request::old('btw')) > 0 ? Request::old('btw') : $d->btwnr), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">Betalingstermijn</label>
					<span class="help">in dagen</span>
					{!! Form::text('payterm', (strlen(Request::old('payterm')) > 0 ? Request::old('payterm') : $d->payterm), array('class' => 'form-control')) !!}
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{!! Form::close() !!}

@endsection