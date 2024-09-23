<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-md">
            <h2>Obras: editar</h2> <br>
        
            <form action="{{ route('sites.update', [$site->id]) }}" method="post" class="app-form">
                <input type="hidden" name="_method" value="PUT">
                @csrf

                <div class="mb-3">
                    <label for="nameInput" class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-control form-control-lg" id="nameInput" value="{{ old('name', $site->name) }}">
                    
                    @if ($errors->first('name'))
                        <div class="invalid-feedback app-invalid-input">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="clientInput" class="form-label">Cliente</label>
                    <select name="client_id" id="clientInput" class="form-select form-select-lg">
                        <option value="">--</option>
                        
                        @foreach($clients as $client)
                            <option 
                                {{ $client->id == old('client_id', $site->client_id) ? 'selected' : '' }}
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
                    <label for="addressInput" class="form-label">Dirección</label>
                    <textarea class="form-control" name="address" id="addressInput" rows="5">{{ old('address', $site->address) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="contactInput" class="form-label">Supervisor</label>
                    <input type="text" name="contact" class="form-control form-control-lg" id="contactInput" value="{{ old('contact', $site->contact) }}">
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>