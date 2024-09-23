<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Site;
use Auth;
use Illuminate\Http\Request;

class SitesController extends Controller
{
    public function index(Request $request)
    {
        $query = Site::with('client');
        
        // ordering section
        $orderBy = $request->orderBy;
        if($orderBy) {
            $orderDirection = $request->orderDirection == 'asc' ? 'asc' : 'desc';

            if ($orderBy == 'contact') {
                $query->orderBy('contact', $orderDirection);
            }
            
            if ($orderBy == 'name') {
                $query->orderBy('name', $orderDirection);
            }

            if ($orderBy == 'client_id') {
                $query->orderBy(
                    Client::select('name')
                        ->whereColumn('client_id', 'clients.id')
                        ->limit(1),
                    $orderDirection,
                )->orderBy('name', 'asc');
            }
        } 
        
        // second default ordering
        if (!$orderBy || $orderBy != 'contact') {
            $query->orderBy('contact', 'asc');
        }

        $sites = $query->paginate(config('constants.pagination'));

        return view('sites.index', ['sites' => $sites]);
    }

    public function create()
    {
        $clients = Client::orderBy('name', 'asc')->get();

        return view('sites.create', ['clients' => $clients]);
    }

    public function store(Request $request)
    {
        $request->session()->flash('form_fail_store', '1');

        $validator = $request->validate([
            'client_id' => 'required',
            'name' => 'required',
        ]);

        $site = new Site();
        $site->fill($request->all());

        $site->company_id = Auth::user()->company_id;
        $site->save();

        $request->session()->forget('form_fail_store');
        $request->session()->flash('form_success_store', '1');

        return redirect()->route('sites.index');
    }

    public function show(Site $site)
    {
        //
    }

    public function edit(Site $site)
    {
        $clients = Client::orderBy('name', 'asc')->get();

        if (!session()->get('errors')) {
            session()->put('redirect_on_update_site', url()->previous());
        }

        return view('sites.edit', [
            'clients' => $clients,
            'site' => $site,
        ]);
    }

    public function update(Request $request, Site $site)
    {
        $request->session()->flash('form_fail_update', '1');

        $validator = $request->validate([
            'client_id' => 'required',
            'name' => 'required',
        ]);
        
        $site->fill($request->all());
        $site->save();

        $request->session()->forget('form_fail_update');
        $request->session()->flash('form_success_update', '1');

        return redirect(session()->get('redirect_on_update_site'));
    }

    public function destroy(Request $request, Site $site)
    {
        $site->delete();

        $request->session()->flash('form_success_delete', '1');

        return redirect()->back();
    }

    ///////////

    public function getDataByClient($client_id = '') {
        $preparedData = [];

        $client = Client::find($client_id);

        $sites = $client ? Site::where('client_id', $client->id)->orderBy('name', 'asc')->get() : [];

        foreach($sites as $site) {
            $data = $site;
            
            $data->optionId = $site->id;
            $data->optionName = $site->name;

            $preparedData[] = $data;
        }

        return $preparedData;
    }
}
