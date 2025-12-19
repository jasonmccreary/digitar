<?php

$i = App\Models\Invoices::find($id);
$d = App\Models\Debtors::find($i->did);
$c = App\Models\User::find(Auth::user()->cid);

$layout = App\Models\Layouts::where('cid','=',Auth::user()->cid)->where('type','=','3');
if ($layout->count() > 0) {
	$return = $layout->first();

	echo DbView::make($return)->field('code')->with(['debtor'=> $d,'invoice'=>$i,'myCompany'=> $c])->render();

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
			Enkele tijd terug heeft u een factuur met factuurnummer {!! $i->invoicenumber !!} ontvangen voor een van onze producten/diensten.<br />
			We hebben echter nog geen betaling mogen ontvangen voor deze factuur.<br />
			<br />
			Graag willen we u hierbij herinneren om de betaling alsnog binnen 14 dagen te voldoen.<br />
			<br />
			<br />
			Met vriendelijke groeten,<br />
			{!! $c->name !!}
		</div>
	</body>
</html>

<?php

}

?>

