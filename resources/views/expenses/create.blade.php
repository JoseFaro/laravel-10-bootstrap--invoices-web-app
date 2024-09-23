<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-md">
            <h2>Gastos: crear</h2> <br>
        
            <form action="{{ route('expenses.store') }}" method="post" class="app-form">
                @csrf
                
                <div class="mb-3">
                    <label for="dateInput" class="form-label">Fecha</label>
                    <input type="text" name="date" class="form-control form-control-lg app-datepicker" id="dateInput" value="{{ old('date') }}" autocomplete="off">
                
                    @if ($errors->first('date'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('date') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="unitInput" class="form-label">Unidad</label>
                    <select name="unit_id" id="unitInput" class="form-select form-select-lg">
                        <option value="">--</option>
                        
                        @foreach($units as $unit)
                            <option 
                                {{ $unit->id == old('unit_id') ? 'selected' : '' }}
                                value="{{ $unit->id }}">
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
                    <label for="supplierInput" class="form-label">Proveedor</label>
                    <select name="supplier_id" id="supplierInput" class="form-select form-select-lg">
                        <option value="">--</option>
                        
                        @foreach($suppliers as $supplier)
                            <option 
                                {{ $supplier->id == old('supplier_id') ? 'selected' : '' }}
                                value="{{ $supplier->id }}"
                            >
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->first('supplier_id'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('supplier_id') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="expensesCategoryInput" class="form-label">Categoría</label>
                    <select name="expenses_category_id" id="expensesCategoryInput" class="form-select form-select-lg">
                        <option value="">--</option>
                        
                        @foreach($expenses_categories as $expenses_category)
                            <option 
                                {{ $expenses_category->id == old('expenses_category_id') ? 'selected' : '' }}
                                value="{{ $expenses_category->id }}"
                            >
                                {{ $expenses_category->name }}
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->first('expenses_category_id'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('expenses_category_id') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="descriptionInput" class="form-label">Descripción</label>
                    <textarea class="form-control" name="description" id="descriptionInput" rows="5">{{ old('description') }}</textarea>
                
                    @if ($errors->first('description'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="amountInput" class="form-label">Monto (Gasto)</label>
                    <input type="text" name="amount" class="form-control form-control-lg" id="amountInput" value="{{ old('amount') }}">
                
                    @if ($errors->first('amount'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('amount') }}
                        </div>
                    @endif
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="billed" id="billedInput" {{ old('billed') ? 'checked' : '' }}>
                    <label class="form-check-label" for="billedInput">
                        Facturado
                    </label>
                </div>

                <div class="mb-3"></div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>