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
            $check = Debtors::where('debnumber', '=', $request->get('debnumber'))->where('cid', '=', $request->user()->cid);
            if ($check->count() > 0) {
                Alert::error('Dit dibiteur nummer bestaat al.')->flash();

                return redirect()->back()->withInput();
            }

            $d = new Debtors;
            $d->debnumber = $request->get('debnumber');
            $d->cid = $request->user()->cid;
            $d->uid = $request->user()->id;
            $d->name = $request->get('name');
            $d->address = $request->get('address');
            $d->zipcode = $request->get('zipcode');
            $d->city = $request->get('city');
            $d->country = $request->get('country');
            $d->contact = $request->get('contact');
            $d->phone = $request->get('phone');
            $d->mobile = $request->get('mobile');
            $d->email = $request->get('email');
            $d->website = $request->get('website');
            $d->kvknr = $request->get('kvknr');
            $d->btwnr = $request->get('btwnr');
            $d->payterm = $request->get('payterm');

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
            $check = Debtors::where('debnumber', '=', $request->get('debnumber'))->where('cid', '=', $request->user()->cid);
            if ($check->count() > 0 && $id != $check->first()->id) {
                Alert::error('Dit dibiteur nummer bestaat al.')->flash();

                return redirect()->back()->withInput();
            }

            $d = Debtors::find($id);

            $d->debnumber = $request->get('debnumber');
            $d->uid = $request->user()->id;
            $d->name = $request->get('name');
            $d->address = $request->get('address');
            $d->zipcode = $request->get('zipcode');
            $d->city = $request->get('city');
            $d->country = $request->get('country');
            $d->contact = $request->get('contact');
            $d->phone = $request->get('phone');
            $d->mobile = $request->get('mobile');
            $d->email = $request->get('email');
            $d->website = $request->get('website');
            $d->kvknr = $request->get('kvknr');
            $d->btwnr = $request->get('btwnr');
            $d->payterm = $request->get('payterm');

            $d->save();

            Alert::success('Debiteur opgeslagen')->flash();

            return redirect()->to('/billing/debtors');
        }
    }

    public function delete(Request $request, $id): RedirectResponse
    {
        if ($request->get('delete') == 'true') {
            Debtors::where('cid', '=', $request->user()->cid)->where('id', '=', $id)->delete();

            Alert::success('Map succesvol verwijderd')->flash();
        }

        return redirect()->to('/billing/debtors');
    }
}
