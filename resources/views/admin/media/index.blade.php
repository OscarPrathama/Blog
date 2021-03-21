@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container media-container my-5">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h3>Images</h3>
        </div>
    </div>
    <div class="row">
        @foreach ($images as $key => $value)
            @if( isset($value['data']->url) )
                <div class="col-md-3 col-sm-6 col-6">
                    <a  href="javascript:void(0)"
                        data-bs-toggle="modal"
                        data-bs-target="#mediaImgModal"
                        data-img_id="{{ $value['id'] }}"
                        data-img_alt="{{ $value['data']->just_name }}"
                        data-img_src="{{ asset('storage'.$value['data']->url) }}"
                        class="post-img">
                        <img
                        src="{{ asset('storage'.$value['data']->url) }}"
                        alt="{{ $value['data']->just_name }}"
                        class="img-thumbnail" >
                    </a>
                </div>
            @endif
        @endforeach
    </div>
</div>

{{-- image modal --}}
<div class="modal fade" id="mediaImgModal" tabindex="-1" aria-labelledby="mediaImgModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaImgModalLabel">Edit Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 text-center admin-img-alert">
                            <img
                                src=""
                                alt=""
                                class="img-thumbnail"
                                id="media_img">
                                <a  href="javascript:void(0)"
                                    class=""
                                    id="delete_img"
                                    >Remove</a>
                        </div>
                        <div class="col-md-7">
                            <form action="" method="POST" onsubmit="return false">
                                @csrf
                                <div class="mb-3">
                                    <label for="img_alt" class="col-form-label">Image ALT</label>
                                    <input type="text" class="form-control" name="img_alt" id="img_alt" value="">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update_img">Save changes</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script type="text/javascript">
$(function(){

    var img_id, img_src, img_alt, current_media;

    $(".post-img").on('click', function(e){

        current_media = $(this);
        img_id = current_media.data('img_id');
        img_src = current_media.data('img_src');
        img_alt = current_media.data('img_alt');

        $('#media_img').attr('src', img_src);
        $('#media_img').attr('alt', img_alt);
        $('[name=img_alt]').val(img_alt);

    });

    $('button#update_img').on('click', function(e){

        e.preventDefault();

        img_data = {
            img_id: img_id,
            img_alt: $('[name=img_alt]').val()
        };

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            dataType: 'JSON',
            url: "{{ route('media-update') }}",
            data: img_data,
            success: function (res) {
                current_media.data('img_alt', res.data.img_alt);
                $('.admin-img-alert').prepend(message(res.msg));
            },
        });

    });

    $('#delete_img').on('click', function(){

        var comfirm = confirm('Are You sure want to delete !');

        if (comfirm == true) {
            img_data = {
                img_id: img_id,
            };
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                dataType: 'JSON',
                url: "{{ route('media-delete') }}",
                data: img_data,
                success: function (res) {
                    console.log(res);
                    location.reload();
                    $('.admin-img-alert').prepend(message(res.msg));
                },
            });
        }

    });

    function message(msg){
        return `<div class="alert alert-success alert-dismissible fade show" role="alert">
                    `+msg+`
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`
    }

});
</script>
@stop
