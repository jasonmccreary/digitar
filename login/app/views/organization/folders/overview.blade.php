@extends('master')

@section('content')
	<style>
  	tr { cursor: s-resize; }
  	tr.subitem { cursor: auto; }
  	.ui-state-highlight {
        height: 1.5em;
        line-height: 1.2em;
        background: #333;
        display: table-row;
    }
  	</style>
	<table class="table" id="sort">
		<thead>
			<tr>
				<th>Naam</th>
				<th>Kleur</th>
				<th width="100px"></th>
			</tr>
		</thead>
			@foreach($standardfolders as $f)
				<tbody id="fid_{{ $f->id }}" style="border-top:1px solid #ddd;">
				<tr style="background:#fff;">
					<td style="vertical-align: middle;">{{ $f->name }}</td>
					<td><div style="border-radius:3px;background:#1B1E24;padding:10px;float:left;">
						<div style="width:14px;height:14px;border-radius:50%;border:3px solid {{ $f->color }};"></div>
					</div></td>
					<td style="vertical-align: middle;">
						<a href="/organization/folder/edit/{{ $f->id }}" class="btn btn-white btn-xs btn-mini" title="Bewerken"><i class="fa fa-pencil"></i></a>
						<a href="/organization/folder/delete/{{ $f->id }}" class="btn btn-white btn-xs btn-mini" title="Verwijderen"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
					<?php $subfolder = Folder::where('uid', '=', Auth::user()->id)->where('pid', '=', $f->id)->get(); ?>				
					@foreach($subfolder as $sf) 
						<tr class="subitem" style="background:#fff;">
							<td style="vertical-align: middle;"><span style="padding:0px 10px 0px 20px;"><i class="fa fa-angle-right"></i></span>{{ $sf->name }}</td>
							<td><div style="border-radius:3px;background:#1B1E24;padding:10px;float:left;">
								<div style="width:14px;height:14px;border-radius:50%;border:3px solid {{ $sf->color }};"></div>
							</div></td>
							<td style="vertical-align: middle;">
								<a href="/organization/folder/edit/{{ $sf->id }}" class="btn btn-white btn-xs btn-mini" title="Bewerken"><i class="fa fa-pencil"></i></a>
								<a href="/organization/folder/delete/{{ $sf->id }}" class="btn btn-white btn-xs btn-mini" title="Verwijderen"><i class="fa fa-trash-o"></i></a>
							</td>
						</tr>
					@endforeach
				
				</tbody>
			@endforeach
		
	</table>

@endsection