@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container mt-4">

    {{-- page title --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>{{ __('Edit Role') }}</h3>
        </div>
    </div>

    {{-- page content --}}
    <div class="row">
        <div class="col-12 col-md-4">
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PATCH')

                {{-- permission name --}}
                <div class="mb-3">
                    <input
                        type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Name" value="{{ old('name') ?? $role->name }}" required
                        {{ $role->name == 'Super Admin' ? 'disabled' : 'autofocus' }} >
                    @error('name') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
                </div>

                {{-- permission list --}}
                <div class="mb-3">
                    @forelse ($permissions as $value)
                        <label class="pl-5">
                            <input type="checkbox" name="permission[]" value="{{ $value->id }}"
                            {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }} >
                            {{ $value->name }}
                        </label>
                    @empty
                        {{ __('You have no permissions yet') }}
                    @endforelse
                </div>

                {{-- button submit --}}
                <div class="mb-3">
                    <input type="submit" class="btn btn-dark" value="Update">
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
