@extends('master')

@section('content')
	<style>
		.form-group { margin-bottom: 0; }
		.form-label { margin-top: 0;}
		.heading .form-label { padding-left: 10px; margin: 0; }
		textarea { width: 100%; min-height: 400px; }
		input[type=text] { width: 100%; }
		input[type=file] { border: none; }

		.fileUpload {
			position: relative;
			overflow: hidden;
			margin: 5px 0 15px 0;
			float: left;
		}
		.fileUpload input {
			position: absolute;
			top: 0;
			right: 0;
			margin: 0;
			padding: 0;
			font-size: 20px;
			cursor: pointer;
			opacity: 0;
			filter: alpha(opacity=0);
		}
		.fileName {
			float: left;
			margin: 8px 0 0 10px;
		}
		.fixed-to-bottom {
			position: fixed;
		    bottom: 0;
		    background: #fff;
		    z-index: 9;
		    width: 100%;
		    left: 250px;
		    padding: 15px 15px 10px 50px;
		    border-top: 1px solid #eee;
		}
	</style>

	{!! Form::open(array('files'=>true)) !!}
	{? $param = unserialize($layout->params); ?}

	<div class="row">
		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-6 form-group">
					<label class="form-label">Layout naam</label>
					{!! Form::text('name', $layout->name, array('class'=>'form-control', 'maxlength'=>'150')) !!}
				</div>
				<div class="col-md-6 form-group">
					<label class="form-label">Briefpapier</label>
					<br />
					<div class="fileUpload btn btn-small btn-primary">
						<span>Briefpapier uploaden</span>
						{!! Form::file('file','',array('id'=>'','class'=>'upload')) !!}
					</div>
					<div class="fileName"><a href="javascript:;" onclick="window.open('{!! $param['background'] !!}', 'Layout bekijken', 'width=820,height=850,scrollbars=yes,toolbar=no,location=no'); return false" class="imagehover" data-img="{!! $param['background'] or '' !!}">{!! $param['background'] or '' !!}</a></div>
				</div>
			</div>
		</div>

		<div class="col-md-12 m-b-30">
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Layout code</label>
					<textarea id="code" name="code">{!! $layout->code !!}</textarea>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="row form-row">
				<div class="col-md-12 form-group">
					<label class="form-label">Layout css</label>
					<textarea id="code2" name="css">{!! $param['css'] or '' !!}</textarea>
				</div>
			</div>
		</div>
	</div>

	<div class="form-group m-t-40 fixed-to-bottom">
		<button type="submit" class="btn btn-success btn-cons">Opslaan</button>
	</div>

	{!! Form::close() !!}

@endsection