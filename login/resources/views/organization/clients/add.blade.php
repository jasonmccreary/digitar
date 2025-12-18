@extends('master')

@section('content')

	{!! Form::open() !!}
	<div class="row">
		<div class="col-md-6">
			<h3>Gegevens</h3>
			<div class="form-group">
				<label class="form-label">Gebruikersnaam</label>
				<div class="controls">{!! Form::text('username', Request::old('username'), array('class'=>'form-control', 'maxlength'=>'24')) !!}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Volledige naam</label>
				<div class="controls">{!! Form::text('name', Request::old('name'), array('class'=>'form-control')) !!}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Adres</label>
				<div class="controls">{!! Form::text('address', Request::old('address'), array('class'=>'form-control')) !!}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Postcode</label>
				<div class="controls">{!! Form::text('zipcode', Request::old('zipcode'), array('class'=>'form-control')) !!}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Plaats</label>
				<div class="controls">{!! Form::text('city', Request::old('city'), array('class'=>'form-control')) !!}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Telefoon nr</label>
				<div class="controls">{!! Form::text('tell', Request::old('tell'), array('class'=>'form-control')) !!}</div>
			</div>
			<div class="form-group">
				<label class="form-label">E-mail</label>
				<div class="controls">{!! Form::text('email', Request::old('email'), array('class'=>'form-control')) !!}</div>
			</div>
			<div class="form-group">
				<label class="form-label">Website</label>
				<div class="controls">{!! Form::text('website', Request::old('website'), array('class'=>'form-control')) !!}</div>
			</div>
		</div>
		<div class="col-md-6">
			
			@if(User::checkBilling())
			<h3>Instellingen</h3>
			<div class="form-group">
				<div class="checkbox check-success 	">
					{!! Form::checkbox('billing', '1', false, array('id'=>'billing')) !!}
		          <label for="billing">Facturatie</label>
		        </div>
		    </div>
		    @endif

			<h3>Mappen</h3>
			<div class="form-group">
				@if(count($sfolders) > 0)
					@foreach($sfolders as $f)
						<div class="checkbox check-success 	">
							{!! Form::checkbox('fid['.$f->id.']', '1', false, array('id'=>$f->id,'class'=>'parentf')) !!}
				          <label for="{!! $f->id !!}">{!! $f->name !!}</label>
				        </div>	
				         @foreach(Folder::getSubfolders($f->id,true) as $sf)
							<div class="checkbox check-success" style="padding-left:20px;">
								{!! Form::checkbox('fid['.$sf->id.']', '1', false, array('id'=>$sf->id,'parent'=>$f->id,'class'=>'subf')) !!}
					          <label for="{!! $sf->id !!}">{!! $sf->name !!}</label>
					        </div>					
						@endforeach				
					@endforeach
				@else
					<p><i>Er zijn nog geen standaard mappen</i></p>
				@endif
			</div>
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{!! Form::close() !!}

@endsection