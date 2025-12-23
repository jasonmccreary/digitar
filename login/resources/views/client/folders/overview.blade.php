@extends('master')

@section('content')

	<table class="table table-striped table-flip-scroll cf">
		<thead>
			<tr>
				<th>Naam</th>
				<th>Kleur</th>
				<th width="100px"></th>
			</tr>
		</thead>
		<tbody>
			@foreach($folders as $f)
				<?php
          			$subfolders = App\Models\Folder::getSubfolders($f->id,$f->cfid);
        		?>
				<tr>
					<td style="vertical-align: middle;">{!! $f->name !!}</td>
					<td><div style="border-radius:3px;background:#1B1E24;padding:10px;float:left;">
						<div style="width:14px;height:14px;border-radius:50%;border:3px solid {!! $f->color !!};"></div>
					</div></td>
					<td style="vertical-align: middle;">
						@if($f->uid == Auth::user()->id)
							<a href="/client/folder/edit/{!! $f->id !!}" class="btn btn-white btn-xs btn-mini" title="Bewerken"><i class="fa fa-pencil"></i></a>
							<a href="/client/folder/delete/{!! $f->id !!}" class="btn btn-white btn-xs btn-mini" title="Verwijderen"><i class="fa fa-trash-o"></i></a>
						@endif
					</td>
				</tr>
				@foreach($subfolders as $folder)
					<tr>
						<td style="vertical-align: middle;"><span style="padding:0px 10px 0px 20px;"><i class="fa fa-angle-right"></i></span>{!! $folder->name !!}</td>
						<td><div style="border-radius:3px;background:#1B1E24;padding:10px;float:left;">
							<div style="width:14px;height:14px;border-radius:50%;border:3px solid {!! $folder->color !!};"></div>
						</div></td>
						<td style="vertical-align: middle;">
							@if($folder->uid == Auth::user()->id)
								<a href="/client/folder/edit/{!! $folder->id !!}" class="btn btn-white btn-xs btn-mini" title="Bewerken"><i class="fa fa-pencil"></i></a>
								<a href="/client/folder/delete/{!! $folder->id !!}" class="btn btn-white btn-xs btn-mini" title="Verwijderen"><i class="fa fa-trash-o"></i></a>
							@endif
						</td>
					</tr>
				@endforeach
			@endforeach
		</tbody>
	</table>

@endsection
