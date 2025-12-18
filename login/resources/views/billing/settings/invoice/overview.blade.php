@extends('master')

@section('content')

	<div class="pull-right">
		<a href="/billing/settings/invoice/add" class="btn btn-success">Nieuwe factuur layout</a>
	</div>	
	
	{? $layouts = Layouts::getInvoice(); ?}

	@if($layouts->count() > 0) 
		<div class="row">
		@foreach($layouts->get() as $lay)
			{? $param = unserialize($lay->params) ?}
			<div class="col-md-2">
				<div class="grid simple horizontal green">
					<div class="grid-title no-border text-center">
						<h4>{!! $lay->name !!}</h4>
						<div class="pull-right">
							<div class="btn-group"> 
								<a class="btn btn-white btn-mini dropdown-toggle" data-toggle="dropdown" href="#"> <span class="no-margin caret"></span> </a>
								<ul class="dropdown-menu">
									<li><a href="/billing/settings/invoice/edit/{!! $lay->id !!}"><i class="fa fa-pencil"></i> Bewerken</a></li>
									<li><a href="/billing/settings/invoice/delete/{!! $lay->id !!}" class="red"><i class="fa fa-trash-o"></i> Verwijderen</a></li>
								</ul>
							</div>
						</div>
					</div>
					@if(isset($param['background']))
					<div class="grid-body no-border text-center">
						<img src="{!! $param['background'] !!}" style="max-width:100%;max-height:200px;" alt="{!! $param['background'] !!}">
					</div>
					@endif
				</div>
			</div>
		@endforeach
		</div>
	@else
		<center><h2 style="margin:0;">Geen Layouts aanwezig</h2></center>
	@endif

@endsection