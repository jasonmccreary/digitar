@extends('master')

@section('content')


	{{ html()->form()->open() }}
		<div class="form-group">
			<label class="form-label">Hoofd map</label>
			{{ html()->select('parent', $folders, false)->style('width:100%;')->id('folderSelect') }}
		</div>
		<div class="form-group">
			<div class="input-group">
				<a data-color="rgb(0,144,217)" data-color-format="hex" id="cp4" class="input-group-addon success my-colorpicker-control" href="#" data-colorpicker-guid="8" style="background:#0090d9;">		  
					<span class="arrow"></span>
					<i class="fa fa-tint" style="padding:0px 4px;"></i>
				</a>
				{{ html()->text('mapname')->class('form-control')->placeholder('Map naam') }}
			</div>
			{{ html()->hidden('color', '#0090d9')->class('form-control')->id('colorpickerdata') }}
		</div>
		<div class="form-group">
			<div class="checkbox check-success 	">
				{{ html()->checkbox('geboekt', false, 1)->id('geboekt') }}
	          <label for="geboekt">Geboekt</label>
	        </div>
	    </div>
		<div class="form-group">
			<button type="submit" class="btn btn-success btn-cons">Toevoegen</button>
		</div>
	{{ html()->form()->close() }}

@endsection