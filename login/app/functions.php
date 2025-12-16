<?php

function nice_date($date) {
	return strftime('%d - %m - %Y', strtotime($date));
	//return strftime('%A, %d %B %Y', strtotime($date));
}

function text_date($date) {
	return strftime('%e %B %Y', strtotime($date));
	//return strftime('%A, %d %B %Y', strtotime($date));
}

function simple_date($date) {
	return strftime('%d-%m-%Y', strtotime($date));
}

function getSearchVal() {
    if (Request::segment(2) == 'search') {
        return urldecode(Request::segment(3));
    }
}

function getFileType($file) {
	$ext = explode('.',$file);
	$ext = $ext[(count($ext)-1)];
	return strtoupper($ext);
}

function priceToDB($price) {
	$price = str_replace('.','',$price);
	$price = str_replace(',','.',$price);
	return str_replace(array('€',' '),'',$price);
}

function euro($s, $sign = true){
    if ((string)$s === "" ){
    	return "-";
    }else{
    	if ($sign) {
    		$s = sprintf( "€ %s", number_format($s,2,",","."));
    	}else {
    		$s = sprintf( "%s", number_format($s,2,",","."));
    	}
    	return $s;
    }
}

function multiexplode ($delimiters,$string) {

    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

