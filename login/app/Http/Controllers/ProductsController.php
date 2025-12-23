<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

class ProductsController extends Controller
{
    public function add(Request $request): RedirectResponse
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

        $v = Validator::make($request->all(), $rules, $messages);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect('/billing/product/add')->withInput();
        } else {
            $p = new Products;
            $p->cid = $request->user()->cid;
            $p->ledger = $request->get('ledger');
            $p->productnumber = $request->get('productnumber');
            $p->name = $request->get('name');
            $p->description = $request->get('description');
            $p->price = priceToDB($request->get('price'));
            $p->tax = $request->get('tax');
            $p->save();

            Alert::success('Product toegevoegd!')->flash();

            return redirect('/billing/products');
        }
    }

    public function edit(Request $request, $id): RedirectResponse
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

        $v = Validator::make($request->all(), $rules, $messages);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect('/billing/product/edit/'.$id)->withInput();
        } else {
            $p = Products::byID($id)->first();

            if (Products::numberExists($request->get('productnumber'))->count() > 0 && $p->productnumber != $request->get('productnumber')) {
                Alert::error('Artikelnummer bestaat al!')->flash();

                return Redirect::back()->withInput($request->except('productnumber'));
            }

            $p->ledger = $request->get('ledger');
            $p->productnumber = $request->get('productnumber');
            $p->name = $request->get('name');
            $p->description = $request->get('description');
            $p->price = priceToDB($request->get('price'));
            $p->tax = $request->get('tax');

            $p->save();

            Alert::success('Artikel opgeslagen!')->flash();

            return redirect('/billing/products');
        }
    }

    public function delete(Request $request, $id): RedirectResponse
    {
        if ($request->get('delete') == 'true') {
            $p = Products::byID($id);
            $p->delete();

            Alert::success('Artikel succesvol verwijderd')->flash();
        }

        return redirect('/billing/products');
    }
}
