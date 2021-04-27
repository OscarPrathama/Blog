@extends('main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
{{-- banner --}}
<div class="contact-us-banner d-flex justify-content-center"
     style="background:url( {{ imgDefault() }} )">
</div>

{{-- content --}}
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
@stop
