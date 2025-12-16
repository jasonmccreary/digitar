<?php

class Products extends Eloquent {

	static public function newProdNumber() {
		$max = Products::where('cid', '=', Auth::user()->cid)->max('productnumber');
		return filter_var($max, FILTER_SANITIZE_NUMBER_INT) + 1;		
	}

	static public function byID($id) {
		return Products::where('cid','=',Auth::user()->cid)->where('id','=',$id);
	}

	static public function numberExists($pn) {
		return Products::where('cid','=',Auth::user()->cid)->where('productnumber','=',$pn);
	}

	static public function getDropdown($cid = false) {
		if ($cid == false) {
			$cid = Auth::user()->cid;
		}

		$array = array();
		$array[0] = '- Selecteer artikel -';
		foreach(Products::where('cid','=',$cid)->orderBy('productnumber','ASC')->get() as $p) {
			$array[$p->id] = $p->productnumber .' - '. $p->name;
		}

		return $array;
	}


}