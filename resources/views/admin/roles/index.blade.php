@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container mt-4">

    <div class="row mb-4">
        <div class="col-md-12">
            <h3>{{ __('Roles') }}</h3>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-6">
            <a href="{{ route('roles.create') }}" class="btn btn-primary mb-sm-3 mb-3">
                {{ __('Add new') }}
            </a>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <form action="{{ route('roles.search') }}" method="get">
                <div class="form-group mb-sm-3 mb-3">
                    <input  type="search" name="s" class="form-control" value="{{ htmlentities(request()->s) }}"
                            placeholder="Search roles">
                </div>
            </form>
        </div>
    </div>

    <form action="{{ route('roles.bulk.action') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-3">
                <select name="bulk_action" id="bulk_action" class="form-select mb-sm-3 mb-3">
                    <option value="0" disabled selected>Bulk Action</option>
                    <option value="delete">Delete</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="submit" class="btn btn-primary">
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-3 text-end">Total : {{ $roles->total() }} results</div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered admin-role-table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Bulks</th>
                                <th scope="col">Role</th>
                                <th scope="col">Guard</th>
                                <th scope="col">Date Created</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $key => $value)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="bulks[]" value="{{ $value->id }}" data-id="{{ $value->id }}" class="my-form-checkbox ch-bulks">
                                    </td>
                                    <td class="role-column">
                                        {{-- role name --}}
                                        <a
                                            href="{{ route('roles.show', $value->id) }}"
                                            target="_blank"
                                            class="text-decoration-none role-name">
                                                {{ $value->name }}
                                        </a>

                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="text-decoration-none">
                                            {{ $value->guard_name }}
                                        </a>
                                    </td>
                                    <td>{{ $value->created_at->format('d M, Y') }}</td>
                                    <td class="">
                                        <a href="{{ route('roles.show', $value->id) }}" target="_blank" class="text-decoration-none me-1">View</a>
                                        <a href="{{ route('roles.edit', $value->id) }}" class="text-decoration-none edit-role me-1">Edit</a>
                                        <form
                                            action="{{ route('roles.destroy', $value->id) }}"
                                            method="POST" class="d-inline delete-role" onsubmit="return confirm('Delete it ?');">
                                            @csrf
                                            @method('DELETE')
                                            <a  href="javascript:void(0)"
                                                class="text-decoration-none btn-delete-role">Delete</a>
                                        </form>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">There is no roles</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </form>

</div>
@endsection

@push('script2')

@endpush
