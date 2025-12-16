@extends('master')

@section('content')

	{{ Form::open() }}
	<div class="form-group">
		<div class="form-group">
			<label class="form-label">Hoofd map</label>
			{{ Form::select('parent',$folders, $folder->pid, array('style' => 'width:100%;', 'id' => 'folderSelect')) }}
		</div>
		<div class="input-group">
			<a data-color="{{ $folder->color }}" data-color-format="hex" id="cp4" class="input-group-addon success my-colorpicker-control" href="#" data-colorpicker-guid="8" style="background:{{ $folder->color }};">		  
				<span class="arrow" style="color:{{ $folder->color }};"></span>
				<i class="fa fa-tint" style="padding:0px 4px;"></i>
			</a>
			{{ Form::text('mapname', $folder->name, array('class'=>'form-control', 'placeholder'=>'Map naam')) }}
		</div>
		{{ Form::hidden('color', $folder->color, array('class'=>'form-control','id'=>'colorpickerdata')) }}
	</div>
	<div class="form-group">
		<div class="checkbox check-success 	">
			{{ Form::checkbox('geboekt', 1 , $folder->bookedcheck, array('id'=>'geboekt')) }}
          <label for="geboekt">Geboekt</label>
        </div>
    </div>
	<div class="form-group">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>
	{{ Form::close() }}

@endsection