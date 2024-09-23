<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-fluid px-md-5">
            <h2>
                Facturas
            </h2>
            <br>

            <ul class="nav app-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('invoices.create') }}">
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
                                    'field' => 'bill_code',
                                    'label' => 'Folio',
                                    'route_name' => 'invoices.index',
                                ])
                            </th>
                            <th>
                                @include('partials.th-sort', [
                                    'default_ordering' => 'asc',
                                    'field' => 'client_id',
                                    'label' => 'Cliente',
                                    'route_name' => 'invoices.index',
                                ])
                            </th>
                            <th>
                                @include('partials.th-sort', [
                                    'default_ordering' => 'desc',
                                    'field' => 'billed_date',
                                    'is_default' => true,
                                    'label' => 'Fecha',
                                    'route_name' => 'invoices.index',
                                ])
                            </th>
                            <th>Actividades</th>
                            <th class="app-text-right">
                                @include('partials.th-sort', [
                                    'default_ordering' => 'desc',
                                    'field' => 'subtotal',
                                    'label' => 'Subtotal',
                                    'route_name' => 'invoices.index',
                                ])
                            </th>
                            <th class="app-text-right">
                                @include('partials.th-sort', [
                                    'default_ordering' => 'desc',
                                    'field' => 'iva',
                                    'label' => 'IVA',
                                    'route_name' => 'invoices.index',
                                ])
                            </th>
                            <th class="app-text-right">
                                @include('partials.th-sort', [
                                    'default_ordering' => 'desc',
                                    'field' => 'retained_iva',
                                    'label' => 'IVA retenido',
                                    'route_name' => 'invoices.index',
                                ])
                            </th>
                            <th class="app-text-right">
                                @include('partials.th-sort', [
                                    'default_ordering' => 'desc',
                                    'field' => 'isr',
                                    'label' => 'ISR retenido',
                                    'route_name' => 'invoices.index',
                                ])
                            </th>
                            <th class="app-text-right">
                                @include('partials.th-sort', [
                                    'default_ordering' => 'desc',
                                    'field' => 'total',
                                    'label' => 'Total',
                                    'route_name' => 'invoices.index',
                                ])
                            </th>
                            <th>
                                @include('partials.th-sort', [
                                    'default_ordering' => 'desc',
                                    'field' => 'created_at',
                                    'label' => 'Creado',
                                    'route_name' => 'invoices.index',
                                ])
                            </th>
                            <th>
                                @include('partials.th-sort', [
                                    'default_ordering' => 'desc',
                                    'field' => 'updated_at',
                                    'label' => 'Actualizado',
                                    'route_name' => 'invoices.index',
                                ])
                            </th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->id }}</td>
                                <td>{{ $invoice->bill_code }}</td>
                                <td>
                                    @if ($invoice->client)
                                        <a href="{{ route('clients.edit', [$invoice->client->id]) }}" class="link-primary">
                                            {{ $invoice->client->name }}
                                        </a>
                                    @endif
                                <td>{{ $invoice->billed_date }}</td>
                                <td>
                                    @php
                                        $subtotal = 0;
                                        $has_activities = false;
                                    @endphp

                                    @if ($invoice->invoice_activities)
                                        @foreach($invoice->invoice_activities as $invoice_activity)
                                            @if (getProp($invoice_activity, 'activity->site_service->service'))
                                                <a href="{{ route('activities.edit', [$invoice_activity->activity->id]) }}" class="app-table-invoice-activity">
                                                    #{{ $invoice_activity->activity->id }} -
                                                    {{ $invoice_activity->activity->site_service->service->name }} 
                                                    <div class="text-muted app-font-tiny">
                                                        {{ $invoice_activity->activity->cost }}
                                                    </div>
                                                </a>

                                                @php 
                                                    $subtotal += $invoice_activity->activity->cost; 
                                                    $has_activities = true;
                                                @endphp
                                            @endif
                                        @endforeach

                                        @if ($has_activities)
                                            <div class="app-table-invoice-activities-total text-success ">
                                                {{ number_format($subtotal, 2) }}
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="app-text-right">{{ number_format($invoice->subtotal, 2) }}</td>
                                <td class="app-text-right">{{ number_format($invoice->iva, 2) }}</td>
                                <td class="app-text-right">{{ number_format($invoice->retained_iva, 2) }}</td>
                                <td class="app-text-right">{{ number_format($invoice->isr, 2) }}</td>
                                <td class="app-text-right">{{ number_format($invoice->total, 2) }}</td>
                                <td>{{ $invoice->created_at }}</td>
                                <td>{{ $invoice->updated_at }}</td>
                                <td>
                                    <a href="{{ route('invoices.edit', [$invoice->id]) }}" class="link-primary">Editar</a>
                                </td>
                                <td>
                                    <a href="{{ route('invoices.destroy', [$invoice->id]) }}" 
                                        class="link-primary app-confirm-delete"
                                        >
                                        Eliminar
                                    </a>

                                    <form id="delete-form-{{ $invoice->id }}" action="{{ route('invoices.destroy', [$invoice]) }}" method="POST" style="display: none;">
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
                {{ $invoices->links() }}
            </div>
        </div>
    </div>

</x-app-layout>