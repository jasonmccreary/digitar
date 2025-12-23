@extends('master', ['errorpage' => true])

@section('content')
	<style>
		* {
			text-align: center;
		}
		h1 {
			color: #fff;
			font-size: 200px;
			margin-top: 200px;
			font-family: 'proxima_nova_bold';
			line-height: 200px;
		}
		p {
			color: #fff;
			font-size: 20px;
			font-family: 'proxima_nova_thin';
		}
		a {
			display: inline-block;
			width: 200px;
			padding: 5px 15px;
			background: #fff;
			font-family: 'proxima_nova';
			color: #1B1E24;
			margin-top: 50px;
		}
	</style>
	<h1><center>403</center></h1>
	<p>
		De pagina die je probeert te berijken is afgeschermd!
	</p>
	<p>
		<a href="/">Ga terug</a>
	</p>

@endsection
