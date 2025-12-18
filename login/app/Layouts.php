<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Layouts extends Model
{
    public static function getInvoice($id = false)
    {
        $layout = Layouts::where('cid', '=', Auth::user()->cid)->where('type', '=', 'factuur');

        if ($id) {
            $layout->where('id', '=', $id);
        }

        return $layout;
    }

    public static function getInvoiceselect()
    {
        // $select[0] = ' - Selecteer layout -';
        foreach (Layouts::getInvoice()->get() as $l) {
            $select[$l->id] = $l->name;
        }

        return $select;
    }

    public static function getMail($id = false)
    {
        $layout = Layouts::where('cid', '=', Auth::user()->cid)->where('type', '=', 'mail');

        if ($id) {
            $layout->where('id', '=', $id);
        }

        return $layout;
    }

    public static function getMailselect()
    {
        $select = [];
        foreach (Layouts::getMail()->get() as $l) {
            $select[$l->id] = $l->name;
        }

        return $select;
    }

    public static function setUp($u)
    {

        if ($u->cid < 1) {
            $u->cid = $u->id;
        }

        if (Layouts::where('cid', '=', $u->cid)->count() < 1) {

            // setup mail layout
            $layout = new Layouts;
            $layout->cid = $u->cid;
            $layout->type = 'mail';
            $layout->name = 'Nieuwe Factuur';
            $layout->code = 'Beste {{ $debtor->name}},<br />
<br />
Er is een nieuwe factuur voor u aangemaakt voor de door u afgenomen diensten / producten.<br />
<br />
U vindt de factuur met de nodige gegevens in de vorm van een PDF-bestand in de bijlage. Voor het bekijken van de documenten dient u te beschikken over Adobe Reader.<br />
<br />
Indien u niet beschikt over Adobe Reader dan kunt u middels onderstaande link deze gratis downloaden.<br />
http://get.adobe.com/nl/reader/<br />
<br />
Voor meer informatie kunt u contact met ons opnemen.<br />
<br />
Hopende u voldoende te hebben geïnformeerd, <br />
<br />
Met vriendelijke groet,<br />
<br />
'.ucwords($u->name).'<br />';
            $layout->params = serialize(['subject' => 'Factuur']);
            $layout->save();

            // setup invoice layout
            $params['background'] = 'https://login.digitar.nu/uploads/demofactuurpapier.jpg';
            $params['css'] = 'table {border-spacing:0; border-collapse: collapse;}
ul {list-style-type: none; padding-left:0;}
h2   { color:#1B1E24; font-size:20pt; font-weight:normal; line-height:1.2em; display: none; }
h3   { color:#2D3139; font-size:13pt; font-weight:normal; margin-bottom: 0em}

table th.right,
table td.right              { text-align:right; }
table th.left,
table td.left               { text-align:left; }
table th.center,
table td.center             { text-align:center; }

.company-data				{ margin: 4em 2em; font-size:1.4em; }
.customer-data              { padding:1em 0; }
.customer-data table        { width:100%;       }
.customer-data table td     { width:50%;        }
.customer-data td span      { display:block; margin:0 0 5pt; padding-bottom:2pt; border-bottom:1px solid #DCDCDC; }
.customer-data td span.left { margin-right:1em; }
.customer-data label        { display:block; font-weight:bold; font-size:8pt; }
.payment-data               { padding:1em 0;    }
.payment-data table         { width:100%;border-left:1px solid #777;border-right:1px solid #777;       }
.payment-data th,
.payment-data td            { line-height:1em; padding:5pt 8pt 5pt; }
.payment-data thead th      { border-bottom:1px solid #777;border-top:1px solid #777;}
.payment-data th            { font-weight:bold; white-space:nowrap; }
.payment-data .bottomleft   { border-color:white; border-top:inherit; border-right:inherit; }
.payment-data span.tax      { display:block; white-space:nowrap; }
.terms, .notes              { position:absolute;bottom:0;text-align:center;width:100%;font-style:italic;font-size:10pt; }

.section                    { margin-bottom: 1em; }
.logo                       { text-align: right; }
.grey 						{ color:#888888;font-size:8pt;padding-top:0.5em; }';

            $layout = new Layouts;
            $layout->cid = $u->cid;
            $layout->type = 'factuur';
            $layout->name = 'Factuur';
            $layout->code = '<div class="section">
<div class="company-data">
  <ul>
    <li>{{ $toName }}</li>
    <li>{{ $toAddress }}</li>
    <li>{{ $toZipcode }}   {{ $toCity }}</li>
  </ul>
</div>
</div>

<h2>Factuur</h2>

<div class="section">

<table width="100%" style="margin:1.5em 0 3em 0;">
	<tr>
		<td class="center">Klantnummer: {{ $toNumber }}</td>
		<td class="center">Factuurnummer: {{ $invoicenumber }}</td>
		<td class="center">Factuurdatum: {{ nice_date($date) }}</td>
	</tr>
</table>

<div class="payment-data">
  <table>
    <thead>
      <tr>
      	<th class="left">Datum</th>
        <th class="center">Aantal</th>
        <th class="left">Omschrijving</th>
        <th class="right">BTW</th>
        <th class="right">Prijs</th>
        <th class="right">Totaal</th>
      </tr>
    </thead>
    <tbody>
    	@foreach(Invoicerows::where(\'iid\',\'=\',$id)->get() as $ir)
        @if($ir->type == 9)
          <tr>
            <td class="left" colspan="6" style="padding-top:15px;padding-bottom:15px;"><b>{{ nl2br($ir->description) }}</b></td>
          </tr>
        @else
          <tr>
            <td class="left">{{ simple_date($ir->date) }}</td>
            <td class="center">{{ $ir->amount }}</td>
            <td class="left">{{ $ir->description }}</td>
            <td class="right">
                <span class="tax">{{ $ir->tax }}%</span>
            </td>
            <td class="right">{{ euro($ir->price) }}</td>
            <td class="right">{{ euro($ir->amount * $ir->price) }}</td>
          </tr>
        @endif
        @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td class="bottomleft" colspan="4"></td>
        <th class="right">Basis BTW</th>
        <td class="right">{{ euro(Invoices::getTotal($id)) }}</td>
      </tr>
      <tr>
        <td class="bottomleft" colspan="4"></td>
        <th class="right">Bedrag BTW</th>
        <td class="td_total_taxes right">{{ euro(Invoices::getTotal($id,true)) }}</td>
      </tr>
      <tr class="strong">
        <td class="bottomleft" colspan="4"></td>
        <th class="right">Factuurbedrag</th>
        <td class="td_total right">{{ euro(Invoices::getTotal($id,false,true)) }}</td>
      </tr>
    </tfoot>
  </table>
</div>
</div>

@if(isset($print)) 
	<div class="section">
		<div class="terms">
			Betaling gaarne binnen 14 dagen op rekeningnummer 0000000000 onder vermelding van het factuurnummer.
			<div class="grey">'.ucwords($u->name).'       KvK nummer: 000000       BTW nummer: NL 0000.00.000.B.01.</div>
		</div>
	</div>
@endif';
            if (isset($params)) {
                $layout->params = serialize($params);
            }
            $layout->save();

            $p = new Products;
            $p->cid = $u->cid;
            $p->ledger = '8000';
            $p->productnumber = 'P0001';
            $p->name = 'Demo';
            $p->description = 'Demo product';
            $p->price = 10;
            $p->tax = 21;
            $p->save();

            $d = new Debtors;
            $d->cid = $u->cid;
            $d->uid = $u->id;
            $d->debnumber = 'D00001';
            $d->name = 'Demo debiteur';
            $d->address = 'Straatnaam 1';
            $d->zipcode = '1234 AB';
            $d->city = 'Plaatsnaam';
            $d->country = 'NL';
            $d->payterm = '30';
            $d->save();
        }

    }
}
