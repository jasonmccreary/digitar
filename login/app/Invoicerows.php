<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoicerows extends Model
{
    public function product()
    {
        return $this->belongsTo(\App\Products::class, 'pid', 'id');
    }
}
