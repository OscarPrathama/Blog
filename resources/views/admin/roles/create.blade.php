@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container mt-4">

    {{-- page title --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>{{ __('Add New Role') }}</h3>
        </div>
    </div>

    {{-- page content --}}
    <div class="row">
        <div class="col-12 col-md-4">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                {{-- permission name --}}
                <div class="mb-3">
                    <input
                        type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Name" value="{{ old('name') }}" required autofocus>
                    @error('name') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
                </div>

                {{-- permission list --}}
                <div class="mb-3">
                    @forelse ($permissions as $value)
                        <label class="pl-5">
                            <input type="checkbox" name="permission[]" value="{{ $value->id }}" >
                            {{ $value->name }}
                        </label>
                    @empty
                        {{ __('No Permission list') }}
                    @endforelse
                    @error('permission') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
                </div>

                {{-- button submit --}}
                <div class="mb-3">
                    <input type="submit" class="btn btn-dark" value="Submit">
                </div>

            </form>
        </div>
    </div>

</div>
@endsection