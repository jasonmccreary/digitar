@extends('master')

@section('content')

	<form method="post" action="/user/cloud/bulk">
		@csrf

	@if(!is_numeric($fid) && Auth::user()->lookonly == 0)
		<div class="row">
			<div class="pull-right" style="padding-right:15px;">
				<button type="submit" name="delete" value="true" class="btn btn-xs btn-mini btn-danger">Verwijderen</button>
			</div>
		</div>
		<br /><br />
		<div class="clearfix"></div>
	@endif

	<table class="table table-hover table-condensed" id="cloudtable">
      	<thead>
        	<tr>
	          	@if(!is_numeric($fid) && Auth::user()->lookonly == 0)
	          	<th style="width:1%"></th>
	          	@endif
	          	<th style="width:8%"></th>
	          	<th style="width:29%">Bestand</th>
	          	<th style="width:5%" data-hide="phone,tablet">Type</th>
	          	<th style="width:10%" data-hide="phone,tablet">Door</th>
	          	<th style="width:10%">Datum</th>
        	</tr>
      	</thead>
      	<tbody>

	@foreach($files as $file)
			<tr>
				@if(!is_numeric($fid) && Auth::user()->lookonly == 0)
		      	<td class="v-align-middle"><div class="checkbox check-default">
		          	<input type="checkbox" value="{!! $file->name !!}" name="fileid[{!! $file->id !!}]" id="checkbox{!! $file->id !!}">
		          	<label for="checkbox{!! $file->id !!}"></label>
		        </div></td>
		        @endif
		        <td>
		        	<a class="btn btn-info btn-mini" href="/user/download/file/{!! $file->id !!}/{!! str_replace(' ', '-', $file->name) !!}.{!! strtolower(getFileType($file->file)) !!}" target="_blank"> Download </a>
		        	<a class="btn btn-default btn-mini" href="/user/viewdetails/{!! $file->id !!}" data-toggle="modal" data-target="#myModal{!! $file->id !!}"> Details </a>
		        	<div class="modal fade" id="myModal{!! $file->id !!}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					    <div class="modal-dialog form-block">
					        <div class="modal-content" style="border-radius:0;">
					            <div class="modal-body" style="background:none;padding:0;">
					            </div>
					            <div style="clear:both;"></div>
					        </div>
					    </div>
					</div>
		        </td>
		      	<td class="v-align-middle">{!! $file->name !!}</td>
		      	<td>{!! getFileType($file->file) !!}</td>
		      	<td><span class="muted">{!! User::getUserName($file->uid) !!}</span>
		      	</td>
		      	<td class="v-align-middle"><span style="display:none;">{!! strtotime($file->date) !!}</span>{!! nice_date($file->date) !!}</td>
        	</tr>
	@endforeach
		</tbody>
	</table>

	</form>


@endsection
