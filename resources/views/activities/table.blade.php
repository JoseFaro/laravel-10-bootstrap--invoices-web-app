<div class="table-responsive">
    <table class="table table-hover app-table">
        <thead>
            <tr>
                <th>#</th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'asc',
                        'field' => 'unit_id',
                        'label' => 'Unidad',
                        'route_name' => 'activities.search',
                    ])
                </th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'asc',
                        'field' => 'client_id',
                        'label' => 'Cliente',
                        'route_name' => 'activities.search',
                    ])
                </th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'asc',
                        'field' => 'site_id',
                        'label' => 'Obra',
                        'route_name' => 'activities.search',
                    ])
                </th>
                <th>Servicio (de obra)</th>
                <th class="app-text-right">
                    @include('partials.th-sort', [
                        'default_ordering' => 'desc',
                        'field' => 'cost',
                        'label' => 'Costo',
                        'route_name' => 'activities.search',
                    ])
                </th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'desc',
                        'field' => 'date',
                        'is_default' => true,
                        'label' => 'Fecha',
                        'route_name' => 'activities.search',
                    ])
                </th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'desc',
                        'field' => 'billable',
                        'label' => 'Facturable',
                        'route_name' => 'activities.search',
                    ])
                </th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'desc',
                        'field' => 'billed',
                        'label' => 'Facturado',
                        'route_name' => 'activities.search',
                    ])
                </th>
                <th>Folio</th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'desc',
                        'field' => 'paid',
                        'label' => 'Pagado',
                        'route_name' => 'activities.search',
                    ])
                </th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'desc',
                        'field' => 'payment_date',
                        'label' => 'Fecha de pago',
                        'route_name' => 'activities.search',
                    ])
                </th>
                <th class="app-text-right">Gastos</th>
                <th class="app-text-right">Flete</th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'desc',
                        'field' => 'created_at',
                        'label' => 'Creado',
                        'route_name' => 'activities.search',
                    ])
                </th>
                <th>
                    @include('partials.th-sort', [
                        'default_ordering' => 'desc',
                        'field' => 'updated_at',
                        'label' => 'Actualizado',
                        'route_name' => 'activities.search',
                    ])
                </th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>

        <tbody>
            @foreach($activities as $activity)
                <tr>
                    <td>{{ $activity->id }}</td>
                    <td>
                        @if ($activity->unit)
                            <a href="{{ route('units.edit', [$activity->unit->id]) }}" class="link-primary">
                                {{ $activity->unit->name }}
                            </a>
                        @endif
                    </td>
                    <td>
                        @if ($activity->client)
                            <a href="{{ route('clients.edit', [$activity->client->id]) }}" class="link-primary">
                                {{ $activity->client->name }}
                            </a>
                        @endif
                    </td>
                    <td>
                        @if ($activity->site)
                            <a href="{{ route('sites.edit', [$activity->site->id]) }}" class="link-primary">
                                {{ $activity->site->name }}
                            </a>
                        @endif
                    </td>
                    <td>
                        @if ($activity->site_service)
                            <a href="{{ route('site-services.edit', [$activity->site_service->id]) }}" class="link-primary">
                                {{ getProp($activity, 'site_service->service->name') }} 
                            </a>
                            
                            <br>

                            <div class="text-muted app-font-tiny">
                                Precio sugerido {{ $activity->site_service->cost }}
                            </div>
                        @endif
                    </td>
                    <td class="app-text-right text-success">{{ number_format($activity->cost, 2) }}</td>
                    <td>{{ $activity->date }}</td>
                    <td>
                        @include('partials.checkmark', ['checked' => !!$activity->billable])
                    </td>
                    <td>
                        @include('partials.checkmark', ['checked' => !!$activity->billed])
                    </td>
                    <td>
                        @if (getProp($activity, 'invoice_activity->invoice'))
                            <a href="{{ route('invoices.edit', [$activity->invoice_activity->invoice->id]) }}" class="link-primary">
                                {{ $activity->invoice_activity->invoice->bill_code }}
                            </a>
                        @endif
                    </td>
                    <td>
                        @include('partials.checkmark', ['checked' => !!$activity->paid])
                    </td>
                    <td>{{ $activity->payment_date }}</td>
                    <td class="app-text-right">
                        @if($activity->expense)
                            <a href="{{ route('expenses.edit', [$activity->expense->id]) }}" class="link-primary">
                                {{ number_format($activity->expense->amount, 2) }}
                            </a>
                        @endif
                    </td>
                    <td class="app-text-right">
                        @if($activity->expense)
                            {{ number_format($activity->cost - $activity->expense->amount, 2) }}
                        @else
                            {{ number_format($activity->cost, 2) }}
                        @endif
                    </td>
                    <td>{{ $activity->created_at }}</td>
                    <td>{{ $activity->updated_at }}</td>
                    <td>
                        <a href="{{ route('activities.edit', [$activity->id]) }}" class="link-primary">Editar</a>
                    </td>
                    <td>
                        <a href="{{ route('activities.destroy', [$activity->id]) }}" 
                            class="link-primary app-confirm-delete"
                            >
                            Eliminar
                        </a>

                        <form id="delete-form-{{ $activity->id }}" action="{{ route('activities.destroy', [$activity]) }}" method="POST" style="display: none;">
                            <input type="hidden" name="_method" value="DELETE">
                            @csrf
                    </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>