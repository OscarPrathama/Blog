@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@endsection

@section('content')

<div class="container admin-inbox-table my-5">

    {{-- inbox title --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <h3>({{ $inboxes->total() }}) Inbox</h3>
        </div>
        <div class="col-md-3 offset-md-6">
            <form action="{{ route('inbox.search') }}" method="GET">
                <input type="search" class="form-control" placeholder="Search" name="s" value="{{ htmlentities(request()->s) }}">
            </form>
        </div>
    </div>

    {{-- inbox main content --}}

    <form action="{{ route('inbox.bulk.action') }}" method="POST" onsubmit="return confirm('Delete it ?')">
        @csrf
        <div class="row mb-3">
            <div class="col-md-3">
                <select name="bulk_action" id="bulk_action" class="form-select">
                    <option value="0" disabled selected>Bulk Action</option>
                    <option value="delete">Delete</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="submit" class="btn btn-primary">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered admin-inbox-table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Bulk</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Message</th>
                                <th scope="col">Date Created</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inboxes as $key => $value)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="bulks[]" value="{{ $value->id }}" data-id="{{ $value->id }}" class="my-form-checkbox ch-bulks">
    </form>
                                    </td>
                                    <td class="table-column">
                                        <a href="{{ URL::to('admin/inbox/'.$value->id) }}" class="text-decoration-none" target="_blank">
                                            {{ $value->name }}
                                        </a>
                                    </td>
                                    <td>{{ $value->email }}</td>
                                    <td style="width: 25%">{{ Str::limit($value->message, 90, '...') }}</td>
                                    <td>{{ $value->created_at->format('d M, Y') }}</td>
                                    <td style="width: 15%">
                                        <a href="{{ URL::to('admin/inbox/'.$value->id) }}" class="btn btn-success d-inline" target="_blank">View</a>
                                        <form
                                            action="{{ route('inbox.destroy', $value->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Delete it ?')">
                                            @method('DELETE')
                                            @csrf
                                            <input type="submit" class="btn btn-danger" value="Delete">
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">There is no Inbox</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $inboxes->links() }}
                </div>
            </div>
        </div>


</div>

@endsection
