<?php

namespace App\Http\Controllers;

use App\Organizations;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

class OrganizationsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function add()
    {
        $input = Input::all();

        $rules = [
            'businessname' => 'required',
            'address' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'tell' => 'required',
            'email' => 'required|email',
        ];

        $v = Validator::make($input, $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect('/admin/organizations/add')->withInput();
        } else {
            $o = new Organizations;
            $o->name = Input::get('businessname');
            $o->address = Input::get('address');
            $o->zipcode = Input::get('zipcode');
            $o->city = Input::get('city');
            $o->tell = Input::get('tell');
            $o->email = Input::get('email');
            $o->website = Input::get('website');
            $o->save();

            Alert::success('De nieuwe organisatie is toegevoegd')->flash();

            return redirect('/admin/organizations');
        }
    }

    /**
     * Delete the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($id)
    {
        if (Input::get('delete') == 'true') {
            $organization = new Organizations;
            $o = $organization->find($id);
            $o->delete();

            Alert::success('Organizatie succesvol verwijderd')->flash();
        }

        return redirect('/admin/organizations');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $input = Input::all();

        $rules = [
            'businessname' => 'required',
            'address' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'tell' => 'required',
            'email' => 'required|email',
        ];

        $v = Validator::make($input, $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect('/admin/organization/edit/'.$id)->withInput();
        } else {
            $organization = new Organizations;
            $o = $organization->find($id);
            $o->name = Input::get('businessname');
            $o->address = Input::get('address');
            $o->zipcode = Input::get('zipcode');
            $o->city = Input::get('city');
            $o->tell = Input::get('tell');
            $o->email = Input::get('email');
            $o->website = Input::get('website');
            $o->save();

            Alert::success('Organizatie opgeslagen')->flash();

            return redirect('/admin/organizations');
        }
    }
}
