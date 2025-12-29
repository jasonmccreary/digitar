@extends('master')

@section('content')

	<form method="post" action="/user/sendmail">
		@csrf

		<div class="row toolButtons" style="bottom:0 !important;">
			<div class="pull-right" style="padding-right:15px;">
				<button type="submit" name="sendmail" value="true" class="btn btn-success btn-cons">Versturen</button>
				<button type="submit" name="cancel" value="true" class="btn btn-danger btn-cons">Annuleren</button>
			</div>
		</div>
		<div class="row-fluid ">
			<h2>Bestanden versturen </h2>
			<div class="row">
				<div class="form-group col-md-12">
					<label class="form-label">Afzender</label>
					<span class="help">De mail wordt verzonden vanaf dit email adres</span>
					<span class="help"></span>
					<div class="controls">
						<input type="text" class="form-control " value="{!! User::getUserUsername(Auth::user()->cid) !!}@digitar.nu" readonly="true">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-12">
					<label class="form-label">Ontvanger</label>
					<span class="help">Geef het email adres op van de persoon waar de documenten heen moeten</span>
					<span class="help"></span>
					<div class="controls">
						<input type="text" name="to" class="form-control " value="{!! Request::old('to') !!}">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-12">
					<label class="form-label">Onderwerp</label>
					<span class="help">Geef het onderwerp van de mail op bijv. "Factuur van T-mobile"</span>
					<div class="controls">
						<input type="text" name="subject" class="form-control " value="{!! Request::old('subject') !!}">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-12">
					<label class="form-label">Bericht</label>
					<span class="help">Een begeleidend bericht aan de ontvanger</span>
					<div class="controls">
						<textarea id="text-editor" name="message" class="form-control" rows="15" style="font-family:arial,verdana,san-serif;">{!! Request::old('message') !!}</textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-12">
					<label class="form-label">Bijlagen</label>
					<span class="help">De bestanden welke worden bijgevoegd aan de mail</span>
					<div class="clearfix"></div>
					@foreach($files as $fileid => $filename)
						<input type="hidden" name="files[{!! $fileid !!}]" value="{!! $filename !!}" />
						<div class="pull-left" style="padding:10px 20px 0 0;">
							<div class="checkbox check-info">
								<input type="checkbox" value="{!! $filename !!}" name="fileid[{!! $fileid !!}]" id="checkbox{!! $fileid !!}" checked="true">
								<label for="checkbox{!! $fileid !!}">{!! $filename !!}</label>
							</div>
						</div>
					@endforeach
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</form>

@endsection
