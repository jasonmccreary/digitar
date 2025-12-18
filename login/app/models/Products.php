<?php

class Products extends Eloquent
{
    public static function newProdNumber()
    {
        $max = Products::where('cid', '=', Auth::user()->cid)->max('productnumber');

        return filter_var($max, FILTER_SANITIZE_NUMBER_INT) + 1;
    }

    public static function byID($id)
    {
        return Products::where('cid', '=', Auth::user()->cid)->where('id', '=', $id);
    }

    public static function numberExists($pn)
    {
        return Products::where('cid', '=', Auth::user()->cid)->where('productnumber', '=', $pn);
    }

    public static function getDropdown($cid = false)
    {
        if ($cid == false) {
            $cid = Auth::user()->cid;
        }

        $array = [];
        $array[0] = '- Selecteer artikel -';
        foreach (Products::where('cid', '=', $cid)->orderBy('productnumber', 'ASC')->get() as $p) {
            $array[$p->id] = $p->productnumber.' - '.$p->name;
        }

        return $array;
    }
}
