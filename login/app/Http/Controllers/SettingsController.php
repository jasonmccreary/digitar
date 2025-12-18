<?php

namespace App\Http\Controllers;

use App\Debtors;
use App\Invoices;
use App\Layouts;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Prologue\Alerts\Facades\Alert;

class SettingsController extends Controller
{
    /*
     * ======================
     *  Layout types
     *  1. Mail - new invoice
     *  2. Invoice - invoice template
     * ======================
     */

    public static function saveMails($id = false)
    {

        if ($id != false) {
            $layout = Layouts::where('cid', '=', Auth::user()->cid)->where('id', '=', $id);
            if ($layout->count() > 0) {
                $layout = $layout->first();
            }
        } else {
            $layout = new Layouts;
        }

        $layout->cid = Auth::user()->cid;
        $layout->type = 'mail';
        $layout->name = Input::get('name');
        $layout->code = Input::get('code');
        $layout->params = serialize(['subject' => Input::get('subject')]);
        $layout->save();

        Alert::success('Mail layout opgeslagen!')->flash();

        return Redirect::route('settingsMails');
    }

    public static function deleteMail($id)
    {
        $layout = Layouts::where('cid', '=', Auth::user()->cid)->where('id', '=', $id)->delete();

        Alert::success('Mail layout verwijderd!')->flash();

        return Redirect::route('settingsMails');
    }

    public static function saveInvoices($id = false)
    {

        if ($id != false) {
            $layout = Layouts::where('cid', '=', Auth::user()->cid)->where('id', '=', $id);
            if ($layout->count() > 0) {
                $layout = $layout->first();
            }
            $params = unserialize($layout->params);
        } else {
            $layout = new Layouts;
        }

        if (Input::hasFile('file')) {
            if (Input::file('file')->guessClientExtension() == 'jpeg') {
                $path = '/home/digitar/public_html/login/public/uploads/';
                $filename = 'factuurpapier-'.User::getUserUsername(Auth::user()->cid).'-'.uniqid().'.jpg';
                $url = 'https://login.digitar.nu/uploads/';

                Input::file('file')->move($path, $filename);

                $params['background'] = $url.$filename;

            } else {
                Alert::error('Foutief bestand, alleen .jpg en .jpeg bestanden zijn toegestaan!')->flash();
            }
        }

        $params['css'] = Input::get('css');

        $layout->cid = Auth::user()->cid;
        $layout->type = 'factuur';
        $layout->name = Input::get('name');
        $layout->code = Input::get('code');
        if (isset($params)) {
            $layout->params = serialize($params);
        }
        $layout->save();

        Alert::success('Factuur layout opgeslagen!')->flash();
        if ($id != false) {
            return Redirect::back();
        } else {
            return Redirect::route('settingsInvoices');
        }
    }

    public static function deleteInvoice($id)
    {
        $layout = Layouts::where('cid', '=', Auth::user()->cid)->where('id', '=', $id)->delete();

        Alert::success('Factuur layout verwijderd!')->flash();

        return Redirect::route('settingsInvoices');
    }

    /*
        IMPORT AND EXPORT FUNCTIONS BELOW
     */
    public function genDebtorsExport()
    {
        $debtors = Debtors::where('cid', '=', Auth::user()->cid)->get();
        // return $debtors;
        $return = 'Zoeknaam; Debiteru nummer; Code aanmanen; Journaal code; BTW berekenen; Kredietbeperking code; Kortingspercentage; Naam; Straat; Postcode; Woonplaats; Land; KVK nummer; BTW nummer; Contact persoon; Contact telefoon; Contact mobiel; Contact email; Contact website
';
        foreach ($debtors as $debtor) {
            $return .= substr(strtoupper(str_replace(' ', '', $debtor->name)), 0, 8).'; '; // Zoeknaam
            $return .= $debtor->debnumber.'; '; // Debiteur nummer
            $return .= 'J; '; // Code aanmanen
            $return .= '0; '; // Journaal code
            $return .= 'J; '; // BTW berekenen
            $return .= '0; '; // Kredietbeperking code
            $return .= '0; '; // Kortingspercentage
            $return .= $debtor->name.'; '; // Naam
            $return .= $debtor->address.'; '; // Straat
            $return .= $debtor->zipcode.'; '; // Postcode
            $return .= $debtor->city.'; '; // Woonplaats
            $return .= $debtor->country.'; '; // Land
            $return .= $debtor->kvknr.'; '; // KVK Nummer
            $return .= $debtor->btwnr.'; '; // BTW Nummer
            $return .= $debtor->contact.'; '; // Contact persoon
            $return .= $debtor->phone.'; '; // Contact telefoon
            $return .= $debtor->mobile.'; '; // Contact mobiel
            $return .= $debtor->email.'; '; // Contact email
            $return .= $debtor->website.';
'; // Contact website
        }

        $file = public_path().'/download/debiteuren.csv';
        file_put_contents($file, $return);
        $headers = ['Content-Type: application/pdf'];

        Alert::info('De export wordt gegenereerd!')->flash();

        return Response::download($file)->setTtl(1);
        // return Redirect::back();
    }

    public function genBillingExport()
    {
        $invoices = Invoices::where('cid', '=', Auth::user()->cid)->get();
        // return $invoices;
        $return = 'CDBETCOND; BTWBEREKENEN; BEDRAGBTW; BTWAANGEPAST; CDBTW; CDAANMANEN; CDDAGBOEK; CDDEBITEUR; FACTSALDO; FACTDATUM; FACTNUMMER; PERIODE
';
        foreach ($invoices as $invoice) {
            if ($invoice->debtor()->count() > 0) {
                $return .= '14;'; // Betalings conditie
                $return .= 'J;'; // BTW berekenen
                $return .= str_replace('.', ',', Invoices::getTotal($invoice->id, true, false)).';'; // BTW bedrag
                $return .= 'N;'; // BTW aangepast
                $return .= '2;'; // Code BTW
                $return .= 'J;'; // Code aanmanen
                $return .= '80;'; // Code aanmanen
                $return .= $invoice->debtor()->first()->debnumber.';'; // Debiteur nummer
                $return .= str_replace('.', ',', Invoices::getTotal($invoice->id, false, true)).';'; // Factuurbedrag
                $return .= Carbon::parse($invoice->date)->format('d-m-Y').';'; // Factuurdatum
                $return .= $invoice->invoicenumber.';'; // Factuurnummer
                $return .= Carbon::parse($invoice->date)->format('n').';
'; // Periode
            }
        }

        $file = public_path().'/download/invoices.csv';
        file_put_contents($file, $return);

        Alert::info('De export wordt gegenereerd!')->flash();

        return Response::download($file)->setTtl(1);
        // return Redirect::back();
    }
}
