<?php

namespace App\Http\Controllers;

use App\Models\Debtors;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

class DebtorsController extends Controller
{
    public function add(Request $request): RedirectResponse
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

        $v = Validator::make($request->all(), $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect()->back()->withInput();
        } else {
            $d = new Debtors;

            $check = $d->where('debnumber', '=', $request->input('debnumber'))->where('cid', '=', $request->user()->cid);
            if ($check->count() > 0) {
                Alert::error('Dit dibiteur nummer bestaat al.')->flash();

                return redirect()->back()->withInput();
            }

            $d->debnumber = $request->input('debnumber');
            $d->cid = $request->user()->cid;
            $d->uid = $request->user()->id;
            $d->name = $request->input('name');
            $d->address = $request->input('address');
            $d->zipcode = $request->input('zipcode');
            $d->city = $request->input('city');
            $d->country = $request->input('country');
            $d->contact = $request->input('contact');
            $d->phone = $request->input('phone');
            $d->mobile = $request->input('mobile');
            $d->email = $request->input('email');
            $d->website = $request->input('website');
            $d->kvknr = $request->input('kvknr');
            $d->btwnr = $request->input('btwnr');
            $d->payterm = $request->input('payterm');

            $d->save();

            Alert::success('Debiteur toegevoegd')->flash();

            return redirect()->to('/billing/debtors');
        }
    }

    public function edit(Request $request, $id): RedirectResponse
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

        $v = Validator::make($request->all(), $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect()->back()->withInput();
        } else {
            $debtor = new Debtors;

            $check = $debtor->where('debnumber', '=', $request->input('debnumber'))->where('cid', '=', $request->user()->cid);
            if ($check->count() > 0 && $id != $check->first()->id) {
                Alert::error('Dit dibiteur nummer bestaat al.')->flash();

                return redirect()->back()->withInput();
            }

            $d = $debtor->find($id);

            $d->debnumber = $request->input('debnumber');
            $d->uid = $request->user()->id;
            $d->name = $request->input('name');
            $d->address = $request->input('address');
            $d->zipcode = $request->input('zipcode');
            $d->city = $request->input('city');
            $d->country = $request->input('country');
            $d->contact = $request->input('contact');
            $d->phone = $request->input('phone');
            $d->mobile = $request->input('mobile');
            $d->email = $request->input('email');
            $d->website = $request->input('website');
            $d->kvknr = $request->input('kvknr');
            $d->btwnr = $request->input('btwnr');
            $d->payterm = $request->input('payterm');

            $d->save();

            Alert::success('Debiteur opgeslagen')->flash();

            return redirect()->to('/billing/debtors');
        }
    }

    public function delete(Request $request, $id): RedirectResponse
    {
        if ($request->input('delete') == 'true') {
            $d = new Debtors;
            $debtor = $d->where('cid', '=', $request->user()->cid)->where('id', '=', $id)->delete();

            Alert::success('Map succesvol verwijderd')->flash();
        }

        return redirect()->to('/billing/debtors');
    }
}
