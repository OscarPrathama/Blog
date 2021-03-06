@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container my-5">

	<div class="row">
		<div class="col12">
			<h3>Edit page</h3>
		</div>
	</div>

	<form action="{{ route('pages-update', ['id' => $page->id]) }}" method="POST" class="post-form" enctype="multipart/form-data">
		@csrf
		<div class="row create-post-form">
			<div class="col col-lg-9 mb-sm-5 mb-5">
                <input type="hidden" name="post_id" value="{{ $page->id }}">

                {{-- title --}}
				<input type="text" name="post_title" class="form-control mb-1 @error('post_title') is-invalid @enderror" placeholder="Post title" value="{{ old('post_title') ?? $page->post_title }}">
				@error('post_title') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror

                {{-- slug --}}
                <input type="text" name="post_slug" style="width: 400px;" class="form-control form-control-sm @error('post_slug') is-invalid @enderror" placeholder="Post slug" value="{{ old('post_slug') ?? $page->post_slug }}">
                @error('post_slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <p class="small">
                    Preview :
                    <a href="{{ route('posts-show', ['slug' => $page->post_slug]) }}" target="_blank">
                        {{ route('posts-show', ['slug' => $page->post_slug]) }}
                    </a>
                </p>
                <div class="mb-4"></div>

                {{-- content --}}
				<textarea name="post_content" id="postContent" cols="30" rows="10" placeholder="Post content" class="form-control mt-4 @error('post_content') is-invalid @enderror">{{ $page->post_content }}</textarea>
				@error('post_content') <div class="invalid-feedback">{{ $message }}</div> @enderror

                {{-- custom field --}}
                @if ($post_meta && !empty($post_meta))
                    <div class="my-4">
                        <h3>Custom field here</h3>
                    </div>
                @endif
			</div>
			<div class="col col-lg-3">
                <div class="accordion mb-4" id="pagestatusAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="pagestatusDialog">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsepagestatus" aria-expanded="true" aria-controls="collapsepagestatus">
                                Post Status (Currently {{ $page->post_status }})
                            </button>
                        </h2>
                        <div id="collapsepagestatus" class="accordion-collapse collapse show" aria-labelledby="pagestatusDialog" data-bs-parent="#pagestatusAccordion">
                            <div class="accordion-body">
                                <div class="card-body d-flex justify-content-between">
					                <input type="submit" name="post_status" class="btn btn-warning text-capitalize" value="draft">
					                <input type="submit" name="post_status" class="btn btn-primary text-capitalize" value="Update">
					            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion" id="postImageFeatureAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="imageFeatureDialog">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseImageFeature" aria-expanded="false" aria-controls="collapseImageFeature">
                                Image Feature
                            </button>
                        </h2>
                        <div id="collapseImageFeature" class="accordion-collapse collapse show" aria-labelledby="imageFeatureDialog" data-bs-parent="#postImageFeatureAccordion">
                            <div class="accordion-body">
                                <div class="form-group">
                                    <div class="img-label mt-2 mb-4">
                                        @if ($post_meta && $post_meta != null && !empty($post_meta->post_image_feature))
                                            <img
                                                src="{{ asset('storage'.$post_meta->post_image_feature->url) }}"
                                                alt="{{ $post_meta->post_image_feature->just_name }}"
                                                id="imgPreview">
                                        @else
                                            <img
                                                src=""
                                                alt=""
                                                id="imgPreview">
                                        @endif
                                        <a href="javascript:void(0)" id="removeImgPreview">
                                            Remove
                                        </a>
                                    </div>
                                    <input type="file" name="post_img_feature" class="form-control-file" id="exampleFormControlFile1">
                                    @error('post_img_feature') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

			</div>
		</div>
	</form>
</div>
@endsection


@section('script')
<script>
let remove_img_preview = $('.post-edit a#removeImgPreview');
let img_preview = $('#imgPreview');
remove_img_preview.on('click', function(){

    function imgSrcCheck(){
        if( img_preview.attr('src').length != '' ){
            remove_img_preview.css('display', 'block');
        }else{
            remove_img_preview.css('display', 'none');
        }
    }

    if(confirm('Are you sure ?') == true){
        img_preview.attr('src', '');
        img_preview.attr('alt', '');
        $('[name=post_img_feature]').val('');
        imgSrcCheck();

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            url: "{{ route('pages-edit-remove-image') }}",
            data: {
                post_id: $('[name=post_id]').val()
            },
            success: function (res) {
                console.log(res)
            }
        });
    }
});
</script>
@stop
