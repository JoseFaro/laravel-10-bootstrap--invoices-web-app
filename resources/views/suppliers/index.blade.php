<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-md">
            <h2>Proveedores</h2> <br>

            <ul class="nav app-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('suppliers.create') }}">
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
                        @foreach($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->id }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>
                                    <a href="{{ route('suppliers.edit', [$supplier->id]) }}" class="link-primary">Editar</a>
                                </td>
                                <td>
                                    <a href="{{ route('suppliers.destroy', [$supplier->id]) }}" 
                                        class="link-primary app-confirm-delete"
                                        >
                                        Eliminar
                                    </a>

                                    <form id="delete-form-{{ $supplier->id }}" action="{{ route('suppliers.destroy', [$supplier]) }}" method="POST" style="display: none;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        @csrf
                                </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>