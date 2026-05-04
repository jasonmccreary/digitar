<?php

namespace App\Http\Controllers;

use App\Models\Organizations;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

class OrganizationsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function add(Request $request): RedirectResponse
    {
        $input = $request->all();

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

            return redirect()->to('/admin/organizations/add')->withInput();
        } else {
            $o = new Organizations;
            $o->name = $request->input('businessname');
            $o->address = $request->input('address');
            $o->zipcode = $request->input('zipcode');
            $o->city = $request->input('city');
            $o->tell = $request->input('tell');
            $o->email = $request->input('email');
            $o->website = $request->input('website');
            $o->save();

            Alert::success('De nieuwe organisatie is toegevoegd')->flash();

            return redirect()->to('/admin/organizations');
        }
    }

    /**
     * Delete the specified resource.
     */
    public function delete(Request $request, int $id): RedirectResponse
    {
        if ($request->input('delete') == 'true') {
            $organization = new Organizations;
            $o = $organization->find($id);
            $o->delete();

            Alert::success('Organizatie succesvol verwijderd')->flash();
        }

        return redirect()->to('/admin/organizations');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, int $id): RedirectResponse
    {
        $input = $request->all();

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
            $o->name = $request->input('businessname');
            $o->address = $request->input('address');
            $o->zipcode = $request->input('zipcode');
            $o->city = $request->input('city');
            $o->tell = $request->input('tell');
            $o->email = $request->input('email');
            $o->website = $request->input('website');
            $o->save();

            Alert::success('Organizatie opgeslagen')->flash();

            return redirect()->to('/admin/organizations');
        }
    }
}
