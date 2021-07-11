@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container mt-4">

    {{-- title --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>{{ __('User Profile') }}</h3>
        </div>
    </div>

    {{-- content --}}
    <div class="row">

        {{-- user image --}}
        <div class="col-md-3">
            <div class="thumbmail-wrapper">
                <img src="" class="img-thumbnail d-block" alt="user image">
            </div>
        </div>

        {{-- user content --}}
        <div class="col-md-4">
            <table class="table table-bordered table-stripe table-hover">
                <tr>
                    <th>Name</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Posts</th>
                    <td>{{ $user->post_count }}</td>
                </tr>
                <tr>
                    <th>Created at</th>
                    <td>{{ $user->created_at->format('d M, Y') }}</td>
                </tr>
                <tr>
                    <th>Roles</th>
                    <td>
                        @forelse ($user->roles as $key => $value)
                            <label class="badge bg-primary p-2">{{ $value->name }}</label>
                        @empty
                            -
                        @endforelse
                    </td>
                </tr>
            </table>
        </div>
    </div>

</div>
@stop
