<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-md">
            <h2>Obras</h2> <br>

            <ul class="nav app-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('sites.create') }}">
                        Crear
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('site-services.index') }}">
                        Costos
                    </a>
                </li>
            </ul>
        
            <div class="table-responsive">
                <table class="table table-hover app-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>
                                <a href="{{ getSortableUrl('name', 'asc', 'sites.index') }}" class="app-table-heading-link">
                                    <span>Nombre</span>

                                    @php $iconDirection = getSortableIconDirection('name', 'asc'); @endphp
                                    @if ($iconDirection)
                                        <i class="bi bi-caret-{{ $iconDirection }}-fill"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                @include('partials.th-sort', [
                                    'default_ordering' => 'asc',
                                    'field' => 'client_id',
                                    'label' => 'Clientes',
                                    'route_name' => 'sites.index',
                                ])
                            </th>
                            <th>Dirección</th>
                            <th>
                                <a href="{{ getSortableUrl('contact', 'asc', 'sites.index', [], true) }}" class="app-table-heading-link">
                                    <span>Supervisor</span>

                                    @php $iconDirection = getSortableIconDirection('contact', 'asc', true); @endphp
                                    @if ($iconDirection)
                                        <i class="bi bi-caret-{{ $iconDirection }}-fill"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Costos</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($sites as $site)
                            <tr>
                                <td>{{ $site->id }}</td>
                                <td>{{ $site->name }}</td>
                                <td>
                                    @if ($site->client)
                                        <a href="{{ route('clients.edit', [$site->client->id]) }}" class="link-primary">
                                            {{ $site->client->name }}
                                        </a>
                                    @endif
                                </td>
                                <td>{!! nl2br($site->address) !!}</th>
                                <td>{{ $site->contact }}</td>
                                <td>
                                    <a href="{{ route('site-services.site', [$site->id]) }}" class="link-primary">Costos</a>
                                </td>
                                <td>
                                    <a href="{{ route('sites.edit', [$site->id]) }}" class="link-primary">Editar</a>
                                </td>
                                <td>
                                    <a href="{{ route('sites.destroy', [$site->id]) }}" 
                                        class="link-primary app-confirm-delete"
                                        >
                                        Eliminar
                                    </a>

                                    <form id="delete-form-{{ $site->id }}" action="{{ route('sites.destroy', [$site]) }}" method="POST" style="display: none;">
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
                {{ $sites->links() }}
            </div>
        </div>
    </div>

</x-app-layout>