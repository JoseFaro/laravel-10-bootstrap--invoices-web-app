<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-md">
            <h2>Categorías de gastos: crear</h2> <br>
        
            <form action="{{ route('expenses-categories.store') }}" method="post" class="app-form">
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
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>