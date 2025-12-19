<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Debtors;
use App\Invoicerows;
use App\Invoices;
use App\Layouts;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

class InvoiceController extends Controller
{
    public $invoice;

    public $debtor;

    public $client;

    public $org;

    private $subject;

    public function add(): RedirectResponse
    {
        $rules = [
            'debtor' => 'required|integer',
            'invoicenumber' => 'required|alpha_space',
            'date' => 'required',
        ];

        $v = Validator::make(Request::all(), $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return Redirect::back()->withInput(Request::only('reference', 'debtor', 'invoicenumber', 'date'));
        } else {
            $invoice = new Invoices;
            $countRows = 0;

            $check = $invoice->where('invoicenumber', '=', Request::get('invoicenumber'))->where('cid', '=', Auth::user()->cid);
            if ($check->count() > 0) {
                Alert::error('Dit factuurnummer nummer bestaat al.')->flash();

                return Redirect::back()->withInput(Request::only('reference', 'debtor', 'date'));
            }

            $invoice->cid = Auth::user()->cid;
            $invoice->uid = Auth::user()->id;
            $invoice->did = Request::get('debtor');
            $invoice->invoicenumber = Request::get('invoicenumber');
            $invoice->reference = Request::get('reference');
            $invoice->layout = Request::get('layout');
            $invoice->date = date('Y-m-d H:i:s', strtotime(Request::get('date')));

            $invoice->save();
            $invoiceId = $invoice->id;

            for ($i = 0; $i < count(Request::get('r-type')); $i++) {
                $ir = new Invoicerows;
                if (Request::get('r-type.'.$i) == 9) {
                    $ir->iid = $invoiceId;
                    $ir->type = Request::get('r-type.'.$i);
                    $ir->description = Request::get('r-description.'.$i);

                    $ir->save();
                } elseif (is_numeric(Request::get('r-product.'.$i))) {
                    $price = str_replace(['€', ' '], '', Request::get('r-price.'.$i));
                    if (strlen($price) > 3) {
                        $price = str_replace('.', '', $price);
                        $price = str_replace(',', '.', $price);
                    }

                    $ir->iid = $invoiceId;
                    $ir->type = Request::get('r-type.'.$i);
                    $ir->date = date('Y-m-d H:i:s', strtotime(Request::get('r-date.'.$i)));
                    $ir->pid = Request::get('r-product.'.$i);
                    $ir->description = Request::get('r-description.'.$i);
                    $ir->amount = Request::get('r-amount.'.$i);
                    $ir->tax = Request::get('r-tax.'.$i);
                    $ir->price = $price;

                    $ir->save();
                }

                $countRows++;
            }

            if ($countRows == 0) {
                $invoice->delete();
                Alert::error('Geen factuur regels!')->flash();

                return Redirect::back()->withInput(Request::only('reference', 'debtor', 'invoicenumber', 'date'));
            } else {
                Alert::success('Factuur toegevoegd')->flash();

                return Redirect::route('invoices');
            }
        }
    }

    public function edit($id): RedirectResponse
    {
        $rules = [
            'debtor' => 'required|integer',
            'invoicenumber' => 'required|alpha_space',
            'date' => 'required',
            'r-date' => 'array',
        ];

        $v = Validator::make(Request::all(), $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return Redirect::back()->withInput(Request::only('reference', 'debtor', 'invoicenumber', 'date'));
        } else {
            $invoice = new Invoices;
            $countRows = 0;

            $check = $invoice->where('invoicenumber', '=', Request::get('invoicenumber'))->where('cid', '=', Auth::user()->cid);
            if ($check->count() > 0 && $id != $check->first()->id) {
                Alert::error('Dit factuurnummer nummer bestaat al.')->flash();

                return Redirect::back()->withInput(Request::only('reference', 'debtor', 'invoicenumber', 'date'));
            }

            $i = $invoice->find($id);

            $i->uid = Auth::user()->id;
            $i->did = Request::get('debtor');
            $i->invoicenumber = Request::get('invoicenumber');
            $i->reference = Request::get('reference');
            $i->layout = Request::get('layout');
            $i->date = date('Y-m-d H:i:s', strtotime(Request::get('date')));

            $i->save();

            Invoicerows::where('iid', '=', $id)->delete();
            foreach (Request::get('r-type') as $i => $v) {
                $ir = new Invoicerows;
                if (Request::get('r-type.'.$i) == 9) {
                    $ir->iid = $id;
                    $ir->type = Request::get('r-type.'.$i);
                    $ir->description = Request::get('r-description.'.$i);

                    $ir->save();
                } elseif (is_numeric(Request::get('r-product.'.$i))) {
                    $price = str_replace(['€', ' '], '', Request::get('r-price.'.$i));
                    if (strlen($price) > 3) {
                        $price = str_replace('.', '', $price);
                        $price = str_replace(',', '.', $price);
                    }
                    // $price = str_replace(',','.',Request::get('r-price.'.$i));
                    // $price = str_replace(array('€',' '),'',$price);

                    $ir->iid = $id;
                    $ir->type = Request::get('r-type.'.$i);
                    $ir->date = date('Y-m-d H:i:s', strtotime(Request::get('r-date.'.$i)));
                    $ir->pid = Request::get('r-product.'.$i);
                    $ir->description = Request::get('r-description.'.$i);
                    $ir->amount = Request::get('r-amount.'.$i);
                    $ir->tax = Request::get('r-tax.'.$i);
                    $ir->price = $price;

                    $ir->save();
                }

                $countRows++;
            }

            if ($countRows == 0) {
                $invoice->delete();
                Alert::error('Geen factuur regels!')->flash();

                return Redirect::back()->withInput(Request::only('reference', 'debtor', 'invoicenumber', 'date'));
            } else {
                Alert::success('Factuur opgeslagen.')->flash();

                return Redirect::route('invoices');
                // return Redirect::back();
            }
        }
    }

    public function delete($id): RedirectResponse
    {
        if (Request::get('delete') == 'true') {
            $i = new Invoices;
            $invoice = $i->where('cid', '=', Auth::user()->cid)->where('id', '=', $id)->delete();
            Invoicerows::where('iid', '=', $id)->delete();

            Alert::success('Factuur verwijderd!')->flash();
        }

        return Redirect::route('invoices');
    }

    public function send($id)
    {
        if (Request::get('send') == 'false') {
            return Redirect::route('invoices');
        }

        if (Request::get('send') == 'mail' && ! Request::has('maillayout')) {
            $i = Invoices::find($id);
            $d = Debtors::find($i->did);

            return view('billing.invoices.sendto', [
                'title' => 'Factuur versturen naar: '.$d->email,
            ]);
        }

        $save = true;

        if (Request::get('send') == 'mail') {
            $this->renderInvoice($id, Request::get('send'), $save);
            $layout = Layouts::find(Request::get('maillayout'));
            $this->subject = unserialize($layout->params)['subject']; // 'Factuur '.$this->invoice->invoicenumber
            $patterns = [];
            $patterns[0] = '/%invoicenumber%/';
            $replacements = [];
            $replacements[0] = $this->invoice->invoicenumber;
            $this->subject = preg_replace($patterns, $replacements, $this->subject);
            Mail::send('emails.invoice', ['iid' => $id, 'lid' => Request::get('maillayout')], function ($message) {
                $message->from($this->client->username.'@digitar.nu', $this->client->name);
                $message->replyTo($this->client->email, $this->client->name);
                $mails = multiexplode([',', ';', '\\', '/'], $this->debtor->email);
                $message->to($mails)->subject($this->subject);

                $pathToFile = '/home/digitar/clients/'.$this->org->username.'/'.$this->client->username.'/'.$this->invoice->invoicenumber.'.pdf';
                $message->attach($pathToFile);
            });

            Alert::success('De factuur is verstuurd!')->flash();

            return Redirect::route('invoices');
        } else {
            return $this->renderInvoice($id, Request::get('send'), $save);
        }

    }

    public function renderInvoice($id, $view = 'view', $save = false)
    {
        $this->invoice = Invoices::where('id', '=', $id)->where('cid', '=', Auth::user()->cid)->first();
        $this->debtor = Debtors::where('id', '=', $this->invoice->did)->first();

        $this->org = User::find(Auth::user()->oid);
        $this->client = User::find(Auth::user()->cid);

        $param['id'] = $this->invoice->id;
        $param['lid'] = $this->invoice->layout;
        if ($view == 'print') {
            $param['print'] = true;
        }
        define('DOMPDF_ENABLE_PHP', true);
        define('DOMPDF_ENABLE_HTML5PARSER', true);

        $oPdf = PDF::loadView('billing.pdf', $param)->setPaper('a4');

        if ($save == true) {
            $filePath = '/home/digitar/clients/'.$this->org->username.'/'.$this->client->username.'/'.$this->invoice->invoicenumber.'.pdf';
            $oPdf->save($filePath);

            if (! file_exists($filePath)) {
                FileController::addFile('Factuur '.$this->invoice->invoicenumber, $this->invoice->invoicenumber.'.pdf', Auth::user()->cid);
            }

            $this->invoice->status = 1;
            $this->invoice->save();
        }

        switch ($view) {
            case 'view':
                return $oPdf->stream($this->invoice->invoicenumber.'.pdf');
                break;

            case 'download':
                return $oPdf->download($this->invoice->invoicenumber.'.pdf');
                break;

            case 'print':
                return $oPdf->download($this->invoice->invoicenumber.'.pdf');
                break;

            default:
                return Redirect::route('invoices');
                break;
        }
    }

    public function searchFiles(): View
    {

        $search = Request::get('billing-search');

        $i = new Invoices;
        $invoices = Invoices::select('invoices.*')->where(
            'invoices.cid', '=', Auth::user()->cid
        )->where(
            'invoices.invoicenumber', 'LIKE', '%'.$search.'%'
        )->orWhere(
            'invoices.cid', '=', Auth::user()->cid
        )->where(
            'invoices.date', 'LIKE', '%'.$search.'%'
        )->orWhere(
            'invoices.cid', '=', Auth::user()->cid
        )->where(
            'debtors.name', 'LIKE', '%'.$search.'%'
        )->join('debtors', 'debtors.id', '=', 'invoices.did')->get();

        // echo '<pre>';
        // dd(DB::getQueryLog());
        // dd($invoices);

        if (Request::is('*ajax*')) {
            return view('billing.ajax.search', [
                'title' => 'Zoeken naar: '.$search,
                'invoices' => $invoices,
            ]);
        } else {
            return view('billing.search', [
                'title' => 'Zoeken naar: '.$search,
                'invoices' => $invoices,
            ]);
        }

    }
}
