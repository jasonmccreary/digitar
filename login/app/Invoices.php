<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Invoices extends Model
{
    public static function newInvoiceNumber()
    {
        $max = Invoices::where('cid', '=', Auth::user()->cid)->max('invoicenumber');

        return filter_var($max, FILTER_SANITIZE_NUMBER_INT) + 1;
    }

    public static function getTotal($id, $showtax = false, $showincltax = false, $taxval = false)
    {
        $total = 0;

        if (! $showtax && ! $showincltax) {
            foreach (Invoicerows::where('iid', '=', $id)->get() as $ir) {
                $total += ($ir->amount * $ir->price);
            }
        }
        if ($showtax && ! $showincltax) {
            foreach (Invoicerows::where('iid', '=', $id)->get() as $ir) {
                if ($taxval != false) {
                    if ($ir->tax == $taxval) {
                        $total += (($ir->amount * $ir->price) * ($ir->tax / 100));
                    }
                } else {
                    $total += (($ir->amount * $ir->price) * ($ir->tax / 100));
                }
            }
        }
        if (! $showtax && $showincltax) {
            foreach (Invoicerows::where('iid', '=', $id)->get() as $ir) {
                $total += (($ir->amount * $ir->price) + (($ir->amount * $ir->price) * ($ir->tax / 100)));
            }
        }

        return $total;
    }

    public static function getId($id)
    {
        return Invoices::where('cid', '=', Auth::user()->cid)->where('id', '=', $id)->first();
    }

    public static function showStatus($id)
    {
        $invoice = Invoices::find($id);

        if ($invoice->status != 10) {
            $debtor = Debtors::find($invoice->did);

            if (is_object($debtor)) {
                $now = time();
                $invoiceDate = strtotime($invoice->date);
                $daysDiff = $now - $invoiceDate;
                $daysDiff = floor($daysDiff / (60 * 60 * 24));
                if ($daysDiff > $debtor->payterm) {
                    $invoice->status = 5;
                    $invoice->save();
                }
                if ($daysDiff < $debtor->payterm && $invoice->status == 5) {
                    $invoice->status = 0;
                    $invoice->save();
                }
            }
        }

        switch ($invoice->status) {
            case '0':
                return '<span class="label">Open</span>';
                break;

            case '1':
                return '<span class="label">Verstuurd</span>';
                break;

            case '5':
                return '<span class="label label-danger">Te laat</span>';
                break;

            case '10':
                return '<span class="label label-info">Betaald</span>';
                break;

            default:
                return '<span class="label label-white">Onbekend</span>';
                break;
        }
    }

    public function debtor(): BelongsTo
    {
        return $this->belongsTo(\App\Debtors::class, 'did', 'id');
    }
}
