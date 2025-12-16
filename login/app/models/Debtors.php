<?php

class Debtors extends Eloquent {

	static public function newDebNumber() {
		$max = Debtors::where('cid', '=', Auth::user()->cid)->max('debnumber');
		return filter_var($max, FILTER_SANITIZE_NUMBER_INT) + 1;
	}

	static public function get($id) {
		$deb = Debtors::where('cid', '=', Auth::user()->cid)->where('id','=',$id);
		if ($deb->count() > 0) {
			return $deb->first();
		}else {
			return false;
		}
	}

	static public function getName($id) {
		$debtor = Debtors::where('cid', '=', Auth::user()->cid)->where('id','=',$id);

		if ($debtor->count()) {
			$d = $debtor->first();
			return $d->name;
		}else{
			return 'Onbekend';
		}
	}

}