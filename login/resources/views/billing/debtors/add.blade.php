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
						{{ html()->text('debnumber', strlen(Request::old('debnumber')) > 0 ? Request::old('debnumber') : "D" . str_pad(Debtors::newDebNumber(), 5, "0", STR_PAD_LEFT))->class('form-control') }}
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">

			<h3 class="semi-bold">Debiteurgegevens</h3>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Bedrijfsnaam</label>
					{{ html()->text('name', Request::old('name'))->class('form-control') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Adres</label>
					{{ html()->text('address', Request::old('address'))->class('form-control') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-4 form-group">
					<label class="form-label">Postcode</label>
					{{ html()->text('zipcode', Request::old('zipcode'))->class('form-control') }}
				</div>
				<div class="col-md-8 form-group">
					<label class="form-label">Plaats</label>
					{{ html()->text('city', Request::old('city'))->class('form-control') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Land</label>
					{{ html()->text('country', strlen(Request::old('country')) > 0 ? Request::old('country') : 'NL')->class('form-control') }}
				</div>
			</div>

			<h3 class="m-t-60 semi-bold">Contactgegevens</h3>
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Contact persoon</label>
					{{ html()->text('contact', Request::old('contact'))->class('form-control') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">Telefoon nummer</label>
					{{ html()->text('phone', Request::old('phone'))->class('form-control') }}
				</div>
				<div class="col-md-6 form-group">
					<label class="form-label">Mobiel nummer</label>
					{{ html()->text('mobile', Request::old('mobile'))->class('form-control') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">E-mail</label>
					{{ html()->text('email', Request::old('email'))->class('form-control') }}
				</div>
				<div class="col-md-6 form-group">
					<label class="form-label">Website</label>
					{{ html()->text('website', Request::old('website'))->class('form-control') }}
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<h3>&nbsp;</h3>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">KvK nummer</label>
					{{ html()->text('kvk', Request::old('kvk'))->class('form-control') }}
				</div>
				<div class="col-md-6 form-group">
					<label class="form-label">BTW nummer</label>
					{{ html()->text('btw', Request::old('btw'))->class('form-control') }}
				</div>
			</div>
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">Betalingstermijn</label>
					<span class="help">in dagen</span>
					{{ html()->text('payterm', strlen(Request::old('payterm')) > 0 ? Request::old('payterm') : 30)->class('form-control') }}
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{{ html()->form()->close() }}

@endsection