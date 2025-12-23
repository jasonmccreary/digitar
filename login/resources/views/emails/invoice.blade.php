<?php

$i = App\Models\Invoices::find($iid);
$d = App\Models\Debtors::find($i->did);
$c = App\Models\User::find(Auth::user()->cid);

$layout = App\Models\Layouts::where('cid','=',Auth::user()->cid)->where('id','=',$lid);
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
			Beste {!! $d->contact ?? $d->name !!},<br />
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

