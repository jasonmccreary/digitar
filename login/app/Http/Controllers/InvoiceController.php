<?php

namespace App\Http\Controllers;

use App\Models\Debtors;
use App\Models\Invoicerows;
use App\Models\Invoices;
use App\Models\Layouts;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Prologue\Alerts\Facades\Alert;

class InvoiceController extends Controller
{
    public $invoice;

    public $debtor;

    public $client;

    public $org;

    private $subject;

    public function add(Request $request): RedirectResponse
    {
        $rules = [
            'debtor' => 'required|integer',
            'invoicenumber' => 'required|alpha_space',
            'date' => 'required',
        ];

        $v = Validator::make($request->all(), $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect()->back()->withInput($request->only('reference', 'debtor', 'invoicenumber', 'date'));
        } else {
            $invoice = new Invoices;
            $countRows = 0;

            $check = $invoice->where('invoicenumber', '=', $request->input('invoicenumber'))->where('cid', '=', $request->user()->cid);
            if ($check->count() > 0) {
                Alert::error('Dit factuurnummer nummer bestaat al.')->flash();

                return redirect()->back()->withInput($request->only('reference', 'debtor', 'date'));
            }

            $invoice->cid = $request->user()->cid;
            $invoice->uid = $request->user()->id;
            $invoice->did = $request->input('debtor');
            $invoice->invoicenumber = $request->input('invoicenumber');
            $invoice->reference = $request->input('reference');
            $invoice->layout = $request->input('layout');
            $invoice->date = date('Y-m-d H:i:s', strtotime($request->input('date')));

            $invoice->save();
            $invoiceId = $invoice->id;

            for ($i = 0; $i < count($request->input('r-type')); $i++) {
                $ir = new Invoicerows;
                if ($request->input('r-type.'.$i) == 9) {
                    $ir->iid = $invoiceId;
                    $ir->type = $request->input('r-type.'.$i);
                    $ir->description = $request->input('r-description.'.$i);

                    $ir->save();
                } elseif (is_numeric($request->input('r-product.'.$i))) {
                    $price = str_replace(['€', ' '], '', $request->get('r-price.'.$i));
                    if (strlen($price) > 3) {
                        $price = str_replace('.', '', $price);
                        $price = str_replace(',', '.', $price);
                    }

                    $ir->iid = $invoiceId;
                    $ir->type = $request->input('r-type.'.$i);
                    $ir->date = date('Y-m-d H:i:s', strtotime($request->input('r-date.'.$i)));
                    $ir->pid = $request->input('r-product.'.$i);
                    $ir->description = $request->input('r-description.'.$i);
                    $ir->amount = $request->input('r-amount.'.$i);
                    $ir->tax = $request->input('r-tax.'.$i);
                    $ir->price = $price;

                    $ir->save();
                }

                $countRows++;
            }

            if ($countRows == 0) {
                $invoice->delete();
                Alert::error('Geen factuur regels!')->flash();

                return redirect()->back()->withInput($request->only('reference', 'debtor', 'invoicenumber', 'date'));
            } else {
                Alert::success('Factuur toegevoegd')->flash();

                return Redirect::route('invoices');
            }
        }
    }

    public function edit(Request $request, $id): RedirectResponse
    {
        $rules = [
            'debtor' => 'required|integer',
            'invoicenumber' => 'required|alpha_space',
            'date' => 'required',
            'r-date' => 'array',
        ];

        $v = Validator::make($request->all(), $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect()->back()->withInput($request->only('reference', 'debtor', 'invoicenumber', 'date'));
        } else {
            $invoice = new Invoices;
            $countRows = 0;

            $check = $invoice->where('invoicenumber', '=', $request->input('invoicenumber'))->where('cid', '=', $request->user()->cid);
            if ($check->count() > 0 && $id != $check->first()->id) {
                Alert::error('Dit factuurnummer nummer bestaat al.')->flash();

                return redirect()->back()->withInput($request->only('reference', 'debtor', 'invoicenumber', 'date'));
            }

            $i = $invoice->find($id);

            $i->uid = $request->user()->id;
            $i->did = $request->input('debtor');
            $i->invoicenumber = $request->input('invoicenumber');
            $i->reference = $request->input('reference');
            $i->layout = $request->input('layout');
            $i->date = date('Y-m-d H:i:s', strtotime($request->input('date')));

            $i->save();

            Invoicerows::where('iid', '=', $id)->delete();
            foreach ($request->input('r-type') as $i => $v) {
                $ir = new Invoicerows;
                if ($request->input('r-type.'.$i) == 9) {
                    $ir->iid = $id;
                    $ir->type = $request->input('r-type.'.$i);
                    $ir->description = $request->input('r-description.'.$i);

                    $ir->save();
                } elseif (is_numeric($request->input('r-product.'.$i))) {
                    $price = str_replace(['€', ' '], '', $request->input('r-price.'.$i));
                    if (strlen($price) > 3) {
                        $price = str_replace('.', '', $price);
                        $price = str_replace(',', '.', $price);
                    }
                    // $price = str_replace(',','.',$request->input('r-price.'.$i));
                    // $price = str_replace(array('€',' '),'',$price);

                    $ir->iid = $id;
                    $ir->type = $request->input('r-type.'.$i);
                    $ir->date = date('Y-m-d H:i:s', strtotime($request->input('r-date.'.$i)));
                    $ir->pid = $request->input('r-product.'.$i);
                    $ir->description = $request->input('r-description.'.$i);
                    $ir->amount = $request->input('r-amount.'.$i);
                    $ir->tax = $request->input('r-tax.'.$i);
                    $ir->price = $price;

                    $ir->save();
                }

                $countRows++;
            }

            if ($countRows == 0) {
                $invoice->delete();
                Alert::error('Geen factuur regels!')->flash();

                return redirect()->back()->withInput($request->only('reference', 'debtor', 'invoicenumber', 'date'));
            } else {
                Alert::success('Factuur opgeslagen.')->flash();

                return Redirect::route('invoices');
                // return redirect()->back();
            }
        }
    }

    public function delete(Request $request, $id): RedirectResponse
    {
        if ($request->input('delete') == 'true') {
            $i = new Invoices;
            $invoice = $i->where('cid', '=', $request->user()->cid)->where('id', '=', $id)->delete();
            Invoicerows::where('iid', '=', $id)->delete();

            Alert::success('Factuur verwijderd!')->flash();
        }

        return Redirect::route('invoices');
    }

    public function send(Request $request, $id)
    {
        if ($request->input('send') == 'false') {
            return Redirect::route('invoices');
        }

        if ($request->input('send') == 'mail' && ! $request->has('maillayout')) {
            $i = Invoices::find($id);
            $d = Debtors::find($i->did);

            return view('billing.invoices.sendto', [
                'title' => 'Factuur versturen naar: '.$d->email,
            ]);
        }

        $save = true;

        if ($request->input('send') == 'mail') {
            $this->renderInvoice($id, $request->input('send'), $save);
            $layout = Layouts::find($request->input('maillayout'));
            $this->subject = unserialize($layout->params)['subject']; // 'Factuur '.$this->invoice->invoicenumber
            $patterns = [];
            $patterns[0] = '/%invoicenumber%/';
            $replacements = [];
            $replacements[0] = $this->invoice->invoicenumber;
            $this->subject = preg_replace($patterns, $replacements, $this->subject);
            Mail::send('emails.invoice', ['iid' => $id, 'lid' => $request->input('maillayout')], function ($message) {
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
            return $this->renderInvoice($id, $request->input('send'), $save);
        }

    }

    public function renderInvoice(Request $request, $id, $view = 'view', $save = false)
    {
        $this->invoice = Invoices::where('id', '=', $id)->where('cid', '=', $request->user()->cid)->first();
        $this->debtor = Debtors::where('id', '=', $this->invoice->did)->first();

        $this->org = User::find($request->user()->oid);
        $this->client = User::find($request->user()->cid);

        $param['id'] = $this->invoice->id;
        $param['lid'] = $this->invoice->layout;
        if ($view == 'print') {
            $param['print'] = true;
        }
        define('DOMPDF_ENABLE_PHP', true);
        define('DOMPDF_ENABLE_HTML5PARSER', true);

        $oPdf = Pdf::loadView('billing.pdf', $param)->setPaper('a4');

        if ($save == true) {
            $filePath = '/home/digitar/clients/'.$this->org->username.'/'.$this->client->username.'/'.$this->invoice->invoicenumber.'.pdf';
            $oPdf->save($filePath);

            if (! file_exists($filePath)) {
                FileController::addFile('Factuur '.$this->invoice->invoicenumber, $this->invoice->invoicenumber.'.pdf', $request->user()->cid);
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

    public function searchFiles(Request $request): View
    {

        $search = $request->input('billing-search');

        $i = new Invoices;
        $invoices = Invoices::select('invoices.*')->where(
            'invoices.cid', '=', $request->user()->cid
        )->where(
            'invoices.invoicenumber', 'LIKE', '%'.$search.'%'
        )->orWhere(
            'invoices.cid', '=', $request->user()->cid
        )->where(
            'invoices.date', 'LIKE', '%'.$search.'%'
        )->orWhere(
            'invoices.cid', '=', $request->user()->cid
        )->where(
            'debtors.name', 'LIKE', '%'.$search.'%'
        )->join('debtors', 'debtors.id', '=', 'invoices.did')->get();

        // echo '<pre>';
        // dd(DB::getQueryLog());
        // dd($invoices);

        if ($request->is('*ajax*')) {
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
