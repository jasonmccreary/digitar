@extends('master')

@section('content')

<!-- <form class="form-signin" role="form" method="POST">
	@if($errors->any())
	<div class="alert alert-danger">
		<a href="#" class="close" data-dismiss="alert">&times;</a>
		{!! implode('', $errors->all('<li class="error">:message</li>')) !!}
	</div>
	@endif

		<input type="text" name="username" placeholder="Gebruikersnaam" required="" autofocus=""><br/>

		<input type="password" name="password" placeholder="Wachtwoord" required="">

		<button type="submit" class="btn btn-primary "><i class="fa fa-unlock"></i></button>
	</p>
</form> -->

<div class="container">
  <div class="row login-container animated fadeInUp">  
        <div class="col-md-7 col-md-offset-2 tiles white no-padding">
		 <div class="p-t-30 p-l-40 p-r-40 p-b-20 xs-p-t-10 xs-p-l-10 xs-p-r-10 xs-p-b-10"> 
          <h2 class="normal">Inloggen</h2>
			{{-- @include('layouts.messages') --}}
        </div>
		<div class="tiles grey p-t-20 p-b-20 text-black">
			<form id="frm_login" class="animated fadeIn" role="form" method="POST">    
	            <div class="row form-row m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
	              <div class="col-md-6 col-sm-6 ">
	                <input name="username" id="username" type="text" class="form-control" placeholder="Gebruikersnaam" required="" autofocus="">
	              </div>
	              <div class="col-md-5 col-sm-5">
	                <input name="password" id="password" type="password" class="form-control" placeholder="Wachtwoord" required="">
	              </div>
	              <div class="col-md-1 col-sm-1">
	                <button type="submit" class="btn btn-primary "><i class="fa fa-unlock"></i></button>
	              </div>
	            </div>
			</form>
		</div>   
      </div>   
  </div>
</div>

@stop