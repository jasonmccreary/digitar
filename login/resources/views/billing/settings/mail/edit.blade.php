@extends('master')

@section('content')
	
	<style>
		.form-group { margin-bottom: 0; }
		.form-label { margin-top: 0;}
		.heading .form-label { padding-left: 10px; margin: 0; }
		textarea { width: 100%; min-height: 400px; }
		input[type=text] { width: 100%; }
		input[type=file] { border: none; }
	</style>

	{{ html()->form()->open() }}
	{? $param = unserialize($layout->params); ?}

	<div class="row">
		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">Mail naam</label>
					{{ html()->text('name', $layout->name)->class('form-control')->maxlength('150') }}
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">Mail onderwerp</label>
					{{ html()->text('subject', $param['subject'])->class('form-control')->maxlength('150') }}
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Mail inhoud</label>
					<textarea id="code" name="code">{!! $layout->code !!}</textarea>
				</div>
			</div>
		</div>
	</div>
	
	<div class="form-group m-t-40">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>

	{{ html()->form()->close() }}

@endsection