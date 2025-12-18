@extends('master')

@section('content')

	{!! Form::open() !!}
	<div class="row">
		<div class="col-md-6">
			<h3>Gegevens</h3>
			<div class="form-group">
				<label class="form-label">Gebruikersnaam</label>
				<div class="controls">{!! Form::text('username', Input::old('username'), array('class'=>'form-control')) !!}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Volledige naam</label>
				<div class="controls">{!! Form::text('name', Input::old('name'), array('class'=>'form-control')) !!}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Adres</label>
				<div class="controls">{!! Form::text('address', Input::old('address'), array('class'=>'form-control')) !!}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Postcode</label>
				<div class="controls">{!! Form::text('zipcode', Input::old('zipcode'), array('class'=>'form-control')) !!}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Plaats</label>
				<div class="controls">{!! Form::text('city', Input::old('city'), array('class'=>'form-control')) !!}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Telefoon nr</label>
				<div class="controls">{!! Form::text('tell', Input::old('tell'), array('class'=>'form-control')) !!}</div>
			</div>
			<div class="form-group">
				<label class="form-label">E-mail</label>
				<div class="controls">{!! Form::text('email', Input::old('email'), array('class'=>'form-control')) !!}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Website</label>
				<div class="controls">{!! Form::text('website', Input::old('website'), array('class'=>'form-control')) !!}</div>
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
					{!! Form::checkbox('billing', '1', false, array('id'=>'billing')) !!}
		          <label for="billing">Facturatie</label>
		        </div>
		        @endif
		        
		    </div>
			<h3>Mappen</h3>
			<div class="form-group">
				<?php $folders = new Folder(); ?>
				@foreach($sfolders as $f)
					<div class="checkbox check-success 	">
						{!! Form::checkbox('fid['.$f->id.']', '1', false, array('id'=>$f->id,'class'=>'parentf')) !!}
			          <label for="{!! $f->id !!}">{!! $f->name !!}</label>
			        </div>	
			        @foreach(Folder::getSubfolders($f->id) as $sf)
						<div class="checkbox check-success" style="padding-left:20px;">
							{!! Form::checkbox('fid['.$sf->id.']', '1', false, array('id'=>$sf->id,'parent'=>$f->id,'class'=>'subf')) !!}
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
	{!! Form::close() !!}

@endsection