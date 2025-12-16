<?php

Route::get('billing', function() {
	return View::make('billing.overview', array(
    	'title' => 'Facturatie'
    ));
})->before('auth');

/*
 * ======================
 *  Debtors
 * ======================
 */

	Route::get('billing/debtors', function() {
		$debtors = new Debtors();
		$aDebtors = $debtors->where('cid','=',Auth::user()->cid)->get();

        return View::make('billing.debtors.overview', array(
        	'title' => 'Debiteuren',
        	'debtors' => $aDebtors
        ));
	})->before('auth');

	Route::get('billing/debtors/add', function() {
		return View::make('billing.debtors.add', array(
			'title' => 'Debiteur toevoegen'
		));
	})->before('auth');
		Route::post('billing/debtors/add', array(
			'before' => 'auth',
			'uses' => 'DebtorsController@add'
		));

	Route::get('billing/debtor/edit/{id}', function($id) {
		$debtor = Debtors::where('id','=',$id)->where('cid','=',Auth::user()->cid)->first();

		return View::make('billing.debtors.edit', array(
			'title' => 'Factuur bewerken',
			'd' => $debtor
		));
	})->before('auth');
		Route::post('billing/debtor/edit/{id}', 'DebtorsController@edit')->before('auth');

	Route::get('billing/debtor/delete/{id}', function($id) {
		$debtor = new Debtors();
		$d = $debtor->find($id);
		return View::make('billing.debtors.delete', array(
			'title' => 'Factuur verwijderen',
			'd' => $d
		));
	})->before('auth');
		Route::post('billing/debtor/delete/{id}', 'DebtorsController@delete')->before('auth');

/*
 * ======================
 *  Invoices
 * ======================
 */

	Route::get('billing/invoices', array('as' => 'invoices', function() {
		$invoices = new Invoices();
		$aInvoices = $invoices->where('cid','=',Auth::user()->cid)->where('date', 'like', Session::get('year').'%')->orderBy('invoicenumber','ASC')->get();

        return View::make('billing.invoices.overview', array(
        	'title' => 'Facturen',
        	'invoices' => $aInvoices
        ));
	}))->before('auth');

	Route::get('billing/invoices/add', function() {
		return View::make('billing.invoices.add', array(
			'title' => 'Factuur aanmaken'
		));
	})->before('auth');
		Route::post('billing/invoices/add', array(
			'before' => 'auth',
			'uses' => 'InvoiceController@add'
		));

	Route::get('billing/invoice/edit/{id}', function($id) {
		$invoice = Invoices::where('id','=',$id)->where('cid','=',Auth::user()->cid)->first();

		return View::make('billing.invoices.edit', array(
			'title' => 'Factuur bewerken',
			'i' => $invoice
		));
	})->before('auth');
		Route::post('billing/invoice/edit/{id}', 'InvoiceController@edit')->before('auth');

	Route::get('billing/invoice/delete/{id}', function($id) {
		$invoice = new Invoices();
		$i = $invoice->find($id);
		return View::make('billing.invoices.delete', array(
			'title' => 'Factuur verwijderen',
			'i' => $i
		));
	})->before('auth');
		Route::post('billing/invoice/delete/{id}', 'InvoiceController@delete')->before('auth');

	Route::get('billing/invoice/paid/{id}', function($id) {
		$invoice = Invoices::where('cid','=',Auth::user()->cid)->where('id','=',$id)->first();
		$invoice->status = 10;
		$invoice->save();

		Alert::success('Betaling opgeslagen')->flash();
		return Redirect::to('/billing/invoices');
	})->before('auth');

	Route::get('billing/invoice/send/{id}', function($id) {
		$m = Layouts::getMail();
		if ($m->count()) {

			$i = Invoices::getId($id);
			if (Debtors::where('id','=',$i->did)->count() <= 0) {
				Alert::error('De debiteur bestaat niet meer.')->flash();
				return Redirect::back();
			}

			return View::make('billing.invoices.send', array(
				'title' => 'Factuur versturen',
				'i' => $i
			));
		}else{
			Alert::error('U hebt nog geen mail layouts aangemaakt!')->flash();
			return Redirect::back();
		}
	})->before('auth');
		Route::post('billing/invoice/send/{id}', 'InvoiceController@send')->before('auth');


/*
 * ======================
 *  Products
 * ======================
 */

	Route::get('billing/products', function() {
		$products = new Products();
		$aProducts = $products->where('cid','=',Auth::user()->cid)->get();

        return View::make('billing.products.overview', array(
        	'title' => 'Artikelen &amp; Diensten',
        	'products' => $aProducts
        ));
	})->before('auth');

	Route::get('billing/product/add', function() {
		return View::make('billing.products.add', array(
			'title' => 'Artikel aanmaken'
		));
	})->before('auth');
		Route::post('billing/product/add', array(
			'before' => 'auth',
			'uses' => 'ProductsController@add'
		));

	Route::get('billing/product/edit/{id}', function($id) {
		$product = Products::byID($id)->first();

		return View::make('billing.products.edit', array(
			'title' => 'Artikel bewerken',
			'product' => $product
		));
	})->before('auth');
		Route::post('billing/product/edit/{id}', array(
			'before' => 'auth',
			'uses' => 'ProductsController@edit'
		));

	Route::get('billing/product/delete/{id}', function($id) {
		$p = Products::byID($id)->first();
		return View::make('billing.products.delete', array(
			'title' => 'Artikel verwijderen',
			'p' => $p
		));
	})->before('auth');
		Route::post('billing/product/delete/{id}', 'ProductsController@delete')->before('auth');



/*
 * ======================
 *  Settings
 * ======================
 */

	Route::get('billing/settings/invoices', array('as'=>'settingsInvoices', function() {
		return View::make('billing.settings.invoice.overview', array(
			'title' => 'Factuur layouts'
		));
	}))->before('auth');

		Route::get('billing/settings/invoice/add', function() {
			return View::make('billing.settings.invoice.add', array(
				'title' => 'Factuur layout toevoegen'
			));
		})->before('auth');
			Route::post('billing/settings/invoice/add', 'SettingsController@saveInvoices')->before('auth');

		Route::get('billing/settings/invoice/edit/{id}', function($id) {
			$invoice = Layouts::getInvoice($id)->first();

			return View::make('billing.settings.invoice.edit', array(
				'title' => 'Factuur layout bewerken',
				'layout' => $invoice
			));
		})->before('auth');
			Route::post('billing/settings/invoice/edit/{id}', 'SettingsController@saveInvoices')->before('auth');

		Route::get('billing/settings/invoice/delete/{id}', function($id) {
			$invoice = Layouts::getInvoice($id)->first();

			return View::make('billing.settings.invoice.delete', array(
				'title' => 'Factuur layout verwijderen',
				'layout' => $invoice
			));
		})->before('auth');
			Route::post('billing/settings/invoice/delete/{id}', 'SettingsController@deleteInvoice')->before('auth');


	Route::get('billing/settings/mails', array('as'=>'settingsMails', function() {
		return View::make('billing.settings.mail.overview', array(
			'title' => 'Mail layouts'
		));
	}))->before('auth');

		Route::get('billing/settings/mail/add', function() {
			return View::make('billing.settings.mail.add', array(
				'title' => 'Mail layout toevoegen'
			));
		})->before('auth');
			Route::post('billing/settings/mail/add', 'SettingsController@saveMails')->before('auth');

		Route::get('billing/settings/mail/edit/{id}', function($id) {
			$invoice = Layouts::getMail($id)->first();

			return View::make('billing.settings.mail.edit', array(
				'title' => 'Factuur layout bewerken',
				'layout' => $invoice
			));
		})->before('auth');
			Route::post('billing/settings/mail/edit/{id}', 'SettingsController@saveMails')->before('auth');

		Route::get('billing/settings/mail/delete/{id}', function($id) {
			$invoice = Layouts::getMail($id)->first();

			return View::make('billing.settings.mail.delete', array(
				'title' => 'Factuur layout verwijderen',
				'layout' => $invoice
			));
		})->before('auth');
			Route::post('billing/settings/mail/delete/{id}', 'SettingsController@deleteMail')->before('auth');


	Route::get('billing/settings/port',function() {
		return View::make('billing.settings.port.index');
	})->before('auth');
		Route::get('billing/settings/port/exportdebtors', 'SettingsController@genDebtorsExport')->before('auth');
		Route::get('billing/settings/port/exportbilling', 'SettingsController@genBillingExport')->before('auth');


/*
 * ======================
 *  Ajax functions
 * ======================
 */

	Route::get('billing/getdebtor/{did}', function($did) {
		$d = new Debtors();
		$debtor = $d->where('id','=',$did)->first();

		$html = '
			<span class="semi-bold">'.$debtor->name.'</span><br />
			'.$debtor->address.'<br />
			'.$debtor->zipcode.' &nbsp; '.$debtor->city.'<br />
			'.$debtor->email.'
		';

		return $html;
	})->before('auth');

	Route::get('billing/getproduct/{pid}', function($pid) {
		$product = Products::byID($pid);
		if ($product->count() > 0) {

			$p = $product->first();
			$array = array(
				'description' => $p->description,
				'price' => $p->price,
				'tax' => $p->tax
			);

		}else {
			$array = array(
				'description' => 'not found',
				'price' => '',
				'tax' => ''
			);
		}

		echo json_encode($array, JSON_PRETTY_PRINT);
	})->before('auth');



/*
 * ======================
 *  search
 * ======================
 */

Route::any('billing/search', array('uses' => 'InvoiceController@searchFiles'));

/*
 * ======================
 *  Standalone
 * ======================
 */


Route::get('billing/viewmail/{id}', function($id) {
	return View::make('emails.invoice', array(
		'id' => $id
	));
})->before('auth');

Route::get('billing/viewpdf/{id}', function($id) {
	return View::make('billing.pdf', array(
		'id' => $id
	));
})->before('auth');

Route::get('billing/checkinvoicenumber/{id}', function($id) {
	$check = Invoices::where('invoicenumber', '=', $id)->where('cid', '=', Auth::user()->cid);
	echo $check->count();
})->before('auth');


Route::get('billing/pdf/{action}/{id}', function($action,$id) {
		$i = Invoices::where('id','=',$id)->where('cid','=',Auth::user()->cid)->first();
		$param['id'] = $i->id;
		$param['lid'] = $i->layout;
		$param['cid'] = $i->cid;
		$param['uid'] = $i->uid;
		$param['did'] = $i->did;
		$param['invoicenumber'] = $i->invoicenumber;
		$param['reference'] = $i->reference;
		$param['date'] = $i->date;
		$param['status'] = $i->status;

		$d = Debtors::where('id','=',$i->did);
		if ($d->count() > 0) {
			$d = $d->first();
			$param['toName'] = $d->name;
			$param['toContact'] = $d->contact;
			$param['toAddress'] = $d->address;
			$param['toZipcode'] = $d->zipcode;
			$param['toCity'] = $d->city;
			$param['toNumber'] = $d->debnumber;
		}else {
			$param['toName'] = 'Onbekend';
			$param['toContact'] = '';
			$param['toAddress'] = '';
			$param['toZipcode'] = '';
			$param['toCity'] = '';
			$param['toNumber'] = '';
		}

		$layout = Layouts::where('cid','=',Auth::user()->cid)->where('id','=',$i->layout);
		if ($layout->count() <= 0) {
			$param['background'] = '';
		}else{
			$lParams = $layout->first()->params;
			$lParams = unserialize($lParams);
			$param['background'] = $lParams['background'];
		}

		define("DOMPDF_ENABLE_PHP", true);
		define("DOMPDF_ENABLE_HTML5PARSER", true);
		$pdf = PDF::loadView('billing.pdf', $param)->setPaper('a4'); 
		//$pdf->set_option('isHtml5ParserEnabled', true);

		if ($action == 'view') {
        	return $pdf->stream($i->invoicenumber.'.pdf');
        }else{
        	return $pdf->download($i->invoicenumber.'.pdf');
        }

	})->before('auth');

?>