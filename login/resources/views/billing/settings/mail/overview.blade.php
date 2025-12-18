@extends('master')

@section('content')

	<div class="pull-right">
		<a href="/billing/settings/mail/add" class="btn btn-success">Nieuwe mail layout</a>
	</div>	
	
	{? $layouts = Layouts::getMail(); ?}

	@if($layouts->count() > 0) 
		<div class="row">
		@foreach($layouts->get() as $lay)
			{? $param = unserialize($lay->params) ?}
			<div class="col-md-2">
				<div class="grid simple horizontal green">
					<div class="grid-title no-border text-center">
						<h4>{{ $lay->name }}</h4>
						<div class="pull-right">
							<div class="btn-group"> 
								<a class="btn btn-white btn-mini dropdown-toggle" data-toggle="dropdown" href="#"> <span class="no-margin caret"></span> </a>
								<ul class="dropdown-menu">
									<li><a href="/billing/settings/mail/edit/{{ $lay->id }}"><i class="fa fa-pencil"></i> Bewerken</a></li>
									<li><a href="/billing/settings/mail/delete/{{ $lay->id }}" class="red"><i class="fa fa-trash-o"></i> Verwijderen</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="grid-body no-border text-center">
						<i class="fa fa-envelope-o fa fa-6x custom-icon-space" id="icon-resize"></i>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	@else
		<center><h2 style="margin:0;">Geen Layouts aanwezig</h2></center>
	@endif

@endsection