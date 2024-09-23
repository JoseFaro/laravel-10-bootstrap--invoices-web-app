<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ServiceHelper;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
{
    public function index()
    {
        return ['services' => Service::orderBy('name', 'asc')->paginate(config('constants.app.pagination'))];
    }

    public function get(Request $request)
    {
        return ['service' => Service::find($request->id)];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ServiceHelper::getOnCreateValidations());
        if( $validator->fails() ) {
            return ['errors' => $validator->errors()];
        }

        $service = new Service();
        $service->fill($request->all());
        $service->save();

        return $service;
    }

    public function update(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), ServiceHelper::getOnEditValidations());
        if( $validator->fails() ) {
            return ['errors' => $validator->errors()];
        }

        $service->fill($request->all());
        $service->save();

        return $service;
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return $service;
    }
}
