<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Debtors;
use Illuminate\Support\Facades\Redirect;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;


class DebtorsController extends Controller
{
    public function add()
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

        $v = Validator::make(Input::all(), $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return Redirect::back()->withInput();
        } else {
            $d = new Debtors;

            $check = $d->where('debnumber', '=', Input::get('debnumber'))->where('cid', '=', Auth::user()->cid);
            if ($check->count() > 0) {
                Alert::error('Dit dibiteur nummer bestaat al.')->flash();

                return Redirect::back()->withInput();
            }

            $d->debnumber = Input::get('debnumber');
            $d->cid = Auth::user()->cid;
            $d->uid = Auth::user()->id;
            $d->name = Input::get('name');
            $d->address = Input::get('address');
            $d->zipcode = Input::get('zipcode');
            $d->city = Input::get('city');
            $d->country = Input::get('country');
            $d->contact = Input::get('contact');
            $d->phone = Input::get('phone');
            $d->mobile = Input::get('mobile');
            $d->email = Input::get('email');
            $d->website = Input::get('website');
            $d->kvknr = Input::get('kvknr');
            $d->btwnr = Input::get('btwnr');
            $d->payterm = Input::get('payterm');

            $d->save();

            Alert::success('Debiteur toegevoegd')->flash();

            return redirect('/billing/debtors');
        }
    }

    public function edit($id)
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

        $v = Validator::make(Input::all(), $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return Redirect::back()->withInput();
        } else {
            $debtor = new Debtors;

            $check = $debtor->where('debnumber', '=', Input::get('debnumber'))->where('cid', '=', Auth::user()->cid);
            if ($check->count() > 0 && $id != $check->first()->id) {
                Alert::error('Dit dibiteur nummer bestaat al.')->flash();

                return Redirect::back()->withInput();
            }

            $d = $debtor->find($id);

            $d->debnumber = Input::get('debnumber');
            $d->uid = Auth::user()->id;
            $d->name = Input::get('name');
            $d->address = Input::get('address');
            $d->zipcode = Input::get('zipcode');
            $d->city = Input::get('city');
            $d->country = Input::get('country');
            $d->contact = Input::get('contact');
            $d->phone = Input::get('phone');
            $d->mobile = Input::get('mobile');
            $d->email = Input::get('email');
            $d->website = Input::get('website');
            $d->kvknr = Input::get('kvknr');
            $d->btwnr = Input::get('btwnr');
            $d->payterm = Input::get('payterm');

            $d->save();

            Alert::success('Debiteur opgeslagen')->flash();

            return redirect('/billing/debtors');
        }
    }

    public function delete($id)
    {
        if (Input::get('delete') == 'true') {
            $d = new Debtors;
            $debtor = $d->where('cid', '=', Auth::user()->cid)->where('id', '=', $id)->delete();

            Alert::success('Map succesvol verwijderd')->flash();
        }

        return redirect('/billing/debtors');
    }
}
