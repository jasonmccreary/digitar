@extends('master')

@section('content')

	<form method="post" action="/user/files/bulk">
	<div class="content">
	<div class="page-title">
		<h3 id="foldertitle" fid="{{ $fid }}">{{ $title }}</h3>
	</div>
	<div class="row-fluid">
	<div class="span12">
	<div class="grid simple">
	<div class="grid-body">

	@if(Auth::user()->lookonly == 0)
		<div class="row toolButtons" data-count="0">
			<div class="pull-left hide-phone countSelected" style="margin-left:300px;font-size:18px;font-weight:200;line-height:36px;"><span></span> bestanden geselecteerd</div>
			<div class="pull-right" style="padding-right:15px;">
				<button type="submit" name="split" value="true" class="btn btn-dark tip only-inbox" title="Opsplitsen"><i class="fa fa-expand"></i></button>
				<button type="submit" name="combine" value="true" class="btn btn-dark tip only-inbox" title="Samenvoegen"><i class="fa fa-compress"></i></button>
				@if(Session::has('highrank'))
					<button type="submit" name="booked" value="true" class="btn btn-primary tip hide-phone" title="Markeer als geboekt"><i class="fa fa-check"></i></button>
				@endif
				<button type="button" name="move" value="true" class="btn btn-primary tip only-inbox" title="Verplaatsen" data-toggle="modal" data-target="#fileMove"><i class="fa fa-folder-open"></i></button>

				<button type="submit" name="sendmail" value="true" class="btn btn-primary tip" title="Versturen"><i class="fa fa-envelope"></i></button>
				<button type="submit" name="download" value="true" class="btn hide-phone btn-primary tip" title="Downloaden"><i class="fa fa-download"></i></button>
				<button type="submit" name="delete" value="true" class="btn btn-danger tip only-inbox" title="Verwijderen"><i class="fa fa-trash"></i></button>
			</div>
		</div>
	@endif


	<div class="modal fade" id="fileMove" role="dialog" aria-labelledby="fileMove" aria-hidden="true">
	    <div class="modal-dialog form-block" style="width:500px;">
	        <div class="modal-content">
	            <div class="modal-body">

	            	<div class="form-group">
						<label class="form-label">Verplaats de geselecteerde bestanden naar de volgende map</label>
						<select id="folderSelect" style="width:100%;" name="folder">
							<option value="0" selected>Onverwerkt</option>
							@foreach($aFolders as $folder)
								<?php $subfolders = Folder::getSubfolders($folder->id); ?>
								<option value="{{ $folder->id }}">{{ $folder->name }}</option>
								@foreach($subfolders as $sf)
				                  <option value="{{ $sf->id }}" > &nbsp;&nbsp; - {{ $sf->name }}</option>
				                @endforeach
							@endforeach
						</select>
					</div>

					<div class="form-group">
						<div class="pull-left" style="margin-left: 10px;">
						@if(Auth::user()->lookonly == 0)
						  	<button type="submit" name="movefiles" value="true" class="btn btn-success btn-cons"><i class="icon-ok"></i> Opslaan</button>
						@endif
						</div>
						<div class="pull-right">
						  <button type="button" class="btn btn-danger btn-cons" data-dismiss="modal">Sluiten</button>
						</div>
					</div>

					<div style="clear:both;"></div>
	            </div>
	        </div>
	    </div>
	</div>

	<table class="table table-hover table-condensed fileTable" id="fileTable" url="{{ Request::path() }}">
		<thead>
        	<tr>
	          	<th style="width:4%"></th>
	          	<th style="width:28%">Document</th>
	          	<th style="width:10%" class="hidden-phone hide-phone" data-hide="phone,tablet">Door</th>
	          	<th style="width:10%">Datum</th>
        	</tr>
      	</thead>
      	<tbody>
      		{{-- Datatables ajax loading / public/assets/js/datatables.js --}}
      	</tbody>
	</table>



	<!-- Niet ingeboekte documenten -->

	@if(!is_numeric($fid) && count(FileController::getOngeboekt()) > 0 && Session::has('highrank'))


	</div>
	</div>
	</div>
	</div>
	</div>
	<div class="content" style="padding-top:20px;">
	<div class="page-title">
		<h3>Niet ingeboekte documenten</h3>
	</div>
	<div class="row-fluid">
	<div class="span12">
	<div class="grid simple">
	<div class="grid-body">

	<table class="table table-hover table-condensed fileTable" id="geboektTable">
		<thead>
        	<tr>
	          	<th style="width:4%"></th>
	          	<th style="width:28%">Document</th>
	          	<th style="width:10%" class="hidden-phone hide-phone" data-hide="phone,tablet">Door</th>
	          	<th style="width:10%">Datum</th>
        	</tr>
      	</thead>
      	<tbody>
      		{{-- Datatables ajax loading / public/assets/js/datatables.js --}}
      	</tbody>
	</table>	

	@endif

	</div>
	</div>
	</div>
	</div>
	</div>
	</form>


<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> 
    <div class="modal-dialog form-block" style="width:95%;"> 
        <div class="modal-content" style="border-radius:0;"> 
            <div class="modal-body" style="background:none;padding:0;"> 
            </div> 
            <div style="clear:both;"></div> 
        </div> 
    </div> 
</div>

@endsection