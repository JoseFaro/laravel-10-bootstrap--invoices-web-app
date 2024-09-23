<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-fluid px-md-5">
            <h2>
                Bitacora
            </h2>
            <br>

            <ul class="nav app-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('activities.create') }}">
                        Crear
                    </a>
                </li>
            </ul>

            @include('activities.search')
        
            @include('activities.table')
            
            <div class="app-pagination">
                {{ $activities->links() }}
            </div>
        </div>
    </div>

</x-app-layout>