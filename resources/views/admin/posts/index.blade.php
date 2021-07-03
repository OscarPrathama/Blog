@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container mt-4">

    <div class="row mb-4">
        <div class="col-md-12">
            <h3>{{ __('Posts') }}</h3>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-6">
            <a href="{{ route('posts-create') }}" class="btn btn-primary mb-sm-3 mb-3">
                {{ __('Add new') }}
            </a>
            <a href="{{ route('posts-export-excel') }}" class="btn btn-success mb-sm-3 mb-3">
                {{ __('Export Excel') }}
            </a>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <form action="{{ route('posts-search') }}" method="get">
                <div class="form-group mb-sm-3 mb-3">
                    <input  type="search" name="s" class="form-control" value="{{ htmlentities(request()->s) }}"
                            placeholder="Search posts">
                </div>
            </form>
        </div>
    </div>

    <form action="{{ route('posts-bulk-action') }}" method="POST">
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
            <div class="col-md-3 text-end">Total : {{ $posts->total() }} results</div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered admin-post-table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Bulks</th>
                                <th scope="col">Title</th>
                                <th scope="col">Author</th>
                                <th scope="col">Status</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($posts as $key => $value)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="bulks[]" value="{{ $value->id }}" data-id="{{ $value->id }}" class="my-form-checkbox ch-bulks">
                                    </td>
                                    <td class="post-column">
                                        {{-- post title --}}
                                        <a
                                            href="{{ route('posts-show', ['slug' => $value->post_slug]) }}"
                                            target="_blank"
                                            class="text-decoration-none post-title">
                                                {{ Str::limit($value->post_title, 120, '...') }}
                                        </a>

                                        {{-- post action (view/edit/delete) --}}
                                        <div class="post-title-action" data-post-id="{{ $value->id }}">
                                            <a href="{{ route('posts-show', ['slug' => $value->post_slug]) }}" target="_blank" class="text-decoration-none">View</a>
                                            <a href="javascript:void(0)" class="text-decoration-none quick-edit-btn">Quick Edit</a>
                                            <a href="{{ route('posts-edit', ['id' => $value->id]) }}" class="text-decoration-none edit-post">Edit</a>
                                            <a href="{{ route('posts-delete', ['id' => $value->id]) }}" class="text-decoration-none delete-post" onclick="return confirm('Are you sure')">Delete</a>
                                        </div>

                                        {{-- post edit field --}}
                                        <div class="quick-edit-field" style="display: none;">
                                            <div class="container mt-2">
                                                <div class="row">
                                                    <div class="col-md-6 justify-content-between quick-edit-wrapper">
                                                        <form action="" method="post">
                                                            @csrf
                                                            <small>New Title ?</small>
                                                            <input type="text" class="form-control mb-2" name="new_post_title" placeholder="New Title" value="{{ $value->post_title }}">
                                                            <input type="hidden" class="form-control" name="post_id" value="{{ $value->id }}">
                                                            <select name="post_status" id="post-{{ $value->id }}" class="form-select mb-2">
                                                                <option value="publish" {{ $value->post_status == 'publish' ? 'selected' : '' }}>Publish</option>
                                                                <option value="draft" {{ $value->post_status == 'draft' ? 'selected' : '' }}>Draft</option>
                                                            </select>
                                                            <div class="d-flex justify-content-between">
                                                                <button type="button" class="cancel-quick-edit btn btn-danger">Close</button>
                                                                <input type="submit" name="submit_quick_edit" class="btn btn-primary" value="Update">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    <td>{{ $value->post_author }}</td>
                                    <td class="post-status">{{ $value->post_status }}</td>
                                    <td>{{ $value->created_at->format('d M, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">There is no post</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </form>

</div>
@stop

@section('script')
<script>
$(function(){
    $('.quick-edit-btn').on('click', function(){
        $this = $(this);
        $this.parents('.post-title-action')
            .siblings('.quick-edit-field')
            .toggle();
    });
    $('.cancel-quick-edit').on('click', function(){
        $this = $(this);
        $this.parents('.quick-edit-field').toggle();
    });
    $('[name=submit_quick_edit]').on('click', function(e){
        e.preventDefault();
        $this = $(this);
        var parents_form = $this.parents('.quick-edit-wrapper');
        var parents_column = $this.parents('td.post-column');
        var post_data = {
            post_id: parents_form.find('[name=post_id]').val(),
            new_post_title: parents_form.find('[name=new_post_title]').val(),
            new_post_status: parents_form.find('select[name=post_status]').val()
        };
        // console.log( $this.parents('tr') )
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            dataType: 'JSON',
            url: "{{ route('posts-quick-edit') }}",
            data:post_data,
            beforeSend: function(){
                console.log('loading...')
                parents_column.find('[name=submit_quick_edit]').attr('value', 'Loading...');
            },
            success: function (res) {
                parents_column.find('.post-title').text(res.new_title);
                $this.parents('tr').find('.post-status').text(res.new_status);
                parents_column.find('[name=submit_quick_edit]').attr('value', 'Update');
            },
        });
    });
});
</script>
@stop
