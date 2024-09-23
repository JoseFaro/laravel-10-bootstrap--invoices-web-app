<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-md">
            <h2>Unidades</h2> <br>

            <ul class="nav app-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('units.create') }}">
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
                            <th>Ratio de consumo</th>
                            <th>Gastos</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($units as $unit)
                            <tr>
                                <td>{{ $unit->id }}</td>
                                <td>{{ $unit->name }}</td>
                                <td>{{ $unit->consume_ratio }}</td>
                                <td class="app-text-right">
                                    <form 
                                        action="{{ route('expenses.search') }}" 
                                        class="row"
                                        method="get" >

                                        <input type="hidden" name="unit_id" value="{{ $unit->id }}" />

                                        <div class="col-auto">
                                            <select name="expenses_category_id" id="expensesCategoryInput" class="form-select form-select-sm">
                                                <option value="">Todas las categorías</option>
                                                
                                                @foreach($expenses_categories as $expenses_category)
                                                    <option value="{{ $expenses_category->id }}">
                                                        {{ $expenses_category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('units.edit', [$unit->id]) }}" class="link-primary">Editar</a>
                                </td>
                                <td>
                                    <a href="{{ route('units.destroy', [$unit->id]) }}" 
                                        class="link-primary app-confirm-delete"
                                        >
                                        Eliminar
                                    </a>

                                    <form id="delete-form-{{ $unit->id }}" action="{{ route('units.destroy', [$unit]) }}" method="POST" style="display: none;">
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
                {{ $units->links() }}
            </div>
        </div>
    </div>

</x-app-layout>