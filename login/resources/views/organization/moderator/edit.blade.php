@extends('master')

@section('content')

	{{ html()->form()->open() }}
	<div class="row">
		<div class="col-md-12">
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
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{{ html()->form()->close() }}

@endsection