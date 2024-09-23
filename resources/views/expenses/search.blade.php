<div class="app-search-container">
    <form action="{{ route('expenses.search') }}" method="get" class="app-form">      
        <div class="row">
            <div class="col-auto mb-3">
                <label for="searchInput" class="form-label">Buscar</label>
                <input type="text" name="search" class="form-control form-control-lg" id="searchInput" value="{{ app('request')->input('search') }}">
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
                <label for="supplierInput" class="form-label">Proveedor</label>
                <select 
                    name="supplier_id" 
                    id="supplierInput" 
                    class="form-select form-select-lg"
                    >
                    <option value="">--</option>
                    
                    @foreach($suppliers as $supplier)
                        <option 
                            {{ $supplier->id == app('request')->input('supplier_id') ? 'selected' : '' }}
                            value="{{ $supplier->id }}">
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <div class="col-auto mb-3">
                <label for="expensesCategoryInput" class="form-label">Categoría</label>
                <select 
                    name="expenses_category_id" 
                    id="expensesCategoryInput" 
                    class="form-select form-select-lg"
                    >
                    <option value="">--</option>
                        
                    @foreach($expenses_categories as $expenses_category)
                        <option 
                            {{ $expenses_category->id == app('request')->input('expenses_category_id') ? 'selected' : '' }}
                            value="{{ $expenses_category->id }}">
                            {{ $expenses_category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="1" name="billed" id="billedInput" {{ app('request')->input('billed') && app('request')->input('billed') == 1  ? 'checked' : '' }}>
                    <label class="form-check-label" for="billedInput">
                        Facturado
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" value="2" name="billed" id="notBilledInput" {{ app('request')->input('billed') && app('request')->input('billed') == 2 ? 'checked' : '' }}>
                    <label class="form-check-label" for="notBilledInput">
                        No facturado
                    </label>
                </div>
            </div>

            <div class="col-auto mb-3">
                <button type="submit" class="btn btn-primary btn-md app-filter-submit">Buscar</button>
            </div>

            <div class="col-auto mb-3">
                <a href="{{ route('expenses.index') }}" class="btn btn-primary btn-md app-filter-submit" value="Reset">Reset</a>
            </div>
        </div> 
    </form>
</div>