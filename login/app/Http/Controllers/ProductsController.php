<?php

class ProductsController extends Controller
{
    public function add()
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

        $v = Validator::make(Input::all(), $rules, $messages);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return Redirect::to('/billing/product/add')->withInput();
        } else {
            $p = new Products;
            $p->cid = Auth::user()->cid;
            $p->ledger = Input::get('ledger');
            $p->productnumber = Input::get('productnumber');
            $p->name = Input::get('name');
            $p->description = Input::get('description');
            $p->price = priceToDB(Input::get('price'));
            $p->tax = Input::get('tax');
            $p->save();

            Alert::success('Product toegevoegd!')->flash();

            return Redirect::to('/billing/products');
        }
    }

    public function edit($id)
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

        $v = Validator::make(Input::all(), $rules, $messages);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return Redirect::to('/billing/product/edit/'.$id)->withInput();
        } else {
            $p = Products::byID($id)->first();

            if (Products::numberExists(Input::get('productnumber'))->count() > 0 && $p->productnumber != Input::get('productnumber')) {
                Alert::error('Artikelnummer bestaat al!')->flash();

                return Redirect::back()->withInput(Input::except('productnumber'));
            }

            $p->ledger = Input::get('ledger');
            $p->productnumber = Input::get('productnumber');
            $p->name = Input::get('name');
            $p->description = Input::get('description');
            $p->price = priceToDB(Input::get('price'));
            $p->tax = Input::get('tax');

            $p->save();

            Alert::success('Artikel opgeslagen!')->flash();

            return Redirect::to('/billing/products');
        }
    }

    public function delete($id)
    {
        if (Input::get('delete') == 'true') {
            $p = Products::byID($id);
            $p->delete();

            Alert::success('Artikel succesvol verwijderd')->flash();
        }

        return Redirect::to('/billing/products');
    }
}
