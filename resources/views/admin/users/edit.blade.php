@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container mt-4">

    {{-- page title --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>{{ __('Edit User') }}</h3>
        </div>
    </div>

    {{-- page content --}}
    <div class="row">
        <div class="col-12 col-md-4">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <input type="hidden" value="{{ $user->id }}" name="id">
                    <input
                        type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Name" value="{{ old('name') ?? $user->name }}" autofocus>
                        @error('name') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <input
                        type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        placeholder="Email" value="{{ old('email') ?? $user->email }}">
                        @error('email') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <strong>Role : </strong><br>
                    <select name="roles[]" multiple class="form-control">
                        @forelse ($roles as $item)
                            <option value="{{ $item }}" {{ in_array($item, $userRole) ? "selected" : "" }}>
                                {{ $item }}
                            </option>
                        @empty
                            -
                        @endforelse
                    </select>
                    @error('roles') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <input
                        type="password" name="password" class="form-control @error('email') is-invalid @enderror"
                        placeholder="Password" autocomplete="new-password">
                        @error('password') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <input
                        type="password" name="password_confirmation" class="form-control @error('email') is-invalid @enderror"
                        placeholder="Confirm Password">
                        @error('password_confirmation') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <input type="submit" class="btn btn-dark" value="Register">
                </div>
            </form>
        </div>
    </div>

</div>
@stop
