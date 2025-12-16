<div class="clearfix"></div>
<div class="content">
  	<div class="page-title">
    	<h3>Zoeken naar: {{ Input::get('search') }}</h3>
  	</div>

  	<div class="row-fluid">
    	<div class="span12">
      		<div class="grid simple ">
        		<div class="grid-body ">

					<table class="table table-hover table-condensed" id="cleartable" data-sort="3" data-sort-order="desc">
					  	<thead>
					    	<tr>
					          	<th style="width:4%"></th>
					          	<th style="width:28%">Document</th>
					          	<th style="width:10%" data-hide="phone,tablet">Door</th>
					          	<th style="width:10%">Datum</th>
					    	</tr>
					  	</thead>
					  	<tbody>

					@foreach($files as $file)
						<?php $oFolder = $file->folder()->first(); ?>
						@if($file->fid == 0 || (is_object($oFolder) && $oFolder->rights()->where('uid','=',Auth::user()->id)->count() > 0))
							<tr>
						        <td>
						        	<a class="btn btn-info btn-mini" href="/user/viewfile/{{ $file->id }}" data-toggle="modal" data-target="#myModal{{ $file->id }}"> Bekijk </a>
						        	<div class="modal fade" id="myModal{{ $file->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									    <div class="modal-dialog form-block" style="width:95%;">
									        <div class="modal-content" style="border-radius:0;">
									            <div class="modal-body" style="background:none;padding:0;">
									            </div>
									            <div style="clear:both;"></div>
									        </div>
									    </div>
									</div>
						        </td>
						      	<td class="v-align-middle">{{ $file->name }}</td>
						      	<td><span class="muted">{{ User::getUserName($file->uid) }}</span></td>
						      	<td class="v-align-middle"><span style="display:none;">{{ strtotime($file->date) }}</span>{{ nice_date($file->date) }}</td>
					    	</tr>
					    @endif
					@endforeach
						</tbody>
					</table>

				</div>
    		</div>
		</div>
	</div>
</div>