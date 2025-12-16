@extends('master')

@section('content')

	{{ Form::open() }}
	<div class="row">
		<div class="col-md-6">
			<h3>Gegevens</h3>
			<div class="form-group">
				<label class="form-label">Gebruikersnaam</label>
				<div class="controls">{{ Form::text('username', $user->username, array('class'=>'form-control')) }}</div>
			</div>
			<div class="row">
  				<div class="col-md-8">
					<div class="form-group">
						<label class="form-label">Wachtwoord</label>
						<div class="controls">{{ Form::text('password', Crypt::decrypt($user->password), array('class'=>'form-control')) }}</div>
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
				<div class="controls">{{ Form::text('name', $user->name, array('class'=>'form-control')) }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Adres</label>
				<div class="controls">{{ Form::text('address', $user->address, array('class'=>'form-control')) }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Postcode</label>
				<div class="controls">{{ Form::text('zipcode', $user->zipcode, array('class'=>'form-control')) }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Plaats</label>
				<div class="controls">{{ Form::text('city', $user->city, array('class'=>'form-control')) }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Telefoon nr</label>
				<div class="controls">{{ Form::text('tell', $user->tell, array('class'=>'form-control')) }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">E-mail</label>
				<div class="controls">{{ Form::text('email', $user->email, array('class'=>'form-control')) }}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Website</label>
				<div class="controls">{{ Form::text('website', $user->website, array('class'=>'form-control')) }}</div>
			</div>
		</div>
		<div class="col-md-6">

			@if(User::checkBilling())
			<h3>Instellingen</h3>
			<div class="form-group">
				<div class="checkbox check-success 	">
					{{ Form::checkbox('billing', '1', $user->billing, array('id'=>'billing')) }}
		          <label for="billing">Facturatie</label>
		        </div>
		    </div>
		    @endif

			<h3>Mappen</h3>
			<div class="form-group">
				<?php $folders = new Folder(); ?>
				@foreach($sfolders as $f)
					<div class="checkbox check-success 	">
						{{ Form::checkbox('fid['.$f->id.']', '1', $folders->checked($f->id,$user->id), array('id'=>$f->id,'class'=>'parentf')) }}
			          <label for="{{ $f->id }}">{{ $f->name }}</label>
			        </div>
			        @foreach(Folder::getSubfolders($f->id,true) as $sf)
						<div class="checkbox check-success" style="padding-left:20px;">
							{{ Form::checkbox('fid['.$sf->id.']', '1', $folders->checked($sf->id,$user->id), array('id'=>$sf->id,'parent'=>$f->id,'class'=>'subf')) }}
				          <label for="{{ $sf->id }}">{{ $sf->name }}</label>
				        </div>
					@endforeach
				@endforeach
			</div>
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{{ Form::close() }}

@endsection