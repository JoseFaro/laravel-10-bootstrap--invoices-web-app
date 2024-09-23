<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-fluid px-md-5">
            <h2>
                Dashboard
            </h2>
            <br>

            @include('dashboard.search')

            <div class="mb-5 app-dashboard-dates">
                @for($counter = 9; $counter >= 0; $counter--)
                    @php
                        $year = date('Y') - $counter;
                        $buttonClass = $isRequestedFullYearWithDates && $year == $selectedYearForSearch ? 'btn btn-primary mb-1' : 'btn btn-outline-primary mb-1';

                        $requestParams = app('request')->all();
                        $requestParams['start_date'] = $year . '-01-01';
                        $requestParams['end_date'] = $year . '-12-31';

                        $queryUrl = Arr::query($requestParams);
                    @endphp
                    
                    <a 
                        href="{{ route('dashboard') }}?{{ $queryUrl }}" 
                        role="button" 
                        class="{{ $buttonClass }}">
                        {{ $year }}
                    </a>
                @endfor
            </div>

            <div class="table-responsive">
                <table class="table table-hover app-table">
                    <thead>
                        <tr>
                            <th>Año</th>
                            <th>Mes</th>
                            @if (app('request')->input('report_type') == 'daily')
                                <th>Día</th>
                            @endif
                            <th class="app-text-right">Ingresos / Facturados / No Facturables</th>
                            <th class="app-text-right">Por facturar</th>
                            <th class="app-text-right">Factura Pagada / No pagada</th>
                            <th class="app-text-right">No facturable Pagado / No pagado</th>
                            <th class="app-text-right">Gastos</th>
                            <th class="app-text-right">Gastos Facturados</th>
                            <th class="app-text-right">Gastos Facturados Pagados</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($utilities['dates'] as $date => $utility)
                            <tr>
                                <td>{{ $utility['year'] }}</td>
                                <td>{{ $utility['month'] }}</td>
                                @if (app('request')->input('report_type') == 'daily')
                                    <td>{{ $utility['day'] }}</td>
                                @endif
                                <td class="app-text-right">
                                    <span class="text-success">{{ number_format($utility['totalCost'], 2) }}</span> /
                                    <span class="text-primary">{{ number_format($utility['totalBilled'], 2) }}</span> /
                                    <span class="">{{ number_format($utility['totalNotBillable'], 2) }}</span>
                                </td>
                                <td class="app-text-right">
                                    <span class="{{ $utility['totalBillableNotBilled'] > 0 ? 'text-danger' : '' }}">
                                        {{ number_format($utility['totalBillableNotBilled'], 2) }}
                                    </span>
                                </td>
                                <td class="app-text-right">
                                    <span class="text-success">{{ number_format($utility['totalBilledAndPaid'], 2) }}</span> /
                                    <span class="text-danger">{{ number_format($utility['totalBilledAndNotPaid'], 2) }}</span>
                                </td>
                                <td class="app-text-right">
                                    <span class="text-success">{{ number_format($utility['totalNotBillableAndPaid'], 2) }}</span> /
                                    <span class="text-danger">{{ number_format($utility['totalNotBillableAndNotPaid'], 2) }}</span>
                                </td>
                                <td class="app-text-right">
                                    {{ number_format($utility['totalExpenses'], 2) }}
                                </td>
                                <td class="app-text-right">
                                    {{ number_format($utility['totalExpensesBilled'], 2) }}
                                </td>
                                <td class="app-text-right">
                                    {{ number_format($utility['totalExpensesBilledAndPaid'], 2) }}
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <th></th>
                            <th></th>
                            @if (app('request')->input('report_type') == 'daily')
                                <th></th>
                            @endif
                            <th class="app-text-right">
                                <span class="text-success">{{ number_format($utilities['totalCost'], 2) }}</span> /
                                <span class="text-primary">{{ number_format($utilities['totalBilled'], 2) }}</span> /
                                <span class="">{{ number_format($utilities['totalNotBillable'], 2) }}</span>
                            </th>
                            <th class="app-text-right">
                                <span class="{{ $utilities['totalBillableNotBilled'] > 0 ? 'text-danger' : '' }}">
                                    {{ number_format($utilities['totalBillableNotBilled'], 2) }}
                                </span>
                            </th>
                            <th class="app-text-right">
                                <span class="text-success">{{ number_format($utilities['totalBilledAndPaid'], 2) }}</span> /
                                <span class="text-danger">{{ number_format($utilities['totalBilledAndNotPaid'], 2) }}</span>
                            </th>
                            <th class="app-text-right">
                                <span class="text-success">{{ number_format($utilities['totalNotBillableAndPaid'], 2) }}</span> /
                                <span class="text-danger">{{ number_format($utilities['totalNotBillableAndNotPaid'], 2) }}</span>
                            </th>
                            <th class="app-text-right">
                                {{ number_format($utilities['totalExpenses'], 2) }}
                            </th>
                            <th class="app-text-right">
                                {{ number_format($utilities['totalExpensesBilled'], 2) }}
                            </th>
                            <th class="app-text-right">
                                {{ number_format($utilities['totalExpensesBilledAndPaid'], 2) }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>