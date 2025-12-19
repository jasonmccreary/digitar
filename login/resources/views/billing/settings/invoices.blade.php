@extends('master')

@section('content')

	<?php

	$code = ''; $name = '';

	$layout = App\Models\Layouts::where('cid','=',Auth::user()->cid)->where('type','=','2');
	if ($layout->count() > 0) {
		$code = $layout->first()->code;
		$name = $layout->first()->name;

		$params = $layout->first()->params;
		echo '<pre>';
		$params = unserialize($params);
		print_r($params['background']);
		echo '</pre>';
	}
	?>

	<style>
		h3 { margin-bottom: 55px; border-bottom: 1px solid rgba(0, 0, 0, .0); }
		.form-group { margin-bottom: 0; }
		.form-label { margin-top: 0;}
		.heading .form-label { padding-left: 10px; margin: 0; }
		textarea { width: 100%; min-height: 400px; }
		input[type=text] { width: 100%; }
		input[type=file] { border: none; }
	</style>
	{{ html()->form('POST', url()->current())->acceptsFiles()->open() }}

	<div class="row">
		<div class="col-md-12">
			<h3 class="semi-bold">Factuur layout</h3>
		</div>

		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Briefpapier</label>
					{{ html()->file('file', array('id' => '', 'class' => ''))->attributes('') }}
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Mail inhoud</label>
					<textarea id="code" name="newInvoice">{!! $code !!}</textarea>
				</div>
			</div>
		</div>
	</div>

	<div class="form-group m-t-40">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>

	{{ html()->form()->close() }}

@endsection
