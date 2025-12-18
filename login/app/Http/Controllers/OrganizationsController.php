<?php

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

            return Redirect::to('/admin/organizations/add')->withInput();
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

            return Redirect::to('/admin/organizations');
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

        return Redirect::to('/admin/organizations');
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

            return Redirect::to('/admin/organization/edit/'.$id)->withInput();
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

            return Redirect::to('/admin/organizations');
        }
    }
}
