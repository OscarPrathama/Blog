@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container mt-4">

    <div class="row mb-4">
        <div class="col-md-12">
            <h3>{{ __('Users') }}</h3>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-6">
            <a href="{{ route('users.create') }}" class="btn btn-primary mb-sm-3 mb-3">
                {{ __('Add new') }}
            </a>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <form action="{{ route('users.search') }}" method="get">
                <div class="form-group mb-sm-3 mb-3">
                    <input  type="search" name="s" class="form-control" value="{{ htmlentities(request()->s) }}"
                            placeholder="Search users">
                </div>
            </form>
        </div>
    </div>

    <form action="{{ route('users.bulk.action') }}" method="POST">
        @csrf
        @method('DELETE')
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
            <div class="col-md-3 text-end">Total : {{ $users->total() }} results</div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered admin-user-table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Bulks</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $key => $value)
                                <tr>
                                    <td>
                                        <input
                                            type="checkbox" name="bulks[]" value="{{ $value->id }}"
                                            data-id="{{ $value->id }}" class="my-form-checkbox ch-bulks">
                                    </td>
                                    <td class="user-column">

                                        {{-- name --}}
                                        <a
                                            href="{{ route('users.edit', $value->id) }}"
                                            class="text-decoration-none">
                                            {{ $value->name }}
                                        </a>

                                        {{-- action (edit/delete) --}}
                                        <div class="" data-user-id="{{ $value->id }}">
                                            <a href="{{ route('users.edit', $value->id) }}" class="text-decoration-none edit-user">Edit</a>
                                            <form   action="{{ route('users.destroy', $value->id) }}"
                                                    method="POST" class="d-inline delete-user">
                                                @csrf
                                                @method('DELETE')
                                                <a  href="javascript:void(0)"
                                                    class="text-decoration-none btn-delete-user">Delete</a>
                                            </form>
                                        </div>

                                    </td>
                                    <td>{{ $value->email }}</td>
                                    <td>{{ $value->created_at->format('d M, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">There is no Users</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </form>

</div>
@stop

@push('script2')
<script>
$(function(){
    $('a.btn-delete-user').on('click', function(){
        if(confirm('Are you sure ?')){
            $(this).parents('form.delete-user').submit();
        }
    })
})
</script>
@endpush
