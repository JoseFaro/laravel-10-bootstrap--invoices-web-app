<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-md">
            <h2>Facturas: crear</h2> <br>
        
            <form action="{{ route('invoices.store') }}" method="post" class="app-form">
                @csrf

                <div class="mb-3">
                    <label for="billCodeInput" class="form-label">Folio</label>
                    <input type="text" name="bill_code" class="form-control form-control-lg" id="billCodeInput" value="{{ old('bill_code') }}">

                    @if ($errors->first('bill_code'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('bill_code') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="invoiceClientInput" class="form-label">Cliente</label>
                    <select 
                        class="form-select form-select-lg"
                        data-route="{{ route('activities.getDataByClient') }}"
                        id="invoiceClientInput" 
                        name="client_id" 
                        >
                        <option value="">--</option>
                        
                        @foreach($clients as $client)
                            <option 
                                {{ $client->id == old('client_id') ? 'selected' : '' }}
                                value="{{ $client->id }}">
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
                    <label for="billedDateInput" class="form-label">Fecha de factura</label>
                    <input type="text" name="billed_date" class="form-control form-control-lg app-datepicker" id="billedDateInput" value="{{ old('billed_date') }}" autocomplete="off">

                    @if ($errors->first('billed_date'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('billed_date') }}
                        </div>
                    @endif
                </div>
                
                <div class="mb-3">
                    <label for="subtotalInput" class="form-label">Subtotal</label>
                    <input type="text" name="subtotal" class="form-control form-control-lg" id="subtotalInput" value="{{ old('subtotal') }}">
                    
                    @if ($errors->first('subtotal'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('subtotal') }}
                        </div>
                    @endif
                </div>


                <div class="mb-3">
                    <label for="ivaInput" class="form-label">IVA</label>
                    <input type="text" name="iva" class="form-control form-control-lg" id="ivaInput" value="{{ old('iva') }}">

                    @if ($errors->first('iva'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('iva') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="retainedIvaInput" class="form-label">IVA retenido</label>
                    <input type="text" name="retained_iva" class="form-control form-control-lg" id="retainedIvaInput" value="{{ old('retained_iva') }}">

                    @if ($errors->first('retained_iva'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('retained_iva') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="retainedIvaInput" class="form-label">ISR retenido</label>
                    <input type="text" name="isr" class="form-control form-control-lg" id="retainedIvaInput" value="{{ old('isr') }}">

                    @if ($errors->first('isr'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('isr') }}
                        </div>
                    @endif
                </div>

                <div class="mb-4">
                    <label for="totalInput" class="form-label">Total</label>
                    <input type="text" name="total" class="form-control form-control-lg" id="totalInput" value="{{ old('total') }}">

                    @if ($errors->first('total'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('total') }}
                        </div>
                    @endif
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" name="paid" id="paidInput" {{ old('paid') ? 'checked' : '' }}>
                    <label class="form-check-label" for="paidInput">
                        Pagado
                    </label>

                    @if ($errors->first('paid'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('paid') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="paymentDateInput" class="form-label">Fecha de pago</label>
                    <input type="text" name="payment_date" class="form-control form-control-lg app-datepicker" id="paymentDateInput" value="{{ old('payment_date') }}" autocomplete="off">

                    @if ($errors->first('payment_date'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('payment_date') }}
                        </div>
                    @endif
                </div>

                @include('invoices.activities-select')

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>