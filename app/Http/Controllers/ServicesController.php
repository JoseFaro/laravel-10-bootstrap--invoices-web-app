<?php

namespace App\Http\Controllers;

use App\Helpers\ServiceHelper;
use App\Models\Service;
use Auth;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('name', 'asc')->paginate(config('constants.pagination'));

        return view('services.index', ['services' => $services]);
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $request->session()->flash('form_fail_store', '1');

        $validator = $request->validate(ServiceHelper::getOnCreateValidations());

        $service = new Service();
        $service->fill($request->all());

        $service->company_id = Auth::user()->company_id;
        $service->save();

        $request->session()->forget('form_fail_store');
        $request->session()->flash('form_success_store', '1');

        return redirect()->route('services.index');
    }

    public function show(Service $service)
    {
    }

    public function edit(Service $service)
    {
        if (!session()->get('errors')) {
            session()->put('redirect_on_update_service', url()->previous());
        }

        return view('services.edit', ['service' => $service]);
    }

    public function update(Request $request, Service $service)
    {
        $request->session()->flash('form_fail_update', '1');

        $validator = $request->validate(ServiceHelper::getOnEditValidations());

        $service->fill($request->all());
        $service->save();

        $request->session()->forget('form_fail_update');
        $request->session()->flash('form_success_update', '1');

        return redirect(session()->get('redirect_on_update_service'));
    }

    public function destroy(Request $request, Service $service)
    {
        $service->delete();

        $request->session()->flash('form_success_delete', '1');

        return redirect()->back();
    }
}
