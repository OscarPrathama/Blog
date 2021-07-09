@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container mt-4">

    <div class="row mb-4">
        <div class="col-md-12">
            <h3>{{ __('Permissions') }}</h3>
        </div>
    </div>

    <div class="row mb-2 justify-content-end">
        <div class="col-md-3">
            <form action="{{ route('permissions.search') }}" method="get">
                <div class="form-group mb-sm-3 mb-3">
                    <input  type="search" name="s" class="form-control" value="{{ htmlentities(request()->s) }}"
                            placeholder="Search permissions">
                </div>
            </form>
        </div>
    </div>


    <div class="row mb-3">
        <div class="col-md-9"></div>
        <div class="col-md-3 text-end">Total : {{ $permissions->total() }} results</div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered admin-permission-table">
                    <thead>
                        <tr>
                            <th scope="col">Role</th>
                            <th scope="col">Guard</th>
                            <th scope="col">Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permissions as $key => $value)
                            <tr>
                                <td class="permission-column">
                                    <a href="javascript:void(0)" class="text-decoration-none permission-name">
                                        {{ $value->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="text-decoration-none">
                                        {{ $value->guard_name }}
                                    </a>
                                </td>
                                <td>{{ $value->created_at->format('d M, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">There is no permissions</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination">
                {{ $permissions->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
