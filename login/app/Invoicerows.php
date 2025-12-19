<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoicerows extends Model
{
    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Products::class, 'pid', 'id');
    }
}
