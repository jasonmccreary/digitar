<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

            return redirect()->to('/billing/product/add')->withInput();
        } else {
            $p = new Products;
            $p->cid = $request->user()->cid;
            $p->ledger = $request->input('ledger');
            $p->productnumber = $request->input('productnumber');
            $p->name = $request->input('name');
            $p->description = $request->input('description');
            $p->price = priceToDB($request->input('price'));
            $p->tax = $request->input('tax');
            $p->save();

            Alert::success('Product toegevoegd!')->flash();

            return redirect()->to('/billing/products');
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

            if (Products::numberExists($request->input('productnumber'))->count() > 0 && $p->productnumber != $request->input('productnumber')) {
                Alert::error('Artikelnummer bestaat al!')->flash();

                return redirect()->back()->withInput($request->except('productnumber'));
            }

            $p->ledger = $request->input('ledger');
            $p->productnumber = $request->input('productnumber');
            $p->name = $request->input('name');
            $p->description = $request->input('description');
            $p->price = priceToDB($request->input('price'));
            $p->tax = $request->input('tax');

            $p->save();

            Alert::success('Artikel opgeslagen!')->flash();

            return redirect()->to('/billing/products');
        }
    }

    public function delete(Request $request, $id): RedirectResponse
    {
        if ($request->input('delete') == 'true') {
            $p = Products::byID($id);
            $p->delete();

            Alert::success('Artikel succesvol verwijderd')->flash();
        }

        return redirect()->to('/billing/products');
    }
}
