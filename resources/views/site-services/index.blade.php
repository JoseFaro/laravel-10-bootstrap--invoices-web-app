<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-md">
            <h2>
                Costos en obras<?=$site ? ':' : ''?>
                @if ($site_id)
                    <span class="text-primary">
                        {{ $site->name }}
                    </span>
                @endif
            </h2> <br>

            <ul class="nav app-nav">
                <li class="nav-item">
                    <a 
                        class="nav-link"
                        href="{{ $site_id ? route('site-services.create-for-site', [$site_id]) : route('site-services.create') }}">
                        Crear
                    </a>
                </li>
            </ul>
        
            <div class="table-responsive">
                <table class="table table-hover app-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            @if (!$site_id)
                                <th>
                                    @include('partials.th-sort-icon', [
                                        'default_ordering' => 'asc',
                                        'field' => 'site_id',
                                        'is_default' => true,
                                        'label' => 'Obra',
                                    ])
                                </th>
                            @endif
                            <th>
                                @if (!$site_id)
                                    Servicio
                                @else
                                    @include('partials.th-sort-icon', [
                                        'default_ordering' => 'asc',
                                        'field' => 'service_id',
                                        'is_default' => true,
                                        'label' => 'Servicio',
                                    ])
                                @endif
                            </th>
                            <th>Costo</th>
                            <th>Creado</th>
                            <th>Actualizado</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($site_services as $site_service)
                            <tr>
                                <td>{{ $site_service->id }}</td>
                                @if (!$site_id)
                                    <td>
                                        @if ($site_service->site)
                                            <a href="{{ route('sites.edit', [$site_service->site->id]) }}" class="link-primary">
                                                {{ $site_service->site->name }}
                                            </a>
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    @if ($site_service->service)
                                        <a href="{{ route('services.edit', [$site_service->service->id]) }}" class="link-primary">
                                            {{ $site_service->service->name }}
                                        </a>
                                    @endif
                                </td>
                                <td>{{ number_format($site_service->cost, 2) }}</td>
                                <td>{{ $site_service->created_at }}</td>
                                <td>{{ $site_service->updated_at }}</td>
                                <td>
                                    <a href="{{ route('site-services.edit', [$site_service->id]) }}" class="link-primary">Editar</a>
                                </td>
                                <td>
                                    <a href="{{ route('site-services.destroy', [$site_service->id]) }}" 
                                        class="link-primary app-confirm-delete"
                                        >
                                        Eliminar
                                    </a>

                                    <form id="delete-form-{{ $site_service->id }}" action="{{ route('site-services.destroy', [$site_service]) }}" method="POST" style="display: none;">
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
                {{ $site_services->links() }}
            </div>
        </div>
    </div>

</x-app-layout>