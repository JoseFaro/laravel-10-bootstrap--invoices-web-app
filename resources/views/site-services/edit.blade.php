<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-md">
            <h2>Obras: editar</h2> <br>
        
            <form action="{{ route('site-services.update', [$site_service->id]) }}" method="post" class="app-form">
                <input type="hidden" name="_method" value="PUT">
                @csrf

                <div class="mb-3">
                    <label for="siteInput" class="form-label">Obra</label>
                    <select name="site_id" id="siteInput" class="form-select form-select-lg">
                        <option value="">--</option>
                        
                        @foreach($sites as $site)
                            <option 
                                {{ $site->id == old('site_id', $site_service->site_id) ? 'selected' : '' }}
                                value="{{ $site->id }}">
                                {{ $site->name }}
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->first('site_id'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('site_id') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="serviceInput" class="form-label">Servicio</label>
                    <select name="service_id" id="serviceInput" class="form-select form-select-lg">
                        <option value="">--</option>
                        
                        @foreach($services as $service)
                            <option 
                                {{ $service->id == old('service_id', $site_service->service_id) ? 'selected' : '' }}
                                value="{{ $service->id }}">
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->first('service_id'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('service_id') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="costInput" class="form-label">Costo</label>
                    <input type="text" name="cost" class="form-control form-control-lg" id="costInput" value="{{ old('cost', $site_service->cost) }}">
                    
                    @if ($errors->first('cost'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('cost') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>