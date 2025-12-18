<?php $numClients = count($clients); ?>


<div class="container">
  	<div class="row login-container" style="margin-top:0px;">  
        <div class="col-md-7 col-md-offset-2 tiles white no-padding">
			@if($numClients > 0)
			<table class="table table-hover" style="margin:0;">
				<tbody>
					<?php $ind = 1; ?>
					@foreach($clients as $c)
						@if($numClients > 1 && isset($c->oid) && isset($c->id))
							<?php $u = User::getFirstUser($c->oid,$c->id); if(!is_object($u)) {die();} ?>
							<tr>
								<td style="padding:0 !important;">
									<a href="/loginas/{!! $u->id !!}/{!! $u->password !!}" tabindex="{!! $ind !!}" style="display:block;color:#444;padding:15px 32px;font-size:1.6em;">
										<i class="fa fa-mail-forward" style="color:#aaa;margin-right:20px;"></i> {!! $c->name !!} <small style="color:#aaa;font-size:.6em;">{!! $u->name !!}</small>
									</a>
								</td>
							</tr>
							<?php $ind++; ?>
						@elseif(isset($c->oid) && isset($c->id))
							@foreach(User::getAllUsers($c->oid,$c->id, $username) as $u)
								<tr>
									<td style="padding:0 !important;">
										<a href="/loginas/{!! $u->id !!}/{!! $u->password !!}" tabindex="{!! $ind !!}" style="display:block;color:#444;padding:15px 32px;font-size:1.6em;">
											<i class="fa fa-mail-forward" style="color:#aaa;margin-right:20px;"></i> {!! $c->name !!} <small style="color:#aaa;font-size:.6em;">{!! $u->name !!}</small>
										</a>
									</td>
								</tr>
								<?php $ind++; ?>
							@endforeach
						@endif
					@endforeach
				</tbody>
			</table>
			@else

			<h2><center>Geen resultaten!</center></h2>

			@endif
		</div>
	</div>
</div>