<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Site;
use App\Models\SiteService;
use Auth;
use Illuminate\Http\Request;

class SiteServicesController extends Controller
{
    public function index($site_id = '')
    {
        $site = null;

        $site = $site_id ? Site::find($site_id) : null;
        $site_services_query = SiteService::query();

        if ($site) {
            $site_services_query->with('service')->where('site_id', $site_id);
        } else {
            $site_services_query
                ->with(['site', 'service'])
                ->orderBy(
                    Site::select('name')
                        ->whereColumn('site_id', 'sites.id')
                        ->limit(1)
                );
        }

        $site_services = $site_services_query->orderBy(
            Service::select('name')
                ->whereColumn('service_id', 'services.id')
                ->limit(1)
        )
        ->paginate(config('constants.pagination'));

        return view('site-services.index', [
            'site' => $site,
            'site_id' => $site_id,
            'site_services' => $site_services
        ]);
    }

    public function create($site_id = '')
    {
        $sitesQuery = Site::orderBy('name', 'asc');
        $sitesQuery = $site_id ?  $sitesQuery->where('id', $site_id) : $sitesQuery;
        $sites = $sitesQuery->get();

        $services = Service::orderBy('name', 'asc')->get();

        return view('site-services.create', [
            'services' => $services,
            'sites' => $sites,
        ]);
    }

    public function store(Request $request)
    {
        $request->session()->flash('form_fail_store', '1');

        $validator = $request->validate([
            'cost' => 'required|numeric',
            'service_id' => 'required',
            'site_id' => 'required',
        ]);

        $site_service = new SiteService();
        $site_service->fill($request->all());

        $site_service->company_id = Auth::user()->company_id;
        $site_service->save();

        $request->session()->forget('form_fail_store');
        $request->session()->flash('form_success_store', '1');

        return redirect()->route('site-services.site', [$site_service->site_id]);
    }

    public function show(SiteService $site_service)
    {
        //
    }

    public function edit(SiteService $site_service)
    {
        $sites = Site::
                    orderBy('name', 'asc')
                    ->where('id', $site_service->site_id)
                    ->get();
        $services = Service::orderBy('name', 'asc')->get();

        if (!session()->get('errors')) {
            session()->put('redirect_on_update_site_service', url()->previous());
        }

        return view('site-services.edit', [
            'services' => $services,
            'sites' => $sites,
            'site_service' => $site_service,
        ]);
    }

    public function update(Request $request, SiteService $site_service)
    {
        $request->session()->flash('form_fail_update', '1');

        $validator = $request->validate([
            'cost' => 'required|numeric',
            'service_id' => 'required',
            'site_id' => 'required',
        ]);
        
        $site_service->fill($request->all());
        $site_service->save();

        $request->session()->forget('form_fail_update');
        $request->session()->flash('form_success_update', '1');

        return redirect(session()->get('redirect_on_update_site_service'));
    }

    public function destroy(Request $request, SiteService $site_service)
    {
        $site_service->delete();

        $request->session()->flash('form_success_delete', '1');

        return redirect()->back();
    }
    
    ///////////

    public function getDataBySite($site_id = '') {
        $preparedData = [];

        $site = Site::find($site_id);

        $site_services = $site ? SiteService::
                            where('site_id', $site->id)
                            ->orderBy(
                                Service::select('name')
                                    ->whereColumn('service_id', 'services.id')
                                    ->limit(1)
                            )->get() 
                        : [];

        foreach($site_services as $site_service) {
            $data = $site_service;
            
            $data->optionId = $site_service->id;
            $data->optionName = $site_service->service ? $site_service->service->name . ' / Precio sugerido ' . $site_service->cost : '';

            $preparedData[] = $data;
        }

        return $preparedData;
    }
}
