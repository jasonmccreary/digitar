<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
			@if(Input::has('message'))
				{!! Input::get('message') !!}
			@endif
		</div>
	</body>
</html>