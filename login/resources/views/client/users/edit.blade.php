@extends('master')

@section('content')

	{{ html()->form()->open() }}
	<div class="row">
		<div class="col-md-6">
			<h3>Gegevens</h3>
			<div class="form-group">
				<label class="form-label">Gebruikersnaam</label>
				<div class="controls">{{ html()->text('username', $user->username)->class('form-control') }}</div>
			</div>
			<div class="row">
  				<div class="col-md-8">
					<div class="form-group">
						<label class="form-label">Wachtwoord</label>
						<div class="controls">{{ html()->text('password', Crypt::decrypt($user->password))->class('form-control') }}</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="form-label">&nbsp;</label>
						<div class="controls"><button type="submit" class="btn btn-default form-control gennewuserpassword">Genereer</button></div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="form-label">Volledige naam</label>
				<div class="controls">{{ html()->text('name', $user->name)->class('form-control') }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Adres</label>
				<div class="controls">{{ html()->text('address', $user->address)->class('form-control') }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Postcode</label>
				<div class="controls">{{ html()->text('zipcode', $user->zipcode)->class('form-control') }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Plaats</label>
				<div class="controls">{{ html()->text('city', $user->city)->class('form-control') }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Telefoon nr</label>
				<div class="controls">{{ html()->text('tell', $user->tell)->class('form-control') }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">E-mail</label>
				<div class="controls">{{ html()->text('email', $user->email)->class('form-control') }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Website</label>
				<div class="controls">{{ html()->text('website', $user->website)->class('form-control') }}</div>
			</div>
		</div>
		<div class="col-md-6">
			<h3>Instellingen</h3>
			<div class="form-group">
				<div class="checkbox check-info">
					<input id="lookonly" @if($user->lookonly == 1) checked="checked" @endif name="lookonly" type="checkbox" value="1">
					<label for="lookonly">Alleen kijken</label>
				</div>
				<div class="checkbox check-info">
					<input id="onverwerkt" @if($user->onverwerkt == 1) checked="checked" @endif name="onverwerkt" type="checkbox" value="1">
					<label for="onverwerkt">Onverwerkt</label>
				</div>

				@if(User::checkBilling())
				<div class="checkbox check-info">
					{{ html()->checkbox('billing', $user->billing, '1')->id('billing') }}
		          <label for="billing">Facturatie</label>
		        </div>
		        @endif

	        </div>
			<h3>Mappen</h3>
			<div class="form-group">
				<?php $folders = new App\Models\Folder(); ?>
				@foreach($sfolders as $f)
					<div class="checkbox check-success">
						{{ html()->checkbox('fid[' . $f->id . ']', $folders->checked($f->id, $user->id), '1')->id($f->id)->class('parentf') }}
			          <label for="{!! $f->id !!}">{!! $f->name !!}</label>
			        </div>
			        @foreach(Folder::getSubFolders($f->id) as $sf)
						<div class="checkbox check-success" style="padding-left:20px;">
							{{ html()->checkbox('fid[' . $sf->id . ']', $folders->checked($sf->id, $user->id), '1')->id($sf->id)->attribute('parent', $f->id)->class('subf') }}
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
