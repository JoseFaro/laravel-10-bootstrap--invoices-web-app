<x-app-layout>
    <br><br>

    <div class="d-flex justify-content-center">
        <div class="container-fluid px-md-5">
            <h2>
                Gastos
            </h2>
            <br>

            <ul class="nav app-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('expenses.create') }}">
                        Crear
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('expenses-categories.index') }}">
                        Categorías
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('suppliers.index') }}">
                        Proveedores
                    </a>
                </li>
            </ul>

            @include('expenses.search')
        
            @include('expenses.table')

            <div class="app-pagination">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>

</x-app-layout>