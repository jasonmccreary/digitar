@extends('master')

@section('content')

	<style>
		h3 { margin-bottom: 55px; border-bottom: 1px solid rgba(0, 0, 0, .0); }
		.form-group { margin-bottom: 0; }
		.form-label { margin-top: 0;}
		.heading .form-label { padding-left: 10px; margin: 0; }
		.invoicerow-placeholder { height: 47px; }
		textarea { width: 100%; min-height: 400px; }
		input[type=text] { width: 100%; }
	</style>
	{!! Form::open() !!}


	<?php 
		$code = ''; $name = '';
		$layout = Layouts::where('cid','=',Auth::user()->cid)->where('type','=','1');
		if ($layout->count() > 0) {
			$code = $layout->first()->code;
			$name = $layout->first()->name;
		}
	?>
	<div class="row">
		<div class="col-md-12">
			<h3 class="semi-bold">Factuur</h3>
		</div>

		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Mail onderwerp</label>
					<input type="text" name="newInvoiceName" value="{!! $name !!}" />
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


	<?php 
		$code = ''; $name = '';
		$layout = Layouts::where('cid','=',Auth::user()->cid)->where('type','=','3');
		if ($layout->count() > 0) {
			$code = $layout->first()->code;
			$name = $layout->first()->name;
		}
	?>
	<div class="row">
		<div class="col-md-12">
			<h3 class="semi-bold">Herrinering</h3>
		</div>

		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Mail onderwerp</label>
					<input type="text" name="reminderInvoiceName" value="{!! $name !!}" />
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Mail inhoud</label>
					<textarea id="code2" name="reminderInvoice">{!! $code !!}</textarea>
				</div>
			</div>
		</div>
	</div>
	
	<div class="form-group m-t-40">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>

	{!! Form::close() !!}

@endsection