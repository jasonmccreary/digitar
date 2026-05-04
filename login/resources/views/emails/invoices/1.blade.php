<?php

$i = App\Models\Invoices::find($id);
$d = App\Models\Debtors::find($i->did);
$c = App\Models\User::find(Auth::user()->cid);

$layout = App\Models\Layouts::query()
		->where('cid', Auth::user()->cid)
		->where('type', '1')
		->first();
if ($layout) {
	echo Illuminate\Support\Facades\Blade::render($layout->code, ['debtor' => $d, 'myCompany' => $c]);
} else {
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
<div>
	Beste {!! $d->contact ?? $d->name !!},<br/>
	<br/>
	In de bijlage vind u de factuur voor de door u afgenomen producten of diensten.<br/>
	<br/>
	<br/>
	Met vriendelijke groeten,<br/>
	{!! User::getUserName($i->cid) !!}
</div>
</body>
</html>
<?php
}
?>

