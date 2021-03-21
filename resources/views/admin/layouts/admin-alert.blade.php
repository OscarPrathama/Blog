@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show alert-right-fixed" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
