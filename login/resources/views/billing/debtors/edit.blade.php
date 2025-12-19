@extends('master')

@section('content')
	
	<style>
	.form-group { margin-bottom: 0; }
	h3 { margin-bottom: 15px; border-bottom: 1px solid rgba(0, 0, 0, .0); }
	.form-label { margin-top: 0;}
	</style>

	{{ html()->form()->open() }}
	<div class="row">
		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<div class="pull-left">
						<label class="form-label" style="line-height:37px;margin-right:15px;">Debiteur nummer</label>
					</div>
					<div class="pull-left">
						{{ html()->text('debnumber', strlen(Request::old('debnumber')) > 0 ? Request::old('debnumber') : $d->debnumber)->class('form-control') }}
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">

			<h3 class="semi-bold">Debiteurgegevens</h3>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Bedrijfsnaam</label>
					{{ html()->text('name', strlen(Request::old('name')) > 0 ? Request::old('name') : $d->name)->class('form-control') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Adres</label>
					{{ html()->text('address', strlen(Request::old('address')) > 0 ? Request::old('address') : $d->address)->class('form-control') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-4 form-group">
					<label class="form-label">Postcode</label>
					{{ html()->text('zipcode', strlen(Request::old('zipcode')) > 0 ? Request::old('zipcode') : $d->zipcode)->class('form-control') }}
				</div>
				<div class="col-md-8 form-group">
					<label class="form-label">Plaats</label>
					{{ html()->text('city', strlen(Request::old('city')) > 0 ? Request::old('city') : $d->city)->class('form-control') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Land</label>
					{{ html()->text('country', strlen(Request::old('country')) > 0 ? Request::old('country') : $d->country)->class('form-control') }}
				</div>
			</div>

			<h3 class="m-t-60 semi-bold">Contactgegevens</h3>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Contact persoon</label>
					{{ html()->text('contact', strlen(Request::old('contact')) > 0 ? Request::old('contact') : $d->contact)->class('form-control') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">Telefoon nummer</label>
					{{ html()->text('phone', strlen(Request::old('phone')) > 0 ? Request::old('phone') : $d->phone)->class('form-control') }}
				</div>
				<div class="col-md-6 form-group">
					<label class="form-label">Mobiel nummer</label>
					{{ html()->text('mobile', strlen(Request::old('mobile')) > 0 ? Request::old('mobile') : $d->mobile)->class('form-control') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">E-mail</label>
					{{ html()->text('email', strlen(Request::old('email')) > 0 ? Request::old('email') : $d->email)->class('form-control') }}
				</div>
				<div class="col-md-6 form-group">
					<label class="form-label">Website</label>
					{{ html()->text('website', strlen(Request::old('website')) > 0 ? Request::old('website') : $d->website)->class('form-control') }}
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<h3>&nbsp;</h3>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">KvK nummer</label>
					{{ html()->text('kvk', strlen(Request::old('kvk')) > 0 ? Request::old('kvk') : $d->kvknr)->class('form-control') }}
				</div>
				<div class="col-md-6 form-group">
					<label class="form-label">BTW nummer</label>
					{{ html()->text('btw', strlen(Request::old('btw')) > 0 ? Request::old('btw') : $d->btwnr)->class('form-control') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">Betalingstermijn</label>
					<span class="help">in dagen</span>
					{{ html()->text('payterm', strlen(Request::old('payterm')) > 0 ? Request::old('payterm') : $d->payterm)->class('form-control') }}
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{{ html()->form()->close() }}

@endsection