@extends('main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container blog-container my-5">
    <div class="row mb-3">
        <div class="col-md-12">
            <h3>Blogs</h3>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-3">
        @forelse ($posts as $key => $value)
            <div class="col">
                <div class="card h-100">
                    @if ( isset($value->meta_value->post_image_feature)
                        && !empty($value->meta_value->post_image_feature) )
                        <?php $bg_url = asset('storage'.$value->meta_value->post_image_feature->url); ?>
                    @else
                        <?php $bg_url = asset('images/2.jpg'); ?>
                    @endif
                    <a href="{{ route('posts-show', ['slug' => $value->post_slug]) }}" class="img-wrapper" style="background-image: url('{{ $bg_url }}')"></a>
                    <div class="card-body">
                        <a href="{{ route('posts-show', ['slug' => $value->post_slug]) }}" class="text-decoration-none">
                            <h5 class="card-title">{{ $value->post_title }}</h5>
                        </a>
                        <p class="card-text">
                            {!! Str::limit($value->post_content, 150, '...') !!}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <h4>{{ __('There\'s no post') }}</h4>
        @endforelse
    </div>
    <div class="row mb-5">
        <div class="col-md-12 pagination justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@stop
