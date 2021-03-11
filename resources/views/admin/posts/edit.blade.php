@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container my-5">

	<div class="row">
		<div class="col12">
			<h3>Edit post</h3>
		</div>
	</div>

	<form action="{{ route('posts-update', ['id' => $post->id]) }}" method="POST" class="post-form" enctype="multipart/form-data">
		@csrf
		<div class="row create-post-form">
			<div class="col-8">
				<input type="text" name="post_title" class="form-control mb-1 @error('post_title') is-invalid @enderror" placeholder="Post title" value="{{ old('post_title') ?? $post->post_title }}">
				@error('post_title') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror

                <input type="text" name="post_slug" style="width: 400px;" class="form-control form-control-sm @error('post_slug') is-invalid @enderror" placeholder="Post slug" value="{{ old('post_slug') ?? $post->post_slug }}">
                @error('post_slug') <div class="invalid-feedback">{{ $message }}</div> @enderror

                <div class="mb-4"></div>

				<textarea name="post_content" id="postContent" cols="30" rows="10" placeholder="Post content" class="form-control mt-4 @error('post_content') is-invalid @enderror">{{ $post->post_content }}</textarea>
				@error('post_content') <div class="invalid-feedback">{{ $message }}</div> @enderror
			</div>
			<div class="col-4">
                <div class="accordion mb-4" id="postStatusAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="postStatusDialog">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePostStatus" aria-expanded="true" aria-controls="collapsePostStatus">
                                Post Status (Currently {{ $post->post_status }})
                            </button>
                        </h2>
                        <div id="collapsePostStatus" class="accordion-collapse collapse show" aria-labelledby="postStatusDialog" data-bs-parent="#postStatusAccordion">
                            <div class="accordion-body">
                                <div class="card-body d-flex justify-content-between">
					                <input type="submit" name="post_status" class="btn btn-warning text-capitalize" value="draft">
					                <input type="submit" name="post_status" class="btn btn-primary text-capitalize" value="publish">
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
                                        <label for="exampleFormControlFile1">Example file input</label>
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
