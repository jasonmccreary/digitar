@extends('master')

@section('content')

<div class="container" style="height:auto;">
  	<div class="row login-container animated fadeInUp">
        <div class="col-md-7 col-md-offset-2 tiles white no-padding">
		 	<div class="p-t-30 p-b-20 xs-p-t-10 xs-p-b-10">
		 		<div class="row form-row m-l-10 m-r-10 xs-m-l-10 xs-m-r-10" style="text-align:center;">
	              	<div class="col-md-6 col-sm-6 ">
          				<h2 class="normal">Inloggen als</h2>
          			</div>
          			<div class="col-md-6 col-sm-6 " style="border-left: 1px solid #ddd;">
          				<h2 class="normal"><a href="/admin/users">
          					<i class="fa fa-home" style="margin-right:20px;"></i>Beheer
          				</a></h2>
          			</div>
          		</div>
				@include('layouts.messages')
        	</div>
			<div class="tiles grey p-t-20 p-b-20 text-black">
				<form id="frm_login" class="animated fadeIn" role="form" method="POST">
	            	<div class="row form-row m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
	              		<div class="col-md-11 col-sm-11 ">
	                		<input name="user" id="user" type="text" class="form-control" placeholder="klant naam" required="" autofocus="" autocomplete="off" >
	              		</div>
	              		<div class="col-md-1 col-sm-1">
	                		<button type="submit" class="btn btn-primary "><i class="fa fa-search"></i></button>
	              		</div>
	            	</div>
				</form>
			</div>
		</div>
    </div>
</div>

<div id="showusers"></div>



{!! HTML::script('assets/plugins/jquery-1.8.3.min.js') !!}
<script type="text/javascript">
	$( document ).ready(function() {
		var $selected = 0;
		var totalCount  = 0;

		$('#user').keyup(function(e) {
			e.preventDefault();

			if (e.which == 13) {
				if ($selected == 0) { $selected++; }
				window.location = $('#showusers').find('a[tabindex='+ $selected +']').attr('href');
				return;
			}

			// 40 down - 38 up
			if (e.which == 40 || e.which == 39 || e.which == 38 || e.which == 37) {

				$('#showusers').find('a[tabindex='+ $selected +']').removeClass('active');

				if (e.which == 40 && $selected < $totalCount) { $selected++; }
				else if (e.which == 38 && $selected > 1) { $selected--; }

				$('#showusers').find('a[tabindex='+ $selected +']').addClass('active');

			}else{

				var jqxhr = $.post( "supersearch", { search: $(this).val() }, function(data) {

				  $('#showusers').html(data);
				  $selected = 0;
				  $totalCount = $('#showusers a').length;

				})
				.done(function() {

				    $('#showusers').slideDown('fast');

				})
				.fail(function() {

				    $('#showusers').html('<h2><center>Er is een fout opgetreden!</center></h2>');

				});
			}
			$(this).val($(this).val());

		});
	});
</script>

@stop