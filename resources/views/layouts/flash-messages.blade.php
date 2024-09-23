@if (Session::has('form_success_store')) 
    <div class="d-flex justify-content-center">
        <div class="container-md">
            <div class="alert alert-success alert-dismissible fade show mb-0 mt-4" role="alert">
                Registro agregado
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

@if (Session::has('form_fail_store')) 
    <div class="d-flex justify-content-center">
        <div class="container-md">
            <div class="alert alert-danger alert-dismissible fade show mb-0 mt-4" role="alert">
                Error al agregar
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

@if (Session::has('form_success_update')) 
    <div class="d-flex justify-content-center">
        <div class="container-md">
            <div class="alert alert-success alert-dismissible fade show mb-0 mt-4" role="alert">
                Registro actualizado
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

@if (Session::has('form_fail_update')) 
    <div class="d-flex justify-content-center">
        <div class="container-md">
            <div class="alert alert-danger alert-dismissible fade show mb-0 mt-4" role="alert">
                Error al actualizar
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

@if (Session::has('form_success_delete')) 
    <div class="d-flex justify-content-center">
        <div class="container-md">
            <div class="alert alert-success alert-dismissible fade show mb-0 mt-4" role="alert">
                Registro eliminado
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

@if (session('status') === 'profile-updated')
    <div class="d-flex justify-content-center">
        <div class="container-md">
            <div class="alert alert-success alert-dismissible fade show mb-0 mt-4" role="alert">
                Perfil actualizado
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

@if (session('status') === 'password-updated')
    <div class="d-flex justify-content-center">
        <div class="container-md">
            <div class="alert alert-success alert-dismissible fade show mb-0 mt-4" role="alert">
                Contraseña actualizada
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif