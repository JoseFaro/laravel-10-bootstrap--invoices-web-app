<div id="invoice-activities-container" class="mb-5">
    <table class="table table-hover app-table mb-0">
        <thead>
            <tr>
                <th></th>
                <th>#</th>
                <th>Unidad</th>
                <th>Obra</th>
                <th>Servicio (de obra)</th>
                <th>Costo</th>
                <th>Fecha</th>
                <th>Creado</th>
                <th>Actualizado</th>
            </tr>
        </thead>
        
        <tbody class="app-tbody">
            @if (count($activities))
                @foreach($activities as $activity)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="activities[]" value="{{ $activity->id }}" {{ $activity->checked ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td>{{ $activity->id }}</td>
                        <td>
                            @if ($activity->unit)
                                {{ $activity->unit->name }}
                            @endif
                        </td>
                        <td>
                            @if ($activity->site)
                                {{ $activity->site->name }}
                            @endif
                        </td>
                        <td>
                            @if ($activity->site_service)
                                @if ($activity->site_service->service)
                                    <span>{{ $activity->site_service->service->name }}</span>
                                    <br>
                                @endif
                                <div class="text-muted app-font-tiny">
                                    Precio sugerido {{ $activity->site_service->cost }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $activity->cost }}</td>
                        <td>{{ $activity->date }}</td>
                        <td>{{ $activity->created_at }}</td>
                        <td>{{ $activity->updated_at }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>