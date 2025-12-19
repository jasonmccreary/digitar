<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

class ProductsController extends Controller
{
    public function add(): RedirectResponse
    {
        $rules = [
            'name' => 'required',
            'productnumber' => 'required|alpha_num',
            'description' => 'required',
            'price' => 'required',
            'tax' => 'required|numeric',
            'ledger' => 'required|numeric',
        ];
        $messages = [
            'description.required' => 'Het veld "Omschrijving op factuur" is verplicht.',
            'price.required' => 'Het veld "Prijs excl. Btw" is verplicht.',
        ];

        $v = Validator::make(Request::all(), $rules, $messages);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect('/billing/product/add')->withInput();
        } else {
            $p = new Products;
            $p->cid = Auth::user()->cid;
            $p->ledger = Request::get('ledger');
            $p->productnumber = Request::get('productnumber');
            $p->name = Request::get('name');
            $p->description = Request::get('description');
            $p->price = priceToDB(Request::get('price'));
            $p->tax = Request::get('tax');
            $p->save();

            Alert::success('Product toegevoegd!')->flash();

            return redirect('/billing/products');
        }
    }

    public function edit($id): RedirectResponse
    {
        $rules = [
            'name' => 'required',
            'productnumber' => 'required|alpha_num',
            'description' => 'required',
            'price' => 'required',
            'tax' => 'required|numeric',
            'ledger' => 'required|numeric',
        ];
        $messages = [
            'description.required' => 'Het veld "Omschrijving op factuur" is verplicht.',
            'price.required' => 'Het veld "Prijs excl. Btw" is verplicht.',
        ];

        $v = Validator::make(Request::all(), $rules, $messages);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect('/billing/product/edit/'.$id)->withInput();
        } else {
            $p = Products::byID($id)->first();

            if (Products::numberExists(Request::get('productnumber'))->count() > 0 && $p->productnumber != Request::get('productnumber')) {
                Alert::error('Artikelnummer bestaat al!')->flash();

                return Redirect::back()->withInput(Request::except('productnumber'));
            }

            $p->ledger = Request::get('ledger');
            $p->productnumber = Request::get('productnumber');
            $p->name = Request::get('name');
            $p->description = Request::get('description');
            $p->price = priceToDB(Request::get('price'));
            $p->tax = Request::get('tax');

            $p->save();

            Alert::success('Artikel opgeslagen!')->flash();

            return redirect('/billing/products');
        }
    }

    public function delete($id): RedirectResponse
    {
        if (Request::get('delete') == 'true') {
            $p = Products::byID($id);
            $p->delete();

            Alert::success('Artikel succesvol verwijderd')->flash();
        }

        return redirect('/billing/products');
    }
}
