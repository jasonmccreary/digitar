@extends('master')

@section('content')

	{{ Form::open() }}
	<div class="form-group">
		<div class="form-group">
			<label class="form-label">Hoofd map</label>
			{{ Form::select('parent',$folders, false, array('style' => 'width:100%;', 'id' => 'folderSelect')) }}
		</div>
		<div class="input-group">
			<a data-color="rgb(0,144,217)" data-color-format="hex" id="cp4" class="input-group-addon success my-colorpicker-control" href="#" data-colorpicker-guid="8" style="background:#0090d9;">		  
				<span class="arrow"></span>
				<i class="fa fa-tint" style="padding:0px 4px;"></i>
			</a>
			{{ Form::text('mapname', NULL, array('class'=>'form-control', 'placeholder'=>'Map naam')) }}
		</div>
		{{ Form::hidden('color', '#0090d9', array('class'=>'form-control','id'=>'colorpickerdata')) }}
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Toevoegen</button>
	</div>
	{{ Form::close() }}

@endsection