<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-md">
            <h2>Clientes: crear</h2> <br>
        
            <form action="{{ route('clients.store') }}" method="post" class="app-form">
                @csrf

                <div class="mb-3">
                    <label for="nameInput" class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-control form-control-lg" id="nameInput" value="{{ old('name') }}">

                    @if ($errors->first('name'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="contactsInput" class="form-label">Contactos</label>
                    <textarea class="form-control" name="contacts" id="contactsInput" rows="5">{{ old('contacts') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="paymentTypeInput" class="form-label">Tipo de pago</label>
                    <input type="text" name="payment_type" class="form-control form-control-lg" id="paymentTypeInput" value="{{ old('payment_type') }}">
                </div>

                <div class="mb-3">
                    <label for="invoiceTypeInput" class="form-label">Tipo de factura</label>
                    <input type="text" name="invoice_type" class="form-control form-control-lg" id="invoiceTypeInput" value="{{ old('invoice_type') }}">
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>