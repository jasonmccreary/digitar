<?php

class Invoicerows extends Eloquent
{
    public function product()
    {
        return $this->belongsTo('Products', 'pid', 'id');
    }
}
