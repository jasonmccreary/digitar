<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Invoicerows extends Model
{
    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Products::class, 'pid', 'id');
    }
}
