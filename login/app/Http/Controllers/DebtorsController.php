<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Debtors;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

class DebtorsController extends Controller
{
    public function add(): RedirectResponse
    {
        $rules = [
            'debnumber' => 'alpha_space',
            'name' => 'required',
            'address' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'country' => 'required',
            'email' => 'required',
            'payterm' => 'numeric',
        ];

        $v = Validator::make(Request::all(), $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return Redirect::back()->withInput();
        } else {
            $d = new Debtors;

            $check = $d->where('debnumber', '=', Request::get('debnumber'))->where('cid', '=', Auth::user()->cid);
            if ($check->count() > 0) {
                Alert::error('Dit dibiteur nummer bestaat al.')->flash();

                return Redirect::back()->withInput();
            }

            $d->debnumber = Request::get('debnumber');
            $d->cid = Auth::user()->cid;
            $d->uid = Auth::user()->id;
            $d->name = Request::get('name');
            $d->address = Request::get('address');
            $d->zipcode = Request::get('zipcode');
            $d->city = Request::get('city');
            $d->country = Request::get('country');
            $d->contact = Request::get('contact');
            $d->phone = Request::get('phone');
            $d->mobile = Request::get('mobile');
            $d->email = Request::get('email');
            $d->website = Request::get('website');
            $d->kvknr = Request::get('kvknr');
            $d->btwnr = Request::get('btwnr');
            $d->payterm = Request::get('payterm');

            $d->save();

            Alert::success('Debiteur toegevoegd')->flash();

            return redirect('/billing/debtors');
        }
    }

    public function edit($id): RedirectResponse
    {
        $rules = [
            'debnumber' => 'alpha_space',
            'name' => 'required',
            'address' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'country' => 'required',
            'email' => 'required',
            'payterm' => 'numeric',
        ];

        $v = Validator::make(Request::all(), $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return Redirect::back()->withInput();
        } else {
            $debtor = new Debtors;

            $check = $debtor->where('debnumber', '=', Request::get('debnumber'))->where('cid', '=', Auth::user()->cid);
            if ($check->count() > 0 && $id != $check->first()->id) {
                Alert::error('Dit dibiteur nummer bestaat al.')->flash();

                return Redirect::back()->withInput();
            }

            $d = $debtor->find($id);

            $d->debnumber = Request::get('debnumber');
            $d->uid = Auth::user()->id;
            $d->name = Request::get('name');
            $d->address = Request::get('address');
            $d->zipcode = Request::get('zipcode');
            $d->city = Request::get('city');
            $d->country = Request::get('country');
            $d->contact = Request::get('contact');
            $d->phone = Request::get('phone');
            $d->mobile = Request::get('mobile');
            $d->email = Request::get('email');
            $d->website = Request::get('website');
            $d->kvknr = Request::get('kvknr');
            $d->btwnr = Request::get('btwnr');
            $d->payterm = Request::get('payterm');

            $d->save();

            Alert::success('Debiteur opgeslagen')->flash();

            return redirect('/billing/debtors');
        }
    }

    public function delete($id): RedirectResponse
    {
        if (Request::get('delete') == 'true') {
            $d = new Debtors;
            $debtor = $d->where('cid', '=', Auth::user()->cid)->where('id', '=', $id)->delete();

            Alert::success('Map succesvol verwijderd')->flash();
        }

        return redirect('/billing/debtors');
    }
}
