<?php 

$i = Invoices::find($iid);
$d = Debtors::find($i->did);
$c = User::find(Auth::user()->cid);

$layout = Layouts::where('cid','=',Auth::user()->cid)->where('id','=',$lid);
if ($layout->count() > 0) {
	$return = $layout->first();
	
	echo DbView::make($return)->field('code')->with(['debtor'=> $d,'sender'=> $c])->render();

}else{

?>

<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
			Beste {!! $d->contact or $d->name !!},<br />
			<br />
			In de bijlage vind u de factuur voor de door u afgenomen producten of diensten.<br />
			<br />
			<br />
			Met vriendelijke groeten,<br />
			{!! User::getUserName($i->cid) !!}
		</div>
	</body>
</html>

<?php

}

?>

