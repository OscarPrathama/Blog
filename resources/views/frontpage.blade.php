@extends('main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
{{-- slider --}}
<div class="slider-container">
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/123.jpg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>First slide label</h5>
                    <p>Some representative placeholder content for the first slide.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/671267.jpg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Second slide label</h5>
                    <p>Some representative placeholder content for the second slide.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/admiral.jpg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Third slide label</h5>
                    <p>Some representative placeholder content for the third slide.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"  data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"  data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

{{-- wording --}}
<div class="container">
    <div class="row fp-wording">
        <div class="col-md-12 text-center">
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Autem quidem, cupiditate hic laboriosam voluptatibus eius animi, laborum nemo a consequatur tempora quia corporis doloremque. Quia, nobis aut. Temporibus, quidem voluptatem!</p>
        </div>
    </div>
</div>

{{-- new post --}}
<div class="container fp-blog-container">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse ($posts as $key => $value)
            <div class="col">
                <div class="card h-100">
                    @if ( isset($value['meta_value']['post_image_feature'])
                        && !empty($value['meta_value']['post_image_feature']) )
                        <?php $bg_url = asset('storage'.$value['meta_value']['post_image_feature']['url']); ?>
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
            <h3>There is no posts</h3>
        @endforelse

    </div>
</div>

{{-- location & contact us --}}
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="mb-3">{{ __('Feel free to contact us') }}</h3>
            <form action="" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com">
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Your message</label>
                    <textarea class="form-control" name="message" id="message" rows="6"></textarea>
                </div>
                <div class="mb-3">
                    <input type="submit" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>

{{-- our location --}}
<div class="location">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3175752483!2d106.84682142187127!3d-6.352917423698553!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xf75e41b55cbf9466!2sMako%20Brimob%20Kelapa%202%20Depok!5e0!3m2!1sid!2sid!4v1615624574998!5m2!1sid!2sid"
    width="100%"
    height="450"
    style="border:0;"
    allowfullscreen=""
    loading="lazy"></iframe>
</div>
@stop
