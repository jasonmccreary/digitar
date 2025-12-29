@if(count($files) > 0 || is_numeric($fid))


	@if(!is_numeric($fid) && Auth::user()->lookonly == 0)
	<form method="post" action="/user/files/bulk">
		@csrf

		<div class="row toolButtons" data-count="0">
			<div class="pull-left hide-phone countSelected"></div>
			<div class="pull-right" style="padding-right:15px;">
				<button type="submit" name="split" value="true" class="btn btn-dark tip" title="Opsplitsen"><i class="fa fa-expand"></i></button>
				<button type="submit" name="combine" value="true" class="btn btn-dark tip" title="Samenvoegen"><i class="fa fa-compress"></i></button>
				@if(Session::has('highrank'))
					<button type="submit" name="booked" value="true" class="btn btn-primary tip hide-phone" title="Markeer als geboekt"><i class="fa fa-check"></i></button>
				@endif
				<button type="button" name="move" value="true" class="btn btn-primary tip" title="Verplaatsen" data-toggle="modal" data-target="#fileMove"><i class="fa fa-folder-open"></i></button>

				<button type="submit" name="sendmail" value="true" class="btn btn-primary tip" title="Versturen"><i class="fa fa-envelope"></i></button>
				<button type="submit" name="download" value="true" class="btn hide-phone btn-primary tip" title="Downloaden"><i class="fa fa-download"></i></button>
				<button type="submit" name="delete" value="true" class="btn btn-danger tip" title="Verwijderen"><i class="fa fa-trash"></i></button>
			</div>
		</div>
	@endif
	@if(is_numeric($fid) && Auth::user()->lookonly == 0)
	<form method="post" action="/user/files/bulk">
		@csrf

		<div class="row toolButtons" data-count="0">
			<div class="pull-left hide-phone countSelected"></div>
			<div class="pull-right" style="padding-right:15px;">
				@if(Session::has('highrank'))
					<button type="submit" name="booked" value="true" class="btn btn-primary tip" title="Markeer als geboekt"><i class="fa fa-check"></i></button>
				@endif
				<button type="button" name="move" value="true" class="btn btn-primary tip" title="Verplaatsen" data-toggle="modal" data-target="#fileMove"><i class="fa fa-folder-open"></i></button>
				<button type="submit" name="sendmail" value="true" class="btn btn-primary tip" title="Versturen"><i class="fa fa-envelope"></i></button>
				<button type="submit" name="download" value="true" class="btn btn-primary tip hide-phone" title="Downloaden"><i class="fa fa-download"></i></button>
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
								<?php $subfolders = App\Models\Folder::getSubfolders($folder->id); ?>
								<option value="{!! $folder->id !!}">{!! $folder->name !!}</option>
								@foreach($subfolders as $sf)
				                  <option value="{!! $sf->id !!}" > &nbsp;&nbsp; - {!! $sf->name !!}</option>
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

	@if(!is_numeric($fid) && Auth::user()->lookonly == 0)
	<table class="table table-hover table-condensed" id="filetable" data-sort="4" data-sort-order="desc">
	@else
	<table class="table table-hover table-condensed" id="filetable" data-sort="3" data-sort-order="desc">
	@endif
      	<thead>
        	<tr>
	          	@if(!is_numeric($fid) && Auth::user()->lookonly == 0)
	          	<th style="width:1%;" class="hide-phone"><div class="checkbox check-default">
	          		<input type="checkbox" id="selectAll" class="selectAll">
		          	<label for="selectAll"></label>
	          	</div></th>
	          	@endif
	          	<th style="width:4%"></th>
	          	<th style="width:28%">Document</th>
	          	<th style="width:10%" class="hidden-phone" data-hide="phone,tablet">Door</th>
	          	<th style="width:10%">Datum</th>
        	</tr>
      	</thead>
      	<tbody>

	@foreach($files as $file)
		@if(is_numeric($fid))
			<?php
				$fDetails = App\Models\Folder::where('id', '=', $file->fid)->first();
			?>
			@if(isset($fDetails->bookedcheck) && $fDetails->bookedcheck == 1 && $file->geboekt == 0 && Session::has('highrank'))
				<tr class="red checkableRow">
			@else
				<tr class="checkableRow">
			@endif
		@else
			<tr class="checkableRow">
		@endif
				@if(!is_numeric($fid) && Auth::user()->lookonly == 0)
		      	<td class="v-align-middle hide-phone"><div class="checkbox check-default">
		          	<input type="checkbox" value="{!! $file->name !!}" name="fileid[{!! $file->id !!}]" id="checkbox{!! $file->id !!}">
		          	<label for="checkbox{!! $file->id !!}"></label>
		        </div></td>
		        @endif
		        <td>
		        	@if(is_numeric($fid) && Auth::user()->lookonly == 0)
		        		<input type="checkbox" value="{!! $file->name !!}" name="fileid[{!! $file->id !!}]" style="display:none;">
		        	@endif
		        	<a class="btn btn-info btn-mini" href="/user/viewfile/{!! $file->id !!}" data-toggle="modal" data-target="#myModal{!! $file->id !!}"> Bekijk </a>
		        	<div class="modal" id="myModal{!! $file->id !!}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					    <div class="modal-dialog form-block" style="width:95%;">
					        <div class="modal-content" style="border-radius:0;">
					            <div class="modal-body" style="background:none;padding:0;">
					            </div>
					            <div style="clear:both;"></div>
					        </div>
					    </div>
					</div>
		        </td>
		      	<td class="v-align-middle">{!! $file->name !!}</td>
		      	<td class="hidden-phone"><span class="muted">{!! User::getUserName($file->uid) !!}</span></td>
		      	<td class="v-align-middle"><span style="display:none;">{!! strtotime($file->date) !!}</span>{!! nice_date($file->date) !!}</td>
        	</tr>
	@endforeach
		</tbody>
	</table>

	</form>

	@else

	<center><h1>Geen onverwerkte documenten <div class="smiley">:)</div></h1></center>

	@endif
