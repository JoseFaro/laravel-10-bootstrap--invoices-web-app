<div class="app-search-container">
    <form action="{{ route('activities.search') }}" method="get" class="app-form">      
        <div class="row">
            <div class="col-auto mb-3">
                <label for="searchInput" class="form-label">Buscar</label>
                <input type="text" name="search" class="form-control form-control-lg" id="searchInput" value="{{ app('request')->input('search') }}">
            </div>
    
            <div class="col-auto mb-3">
                <label for="unitInput" class="form-label">Unidad</label>
                <select name="unit_id" id="unitInput" class="form-select form-select-lg">
                    <option value="">--</option>
                    
                    @foreach($units as $unit)
                        <option 
                            {{ $unit->id == app('request')->input('unit_id') ? 'selected' : '' }}
                            value="{{ $unit->id }}">
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <div class="col-auto mb-3">
                <label for="clientInput" class="form-label">Cliente</label>
                <select 
                    name="client_id" 
                    id="clientInput" 
                    class="form-select form-select-lg app-select-trigger"
                    data-route-placeholder={{ route('sites.getDataByClient') }}
                    data-target-select='.app-site-select'
                >
                    <option value="">--</option>
                    
                    @foreach($clients as $client)
                        <option 
                            {{ $client->id == app('request')->input('client_id') ? 'selected' : '' }}
                            value="{{ $client->id }}">
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <div class="col-auto mb-3">
                <label for="siteInput" class="form-label">Obra</label>
                <select 
                    name="site_id" 
                    id="siteInput" 
                    class="form-select form-select-lg app-site-select"
                >
                    <option value="">--</option>
                        
                    @foreach($sites as $site)
                        <option 
                            {{ $site->id == app('request')->input('site_id') ? 'selected' : '' }}
                            value="{{ $site->id }}">
                            {{ $site->name }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <div class="col-auto mb-3">
                <label for="serviceInput" class="form-label">Servicio</label>
                <select 
                    name="service_id" 
                    id="serviceInput" 
                    class="form-select form-select-lg"
                    >
                    <option value="">--</option>
    
                    @foreach($services as $service)
                        <option 
                            {{ $service->id == app('request')->input('service_id') ? 'selected' : '' }}
                            value="{{ $service->id }}">
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <div class="col-auto mb-3">
                <label for="dateInput" class="form-label">Fecha inicial</label>
                <input type="text" name="start_date" class="form-control form-control-lg app-datepicker" id="dateInput" value="{{ app('request')->input('start_date') }}" autocomplete="off">
            </div>
    
            <div class="col-auto mb-3">
                <label for="dateInput" class="form-label">Fecha final</label>
                <input type="text" name="end_date" class="form-control form-control-lg app-datepicker" id="dateInput" value="{{ app('request')->input('end_date') }}" autocomplete="off">
            </div>

            <div class="col-auto mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="1" name="billable" id="billableInput" {{ app('request')->input('billable') && app('request')->input('billable') == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="billableInput">
                        Facturable
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" value="1" name="billed" id="billedInput" {{ app('request')->input('billed') && app('request')->input('billed') == 1  ? 'checked' : '' }}>
                    <label class="form-check-label" for="billedInput">
                        Facturado
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" value="1" name="paid" id="paidInput" {{ app('request')->input('paid') && app('request')->input('paid') == 1  ? 'checked' : '' }}>
                    <label class="form-check-label" for="paidInput">
                        Pagado
                    </label>
                </div>
            </div>

            <div class="col-auto mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="2" name="billable" id="notBillableInput" {{ app('request')->input('billable') && app('request')->input('billable') == 2 ? 'checked' : '' }}>
                    <label class="form-check-label" for="notBillableInput">
                        No facturable
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" value="2" name="billed" id="notBilledInput" {{ app('request')->input('billed') && app('request')->input('billed') == 2 ? 'checked' : '' }}>
                    <label class="form-check-label" for="notBilledInput">
                        No facturado
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" value="2" name="paid" id="notPaidInput" {{ app('request')->input('paid') && app('request')->input('paid') == 2 ? 'checked' : '' }}>
                    <label class="form-check-label" for="notPaidInput">
                        No pagado
                    </label>
                </div>
            </div>

            <div class="col-auto mb-3">
                <button type="submit" class="btn btn-primary btn-md app-filter-submit">Buscar</button>
            </div>

            <div class="col-auto mb-3">
                <a href="{{ route('activities.index') }}" class="btn btn-primary btn-md app-filter-submit" value="Reset">Reset</a>
            </div>
        </div> 
    </form>
</div>