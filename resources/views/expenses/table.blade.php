<div class="table-responsive">
    <table class="table table-hover app-table">
        <thead>
            <tr>
                <th>#</th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'desc',
                        'field' => 'date',
                        'is_default' => true,
                        'label' => 'Fecha',
                        'route_name' => 'expenses.search',
                    ])
                </th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'asc',
                        'field' => 'unit_id',
                        'label' => 'Unidad',
                        'route_name' => 'expenses.search',
                    ])
                </th>
                <th>Descripción</th>
                <th>Actividad</th>
                <th class="app-text-right">
                    @include('partials.th-sort', [
                        'default_ordering' => 'desc',
                        'field' => 'amount',
                        'label' => 'Gasto',
                        'route_name' => 'expenses.search',
                    ])
                </th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'asc',
                        'field' => 'supplier_id',
                        'label' => 'Proveedor',
                        'route_name' => 'expenses.search',
                    ])
                </th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'asc',
                        'field' => 'expenses_category_id',
                        'label' => 'Categoría',
                        'route_name' => 'expenses.search',
                    ])
                </th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'desc',
                        'field' => 'billed',
                        'label' => 'Facturado',
                        'route_name' => 'expenses.search',
                    ])
                </th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'desc',
                        'field' => 'created_at',
                        'label' => 'Creado',
                        'route_name' => 'expenses.search',
                    ])
                </th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'desc',
                        'field' => 'updated_at',
                        'label' => 'Actualizado',
                        'route_name' => 'expenses.search',
                    ])
                </th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>

        <tbody>
            @foreach($expenses as $expense)
                <tr>
                    <td>{{ $expense->id }}</td>
                    <td>{{ $expense->date }}</td>
                    <td>
                        @if ($expense->unit)
                            <a href="{{ route('units.edit', [$expense->unit->id]) }}" class="link-primary">
                                {{ $expense->unit->name }}
                            </a>
                        @endif
                    </td>
                    <td>{!! nl2br($expense->description) !!}</td>
                    <td>
                        @if ($expense->activity)
                            <a href="{{ route('activities.edit', [$expense->activity->id]) }}" class="link-primary">
                                #{{ $expense->activity->id }}
                                - {{ getProp($expense, 'activity->site_service->service->name') }} 
                            </a>
                        @endif
                    </td>
                    <td class="app-text-right">{{ number_format($expense->amount, 2) }}</td>
                    <td>
                        @if ($expense->supplier)
                            <a href="{{ route('suppliers.edit', [$expense->supplier->id]) }}" class="link-primary">
                                {{ $expense->supplier->name }}
                            </a>
                        @endif
                    </td>
                    <td>
                        @if ($expense->expenses_category)
                            <a href="{{ route('expenses-categories.edit', [$expense->expenses_category->id]) }}" class="link-primary">
                                {{ $expense->expenses_category->name }}
                            </a>
                        @endif
                    </td>
                    <td>
                        @include('partials.checkmark', ['checked' => !!$expense->billed])
                    </td>
                    <td>{{ $expense->created_at }}</td>
                    <td>{{ $expense->updated_at }}</td>
                    <td>
                        <a href="{{ route('expenses.edit', [$expense->id]) }}" class="link-primary">Editar</a>
                    </td>
                    <td>
                        <a href="{{ route('expenses.destroy', [$expense->id]) }}" 
                            class="link-primary app-confirm-delete"
                            >
                            Eliminar
                        </a>

                        <form id="delete-form-{{ $expense->id }}" action="{{ route('expenses.destroy', [$expense]) }}" method="POST" style="display: none;">
                            <input type="hidden" name="_method" value="DELETE">
                            @csrf
                    </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>