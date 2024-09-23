<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-md">
            <h2>Bitacora: editar</h2> <br>
        
            <form action="{{ route('activities.update', [$activity->id]) }}" method="post" class="app-form">
                <input type="hidden" name="_method" value="PUT">
                @csrf

                <div class="mb-3">
                    <label for="unitInput" class="form-label">Unidad</label>
                    <select name="unit_id" id="unitInput" class="form-select form-select-lg">
                        <option value="">--</option>
                        
                        @foreach($units as $unit)
                            <option 
                                value="{{ $unit->id }}"
                                @selected($unit->id == old('unit_id', $activity->unit_id))
                            >
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->first('unit_id'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('unit_id') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
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
                                value="{{ $client->id }}"
                                @selected($client->id == old('client_id', $activity->client_id))
                            >
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->first('client_id'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('client_id') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="siteInput" class="form-label">Obra</label>
                    <select 
                        name="site_id" 
                        id="siteInput" 
                        class="form-select form-select-lg app-site-select app-select-trigger"
                        data-route-placeholder={{ route('site-services.getDataBySite') }}
                        data-target-select='.app-service-select'
                        data-update-related-target-on-clear="1"
                        >
                        <option value="">--</option>
                        
                        @foreach($sites as $site)
                            <option 
                                value="{{ $site->id }}"
                                @selected($site->id == old('site_id', $activity->site_id))
                            >
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
                    <select 
                        name="site_service_id" 
                        id="serviceInput" 
                        class="form-select form-select-lg app-service-select"
                        >
                        <option value="">--</option>
                        
                        @foreach($site_services as $site_service)
                            <option 
                                value="{{ $site_service->id }}"
                                @selected($site_service->id == old('site_service_id', $activity->site_service_id))
                            >
                                {{ getProp($site_service, 'service->name') . ' / Precio sugerido ' . $site_service->cost }}
                            </option>
                        @endforeach
                    </select>
                    
                    @if ($errors->first('site_service_id'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('site_service_id') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="dateInput" class="form-label">Fecha</label>
                    <input type="text" name="date" class="form-control form-control-lg app-datepicker" id="dateInput" value="{{ old('date', $activity->date) }}" autocomplete="off">
                
                    @if ($errors->first('date'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('date') }}
                        </div>
                    @endif
                </div>

                <div class="form-check" id="billableCheckboxHandler">
                    <input type="hidden" name="billable" value="0">
                    <input 
                        class="form-check-input" 
                        id="billableInput" 
                        name="billable" 
                        type="checkbox" 
                        value="1" 
                        @disabled(!!$activity->invoice_activity)
                        @checked(old('billable', $activity->billable))
                    />
                    <label class="form-check-label" for="billableInput">
                        Facturable
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" @checked(old('billed', $activity->billed)) disabled>
                    <label class="form-check-label" for="billedInput">
                        Facturado
                    </label>
                </div>

                <div class="form-check" id="paidCheckboxHandler">
                    <input type="hidden" name="paid" value="0" />
                    <input 
                        class="form-check-input" 
                        id="paidInput" 
                        name="paid" 
                        type="checkbox" 
                        value="1" 
                        @checked(old('paid', $activity->paid))
                        @disabled(old('billable', $activity->billable))
                    />
                    <label class="form-check-label" for="paidInput">
                        Pagado
                    </label>

                    @if ($errors->first('paid'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('paid') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3"></div>

                <div class="mb-3" id="paymentDateInputHandler">
                    <label for="paymentDateInput" class="form-label">Fecha de pago</label>
                    <input 
                        type="text" 
                        name="payment_date" 
                        class="form-control form-control-lg app-datepicker" 
                        id="paymentDateInput" 
                        value="{{ old('payment_date', $activity->payment_date) }}" 
                        autocomplete="off"
                        @disabled(old('billable', $activity->billable))
                    />

                    @if ($errors->first('payment_date'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('payment_date') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="costInput" class="form-label">Costo</label>
                    <input type="text" name="cost" class="form-control form-control-lg" id="costInput" value="{{ old('cost', $activity->cost) }}">
                
                    @if ($errors->first('cost'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('cost') }}
                        </div>
                    @endif
                </div>

                @if (!$activity->expense)
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" name="create_expense" id="createExpenseInput" @checked(old('create_expense')) />
                        <label class="form-check-label" for="createExpenseInput">
                            Crear gasto
                        </label>
                    </div>
                @endif

                <div id="<?=($activity->expense) ? 'create-expense-container-opened' : 'create-expense-container' ?>" class="mb-3 mt-4">
                    <div class="mb-3">
                        <label for="supplierInput" class="form-label">Proveedor</label>
                        <select name="expense__supplier_id" id="supplierInput" class="form-select form-select-lg">
                            <option value="">--</option>
                            
                            @foreach($suppliers as $supplier)
                                <option 
                                    @selected($supplier->id == old('expense__supplier_id', getProp($activity, 'expense->supplier_id')))
                                    value="{{ $supplier->id }}"
                                >
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>

                        @if ($errors->first('expense__supplier_id'))
                            <div class="invalid-feedback app-invalid-input">
                                {{ $errors->first('expense__supplier_id') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="expensesCategoryInput" class="form-label">Categoría</label>
                        <select name="expense__expenses_category_id" id="expensesCategoryInput" class="form-select form-select-lg">
                            <option value="">--</option>
                            
                            @foreach($expenses_categories as $expenses_category)
                                <option 
                                    @selected($expenses_category->id == old('expense__expenses_category_id', getProp($activity, 'expense->expenses_category_id')))
                                    value="{{ $expenses_category->id }}"
                                >
                                    {{ $expenses_category->name }}
                                </option>
                            @endforeach
                        </select>

                        @if ($errors->first('expense__expenses_category_id'))
                            <div class="invalid-feedback app-invalid-input">
                                {{ $errors->first('expense__expenses_category_id') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="expenseDescriptionInput" class="form-label">Descripción</label>
                        <textarea class="form-control" name="expense__description" id="expenseDescriptionInput" rows="5">{{ old('expense__description', getProp($activity, 'expense->description')) }}</textarea>
                    
                        @if ($errors->first('expense__description'))
                            <div class="invalid-feedback app-invalid-input">
                                {{ $errors->first('expense__description') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="expensesAmountInput" class="form-label">Monto (Gasto)</label>
                        <input type="text" name="expense__amount" class="form-control form-control-lg" id="expensesAmountInput" value="{{ old('expense__amount', getProp($activity, 'expense->amount')) }}" />

                        @if ($errors->first('expense__amount'))
                            <div class="invalid-feedback app-invalid-input">
                                {{ $errors->first('expense__amount') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-check">
                        <input type="hidden" value="0" name="expense__billed" />
                        <input class="form-check-input" type="checkbox" value="1" name="expense__billed" id="expenseBilledInput" @checked(old('expense__billed', getProp($activity, 'expense->billed'))) />
                        <label class="form-check-label" for="expenseBilledInput">
                            Gasto facturado
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>