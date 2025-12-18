<?php

use Illuminate\Database\Eloquent\Model;

class Invoicerows extends Model
{
    public function product()
    {
        return $this->belongsTo('Products', 'pid', 'id');
    }
}
