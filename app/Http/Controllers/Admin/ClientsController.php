<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClientRequest;
use App\LoginModule\Profile\SchemaBuilder;
use App\LoginModule\AuthList;

class ClientsController extends Controller
{

    public function __construct(AuthList $auth_list) {
        $this->auth_list = $auth_list;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.clients.index', [
            'clients' => Client::get()
        ]);       //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $client = new Client([
            'secret' => str_random(40)
        ]);
        return view('admin.clients.form', [
            'client' => $client,
            'user_attributes' => SchemaBuilder::availableAttributes(),
            'auth_methods' => $this->auth_list->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {
        $client = new Client($request->all());
        $client->badge_autologin = $request->has('badge_autologin');
        $client->badge_required = $request->has('badge_required');
        $client->personal_access_client = false;
        $client->password_client = false;
        $auth_order = $request->has('auth_order') ? $request->get('auth_order') : [];
        $client->auth_order = $this->auth_list->normalize($auth_order);
        $client->save();
        return redirect()
            ->route('admin.clients.index')
            ->with('status', 'New client added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        return view('admin.clients.form', [
            'client' => $client,
            'user_attributes' => SchemaBuilder::availableAttributes(),
            'auth_methods' => $this->auth_list->normalize($client->auth_order)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(StoreClientRequest $request, Client $client)
    {
        //dd($request->all());
        $client->fill($request->all());
        $client->badge_autologin = $request->has('badge_autologin');
        $client->badge_required = $request->has('badge_required');
        $auth_order = $request->has('auth_order') ? $request->get('auth_order') : [];
        $client->auth_order = $this->auth_list->normalize($auth_order);
        $client->save();
        return redirect()
            ->route('admin.clients.index')
            ->with('status', 'Client updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()
            ->route('admin.clients.index')
            ->with('status', 'Client deleted.');
    }

}