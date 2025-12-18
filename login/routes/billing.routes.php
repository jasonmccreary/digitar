<?php

use App\Http\Controllers\DebtorsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('billing', function () {
    return view('billing.overview', [
        'title' => 'Facturatie',
    ]);
})->before('auth');

/*
 * ======================
 *  Debtors
 * ======================
 */

Route::get('billing/debtors', function () {
    $debtors = new Debtors;
    $aDebtors = $debtors->where('cid', '=', Auth::user()->cid)->get();

    return view('billing.debtors.overview', [
        'title' => 'Debiteuren',
        'debtors' => $aDebtors,
    ]);
})->before('auth');

Route::get('billing/debtors/add', function () {
    return view('billing.debtors.add', [
        'title' => 'Debiteur toevoegen',
    ]);
})->before('auth');
Route::post('billing/debtors/add', [
    'before' => 'auth',
    'uses' => [DebtorsController::class, 'add'],
]);

Route::get('billing/debtor/edit/{id}', function ($id) {
    $debtor = Debtors::where('id', '=', $id)->where('cid', '=', Auth::user()->cid)->first();

    return view('billing.debtors.edit', [
        'title' => 'Factuur bewerken',
        'd' => $debtor,
    ]);
})->before('auth');
Route::post('billing/debtor/edit/{id}', [DebtorsController::class, 'edit'])->before('auth');

Route::get('billing/debtor/delete/{id}', function ($id) {
    $debtor = new Debtors;
    $d = $debtor->find($id);

    return view('billing.debtors.delete', [
        'title' => 'Factuur verwijderen',
        'd' => $d,
    ]);
})->before('auth');
Route::post('billing/debtor/delete/{id}', [DebtorsController::class, 'delete'])->before('auth');

/*
 * ======================
 *  Invoices
 * ======================
 */

Route::get('billing/invoices', function () {
    $invoices = new Invoices;
    $aInvoices = $invoices->where('cid', '=', Auth::user()->cid)->where('date', 'like', Session::get('year').'%')->orderBy('invoicenumber', 'ASC')->get();

    return view('billing.invoices.overview', [
        'title' => 'Facturen',
        'invoices' => $aInvoices,
    ]);
})->name('invoices')->before('auth');

Route::get('billing/invoices/add', function () {
    return view('billing.invoices.add', [
        'title' => 'Factuur aanmaken',
    ]);
})->before('auth');
Route::post('billing/invoices/add', [
    'before' => 'auth',
    'uses' => [InvoiceController::class, 'add'],
]);

Route::get('billing/invoice/edit/{id}', function ($id) {
    $invoice = Invoices::where('id', '=', $id)->where('cid', '=', Auth::user()->cid)->first();

    return view('billing.invoices.edit', [
        'title' => 'Factuur bewerken',
        'i' => $invoice,
    ]);
})->before('auth');
Route::post('billing/invoice/edit/{id}', [InvoiceController::class, 'edit'])->before('auth');

Route::get('billing/invoice/delete/{id}', function ($id) {
    $invoice = new Invoices;
    $i = $invoice->find($id);

    return view('billing.invoices.delete', [
        'title' => 'Factuur verwijderen',
        'i' => $i,
    ]);
})->before('auth');
Route::post('billing/invoice/delete/{id}', [InvoiceController::class, 'delete'])->before('auth');

Route::get('billing/invoice/paid/{id}', function ($id) {
    $invoice = Invoices::where('cid', '=', Auth::user()->cid)->where('id', '=', $id)->first();
    $invoice->status = 10;
    $invoice->save();

    Alert::success('Betaling opgeslagen')->flash();

    return redirect('/billing/invoices');
})->before('auth');

Route::get('billing/invoice/send/{id}', function ($id) {
    $m = Layouts::getMail();
    if ($m->count()) {

        $i = Invoices::getId($id);
        if (Debtors::where('id', '=', $i->did)->count() <= 0) {
            Alert::error('De debiteur bestaat niet meer.')->flash();

            return Redirect::back();
        }

        return view('billing.invoices.send', [
            'title' => 'Factuur versturen',
            'i' => $i,
        ]);
    } else {
        Alert::error('U hebt nog geen mail layouts aangemaakt!')->flash();

        return Redirect::back();
    }
})->before('auth');
Route::post('billing/invoice/send/{id}', [InvoiceController::class, 'send'])->before('auth');

/*
 * ======================
 *  Products
 * ======================
 */

Route::get('billing/products', function () {
    $products = new Products;
    $aProducts = $products->where('cid', '=', Auth::user()->cid)->get();

    return view('billing.products.overview', [
        'title' => 'Artikelen &amp; Diensten',
        'products' => $aProducts,
    ]);
})->before('auth');

Route::get('billing/product/add', function () {
    return view('billing.products.add', [
        'title' => 'Artikel aanmaken',
    ]);
})->before('auth');
Route::post('billing/product/add', [
    'before' => 'auth',
    'uses' => [ProductsController::class, 'add'],
]);

Route::get('billing/product/edit/{id}', function ($id) {
    $product = Products::byID($id)->first();

    return view('billing.products.edit', [
        'title' => 'Artikel bewerken',
        'product' => $product,
    ]);
})->before('auth');
Route::post('billing/product/edit/{id}', [
    'before' => 'auth',
    'uses' => [ProductsController::class, 'edit'],
]);

Route::get('billing/product/delete/{id}', function ($id) {
    $p = Products::byID($id)->first();

    return view('billing.products.delete', [
        'title' => 'Artikel verwijderen',
        'p' => $p,
    ]);
})->before('auth');
Route::post('billing/product/delete/{id}', [ProductsController::class, 'delete'])->before('auth');

/*
 * ======================
 *  Settings
 * ======================
 */

Route::get('billing/settings/invoices', function () {
    return view('billing.settings.invoice.overview', [
        'title' => 'Factuur layouts',
    ]);
})->name('settingsInvoices')->before('auth');

Route::get('billing/settings/invoice/add', function () {
    return view('billing.settings.invoice.add', [
        'title' => 'Factuur layout toevoegen',
    ]);
})->before('auth');
Route::post('billing/settings/invoice/add', [SettingsController::class, 'saveInvoices'])->before('auth');

Route::get('billing/settings/invoice/edit/{id}', function ($id) {
    $invoice = Layouts::getInvoice($id)->first();

    return view('billing.settings.invoice.edit', [
        'title' => 'Factuur layout bewerken',
        'layout' => $invoice,
    ]);
})->before('auth');
Route::post('billing/settings/invoice/edit/{id}', [SettingsController::class, 'saveInvoices'])->before('auth');

Route::get('billing/settings/invoice/delete/{id}', function ($id) {
    $invoice = Layouts::getInvoice($id)->first();

    return view('billing.settings.invoice.delete', [
        'title' => 'Factuur layout verwijderen',
        'layout' => $invoice,
    ]);
})->before('auth');
Route::post('billing/settings/invoice/delete/{id}', [SettingsController::class, 'deleteInvoice'])->before('auth');

Route::get('billing/settings/mails', function () {
    return view('billing.settings.mail.overview', [
        'title' => 'Mail layouts',
    ]);
})->name('settingsMails')->before('auth');

Route::get('billing/settings/mail/add', function () {
    return view('billing.settings.mail.add', [
        'title' => 'Mail layout toevoegen',
    ]);
})->before('auth');
Route::post('billing/settings/mail/add', [SettingsController::class, 'saveMails'])->before('auth');

Route::get('billing/settings/mail/edit/{id}', function ($id) {
    $invoice = Layouts::getMail($id)->first();

    return view('billing.settings.mail.edit', [
        'title' => 'Factuur layout bewerken',
        'layout' => $invoice,
    ]);
})->before('auth');
Route::post('billing/settings/mail/edit/{id}', [SettingsController::class, 'saveMails'])->before('auth');

Route::get('billing/settings/mail/delete/{id}', function ($id) {
    $invoice = Layouts::getMail($id)->first();

    return view('billing.settings.mail.delete', [
        'title' => 'Factuur layout verwijderen',
        'layout' => $invoice,
    ]);
})->before('auth');
Route::post('billing/settings/mail/delete/{id}', [SettingsController::class, 'deleteMail'])->before('auth');

Route::get('billing/settings/port', function () {
    return view('billing.settings.port.index');
})->before('auth');
Route::get('billing/settings/port/exportdebtors', [SettingsController::class, 'genDebtorsExport'])->before('auth');
Route::get('billing/settings/port/exportbilling', [SettingsController::class, 'genBillingExport'])->before('auth');

/*
 * ======================
 *  Ajax functions
 * ======================
 */

Route::get('billing/getdebtor/{did}', function ($did) {
    $d = new Debtors;
    $debtor = $d->where('id', '=', $did)->first();

    $html = '
			<span class="semi-bold">'.$debtor->name.'</span><br />
			'.$debtor->address.'<br />
			'.$debtor->zipcode.' &nbsp; '.$debtor->city.'<br />
			'.$debtor->email.'
		';

    return $html;
})->before('auth');

Route::get('billing/getproduct/{pid}', function ($pid) {
    $product = Products::byID($pid);
    if ($product->count() > 0) {

        $p = $product->first();
        $array = [
            'description' => $p->description,
            'price' => $p->price,
            'tax' => $p->tax,
        ];

    } else {
        $array = [
            'description' => 'not found',
            'price' => '',
            'tax' => '',
        ];
    }

    echo json_encode($array, JSON_PRETTY_PRINT);
})->before('auth');

/*
 * ======================
 *  search
 * ======================
 */

Route::any('billing/search', [InvoiceController::class, 'searchFiles']);

/*
 * ======================
 *  Standalone
 * ======================
 */

Route::get('billing/viewmail/{id}', function ($id) {
    return view('emails.invoice', [
        'id' => $id,
    ]);
})->before('auth');

Route::get('billing/viewpdf/{id}', function ($id) {
    return view('billing.pdf', [
        'id' => $id,
    ]);
})->before('auth');

Route::get('billing/checkinvoicenumber/{id}', function ($id) {
    $check = Invoices::where('invoicenumber', '=', $id)->where('cid', '=', Auth::user()->cid);
    echo $check->count();
})->before('auth');

Route::get('billing/pdf/{action}/{id}', function ($action, $id) {
    $i = Invoices::where('id', '=', $id)->where('cid', '=', Auth::user()->cid)->first();
    $param['id'] = $i->id;
    $param['lid'] = $i->layout;
    $param['cid'] = $i->cid;
    $param['uid'] = $i->uid;
    $param['did'] = $i->did;
    $param['invoicenumber'] = $i->invoicenumber;
    $param['reference'] = $i->reference;
    $param['date'] = $i->date;
    $param['status'] = $i->status;

    $d = Debtors::where('id', '=', $i->did);
    if ($d->count() > 0) {
        $d = $d->first();
        $param['toName'] = $d->name;
        $param['toContact'] = $d->contact;
        $param['toAddress'] = $d->address;
        $param['toZipcode'] = $d->zipcode;
        $param['toCity'] = $d->city;
        $param['toNumber'] = $d->debnumber;
    } else {
        $param['toName'] = 'Onbekend';
        $param['toContact'] = '';
        $param['toAddress'] = '';
        $param['toZipcode'] = '';
        $param['toCity'] = '';
        $param['toNumber'] = '';
    }

    $layout = Layouts::where('cid', '=', Auth::user()->cid)->where('id', '=', $i->layout);
    if ($layout->count() <= 0) {
        $param['background'] = '';
    } else {
        $lParams = $layout->first()->params;
        $lParams = unserialize($lParams);
        $param['background'] = $lParams['background'];
    }

    define('DOMPDF_ENABLE_PHP', true);
    define('DOMPDF_ENABLE_HTML5PARSER', true);
    $pdf = PDF::loadView('billing.pdf', $param)->setPaper('a4');
    // $pdf->set_option('isHtml5ParserEnabled', true);

    if ($action == 'view') {
        return $pdf->stream($i->invoicenumber.'.pdf');
    } else {
        return $pdf->download($i->invoicenumber.'.pdf');
    }

})->before('auth');
