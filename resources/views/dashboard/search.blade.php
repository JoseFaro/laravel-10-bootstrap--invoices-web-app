<div class="app-search-container">
    <form action="{{ route('dashboard') }}" method="get" class="app-form">      
        <div class="row">
            <div class="col-auto mb-3">
                <label for="unitInput" class="form-label">Unidad</label>
                <select name="unit_id" id="unitInput" class="form-select form-select-lg">
                    <option value="">--</option>
                    
                    @foreach($units as $unit)
                        <option 
                            @selected($unit->id == app('request')->input('unit_id'))
                            value="{{ $unit->id }}"
                        >
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
                            @selected($client->id == app('request')->input('client_id'))
                            value="{{ $client->id }}"
                        >
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
                            @selected($site->id == app('request')->input('site_id'))
                            value="{{ $site->id }}"
                        >
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
                            @selected($service->id == app('request')->input('service_id'))
                            value="{{ $service->id }}"
                        >
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto mb-3">
                <label for="supplierInput" class="form-label">Proveedor</label>
                <select 
                    name="supplier_id" 
                    id="supplierInput" 
                    class="form-select form-select-lg"
                >
                    <option value="">--</option>
    
                    @foreach($suppliers as $supplier)
                        <option 
                            @selected($supplier->id == app('request')->input('supplier_id'))
                            value="{{ $supplier->id }}"
                        >
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto mb-3">
                <label for="expensesCategoryInput" class="form-label">Categoría de gasto</label>
                <select 
                    name="expenses_category_id" 
                    id="expensesCategoryInput" 
                    class="form-select form-select-lg"
                >
                    <option value="">--</option>
    
                    @foreach($expenses_categories as $expenses_category)
                        <option 
                            @selected($expenses_category->id == app('request')->input('expenses_category_id'))
                            value="{{ $expenses_category->id }}"
                        >
                            {{ $expenses_category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-auto mb-3">
                <label for="dateInput" class="form-label">Fecha inicial</label>
                <input type="text" name="start_date" class="form-control form-control-lg app-datepicker" id="dateInput" value="{{ app('request')->input('start_date') }}" autocomplete="off" />
            </div>
    
            <div class="col-auto mb-3">
                <label for="dateInput" class="form-label">Fecha final</label>
                <input type="text" name="end_date" class="form-control form-control-lg app-datepicker" id="dateInput" value="{{ app('request')->input('end_date') }}" autocomplete="off" />
            </div>

            <div class="col-auto mb-3">
                <label for="reportTypeInput" class="form-label">Tipo de reporte</label>
                <select name="report_type" id="reportTypeInput" class="form-select form-select-lg">    
                    <option value="monthly" @selected(app('request')->input('report_type') == 'monthly')>Mensual</option>
                    <option value="daily"  @selected(app('request')->input('report_type') == 'daily')>Diario</option>
                </select>
            </div>

            <div class="col-auto mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="1" name="billable" id="billableInput" @checked(app('request')->input('billable') && app('request')->input('billable') == 1) />
                    <label class="form-check-label" for="billableInput">
                        Facturable
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" value="1" name="billed" id="billedInput" @checked(app('request')->input('billed') && app('request')->input('billed') == 1) />
                    <label class="form-check-label" for="billedInput">
                        Facturado
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" value="1" name="paid" id="paidInput" @checked(app('request')->input('paid') && app('request')->input('paid') == 1) />
                    <label class="form-check-label" for="paidInput">
                        Pagado
                    </label>
                </div>
            </div>

            <div class="col-auto mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="2" name="billable" id="notBillableInput" @checked(app('request')->input('billable') && app('request')->input('billable') == 2) />
                    <label class="form-check-label" for="notBillableInput">
                        No facturable
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" value="2" name="billed" id="notBilledInput" @checked(app('request')->input('billed') && app('request')->input('billed') == 2) />
                    <label class="form-check-label" for="notBilledInput">
                        No facturado
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" value="2" name="paid" id="notPaidInput" @checked(app('request')->input('paid') && app('request')->input('paid') == 2) />
                    <label class="form-check-label" for="notPaidInput">
                        No pagado
                    </label>
                </div>
            </div>

            <div class="col-auto mb-3">
                <button type="submit" class="btn btn-primary btn-md app-filter-submit">Buscar</button>
            </div>

            <div class="col-auto mb-3">
                <a href="{{ route('dashboard', ['default']) }}" class="btn btn-primary btn-md app-filter-submit" value="Reset">Reset</a>
            </div>
        </div> 
    </form>
</div>