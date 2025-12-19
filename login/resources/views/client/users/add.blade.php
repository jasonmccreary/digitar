@extends('master')

@section('content')

	{{ html()->form()->open() }}
	<div class="row">
		<div class="col-md-6">
			<h3>Gegevens</h3>
			<div class="form-group">
				<label class="form-label">Gebruikersnaam</label>
				<div class="controls">{{ html()->text('username', Request::old('username'))->class('form-control') }}</div>
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
		</div>
		<div class="col-md-6">
			<h3>Instellingen</h3>
			<div class="form-group">
				<div class="checkbox check-info">
					<input id="lookonly" name="lookonly" type="checkbox" value="1">
					<label for="lookonly">Alleen kijken</label>
				</div>
				<div class="checkbox check-info">
					<input id="onverwerkt" name="onverwerkt" type="checkbox" value="1" checked>
					<label for="onverwerkt">Onverwerkt</label>
				</div>

				@if(User::checkBilling())
				<div class="checkbox check-info">
					{{ html()->checkbox('billing', false, '1')->id('billing') }}
		          <label for="billing">Facturatie</label>
		        </div>
		        @endif

		    </div>
			<h3>Mappen</h3>
			<div class="form-group">
				<?php $folders = new App\Models\Folder(); ?>
				@foreach($sfolders as $f)
					<div class="checkbox check-success 	">
						{{ html()->checkbox('fid[' . $f->id . ']', false, '1')->id($f->id)->class('parentf') }}
			          <label for="{!! $f->id !!}">{!! $f->name !!}</label>
			        </div>
			        @foreach(Folder::getSubfolders($f->id) as $sf)
						<div class="checkbox check-success" style="padding-left:20px;">
							{{ html()->checkbox('fid[' . $sf->id . ']', false, '1')->id($sf->id)->attribute('parent', $f->id)->class('subf') }}
				          <label for="{!! $sf->id !!}">{!! $sf->name !!}</label>
				        </div>
					@endforeach
				@endforeach
			</div>
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{{ html()->form()->close() }}

@endsection
