@extends('master')

@section('content')

	{{ html()->form()->open() }}
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<h3>{!! $user->name !!}</h3>
				{!! $user->address !!}<br/>
				{!! $user->zipcode !!} {!! $user->city !!}<br/>
				{!! $user->tell !!}<br/>
				{!! $user->email !!}
			</div>
		</div>
		<div class="col-md-8">
			<label>Beheerders</label>
			<div class="form-group">
				@foreach($mods as $mod)
					<div class="checkbox check-success 	">
						{{ html()->checkbox('mod[' . $mod->id . ']', Usermods::checked($mod->id, $user->id), '1')->id($mod->id) }}
			          <label for="{!! $mod->id !!}">{!! $mod->name !!}</label>
			        </div>					
				@endforeach
			</div>
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{{ html()->form()->close() }}

@endsection