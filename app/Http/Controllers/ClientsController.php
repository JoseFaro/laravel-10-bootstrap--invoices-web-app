<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Auth;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();
        
        // ordering section
        $orderBy = $request->orderBy;
        if($orderBy) {
            $orderDirection = $request->orderDirection == 'asc' ? 'asc' : 'desc';
            
            $orderFieldsAvailable = [
                'name',
            ];

            if (in_array($orderBy, $orderFieldsAvailable)) {
                $query->orderBy($orderBy, $orderDirection);
            }
        } 
        
        // second default ordering
        if (!$orderBy || $orderBy != 'name') {
            $query->orderBy('name', 'asc');
        }

        $clients = $query->paginate(config('constants.pagination'))->withQueryString();

        return view('clients.index', ['clients' => $clients]);
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->session()->flash('form_fail_store', '1');

        $validator = $request->validate([
            'name' => 'required',
        ]);

        $client = new Client();
        $client->fill($request->all());

        $client->company_id = Auth::user()->company_id;
        $client->save();

        $request->session()->forget('form_fail_store');
        $request->session()->flash('form_success_store', '1');

        return redirect()->route('clients.index');
    }

    public function show(Client $client)
    {
        //
    }

    public function edit(Client $client)
    {
        if (!session()->get('errors')) {
            session()->put('redirect_on_update_client', url()->previous());
        }

        return view('clients.edit', ['client' => $client]);
    }

    public function update(Request $request, Client $client)
    {
        $request->session()->flash('form_fail_update', '1');

        $validator = $request->validate([
            'name' => 'required',
        ]);
        
        $client->fill($request->all());
        $client->save();

        $request->session()->forget('form_fail_update');
        $request->session()->flash('form_success_update', '1');

        return redirect(session()->get('redirect_on_update_client'));
    }

    public function destroy(Request $request, Client $client)
    {
        $client->delete();

        $request->session()->flash('form_success_delete', '1');

        return redirect()->back();
    }
}
