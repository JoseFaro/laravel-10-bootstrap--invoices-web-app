<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-md">
            <h2>Clientes</h2> <br>

            <ul class="nav app-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clients.create') }}">
                        Crear
                    </a>
                </li>
            </ul>
            
            <div class="table-responsive">
                <table class="table table-hover app-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>
                                @include('partials.th-sort', [
                                    'default_ordering' => 'asc',
                                    'field' => 'name',
                                    'is_default' => true,
                                    'label' => 'Nombre',
                                    'route_name' => 'clients.index',
                                ])
                            </th>
                            <th>Contactos</th>
                            <th>Tipo de pago</th>
                            <th>Tipo de factura</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($clients as $client)
                            <tr>
                                <td>{{ $client->id }}</td>
                                <td>{{ $client->name }}</td>
                                <td>{!! nl2br($client->contacts) !!}</td>
                                <td>{{ $client->payment_type }}</td>
                                <td>{{ $client->invoice_type }}</td>
                                <td>
                                    <a href="{{ route('clients.edit', [$client->id]) }}" class="link-primary">Editar</a>
                                </td>
                                <td>
                                    <a href="{{ route('clients.destroy', [$client->id]) }}" 
                                        class="link-primary app-confirm-delete"
                                        >
                                        Eliminar
                                    </a>

                                    <form id="delete-form-{{ $client->id }}" action="{{ route('clients.destroy', [$client]) }}" method="POST" style="display: none;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        @csrf
                                </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="app-pagination">
                {{ $clients->links() }}
            </div>
        </div>
    </div>

</x-app-layout>