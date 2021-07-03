@extends('main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
{{-- slider --}}
@isset($sliders)
    @php
        $i = $j = 0;
    @endphp
    <div class="slider-container">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach ($sliders as $key)
                    <button type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}"
                            aria-current="true" aria-label="Slide {{ $i }}"></button>
                    @php $i++; @endphp
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach ($sliders as $key)
                    <div class="carousel-item {{ $j == 0 ? 'active' : '' }}">
                        <img src="{{ asset('images/admiral.jpg') }}" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>{{ $key->title }}</h5>
                            <p>{{ $key->wording }}</p>
                        </div>
                    </div>
                    @php $j++; @endphp
                @endforeach
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
@endisset

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
                        <?php $bg_url = asset('images/1-dota.jpg'); ?>
                    @endif
                    <a  href="{{ route('posts-show', ['slug' => $value->post_slug]) }}"
                        class="img-wrapper" style="background-image: url('{{ $bg_url }}')"></a>
                    <div class="card-body">
                        <a href="{{ route('posts-show', ['slug' => $value->post_slug]) }}" class="text-decoration-none">
                            <h5 class="card-title">{{ $value->post_title }}</h5>
                        </a>
                        <p class="card-text">
                            {{-- belum : buat helper --}}
                            {{ strip_tags(htmlspecialchars_decode(Str::limit($value->post_content, 150, '...'))) }}
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
<div class="container my-5 fp-inbox-container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="mb-3">{{ __('Feel free to contact us') }}</h3>
            <form action="" method="POST" id="fp_inbox">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input  type="name" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}">
                    @error('name') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email address') }}</label>
                    <input  type="email" class="form-control @error('name') is-invalid @enderror"
                            name="email" value="{{ old('name') }}">
                    @error('email') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">{{ __('Your message') }}</label>
                    <textarea   class="form-control @error('name') is-invalid @enderror"
                                name="message" rows="6">{{ old('message') }}</textarea>
                    @error('message') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <input type="submit" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="alert alert-success alert-dismissible fade fp-inbox" role="alert">
    <strong>Success</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

@section('script')
<script>
$(function(){

    $('form#fp_inbox').on('submit', function(e){
        e.preventDefault();

        var fp_container = $('.fp-inbox-container'),
        set_name = fp_container.find('[name=name]').val(),
        set_email = fp_container.find('[name=email]').val(),
        set_message = fp_container.find('[name=message]').val();

        if ( set_name != '' && set_email != '' && set_message != '' ) {

            var inbox_data = {
                name: set_name,
                email: set_email,
                message: set_message
            };
            console.log(inbox_data);
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                dataType: 'JSON',
                url: "{{ route('fp-store-inbox') }}",
                data: inbox_data,
                beforeSend: function(){
                    console.log('loading...');
                    fp_container.find('[type=submit]').attr('value', 'Loading...');
                },
                success: function (res) {
                    fp_container.find('[type=submit]').attr('value', 'Submit');
                    $('.fp-inbox').addClass('show');
                },
            });
        }else{
            alert('All field must be filled !');
        }

    })






})
</script>
@endsection
