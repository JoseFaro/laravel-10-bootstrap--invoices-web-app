<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-md">
            <h2>Servicios</h2> <br>

            <ul class="nav app-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('services.create') }}">
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
                                @include('partials.th-sort-icon', [
                                    'default_ordering' => 'asc',
                                    'field' => 'name',
                                    'is_default' => true,
                                    'label' => 'Nombre',
                                ])
                            </th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($services as $service)
                            <tr>
                                <td>{{ $service->id }}</td>
                                <td>{{ $service->name }}</td>
                                <td>
                                    <a href="{{ route('services.edit', [$service->id]) }}" class="link-primary">Editar</a>
                                </td>
                                <td>
                                    <a href="{{ route('services.destroy', [$service->id]) }}" 
                                        class="link-primary app-confirm-delete"
                                        >
                                        Eliminar
                                    </a>

                                    <form id="delete-form-{{ $service->id }}" action="{{ route('services.destroy', [$service]) }}" method="POST" style="display: none;">
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
                {{ $services->links() }}
            </div>
        </div>
    </div>

</x-app-layout>