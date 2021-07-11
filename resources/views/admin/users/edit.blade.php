@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container mt-4">

    {{-- title --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <h3>{{ __('Edit User') }}</h3>
        </div>
    </div>

    {{-- content --}}
    <form
        action="{{ route('users.update', $user->id) }}" method="POST" class="row"
        enctype="multipart/form-data">

        {{-- user image --}}
        <div class="col-md-3">
            <div class="thumbmail-wrapper">
                @if ( isset($user->image) && !empty($user->image->url))
                    <img
                        src="{{ asset('storage'.$user->image->url) }}"
                        class="img-thumbnail d-block" alt="{{ $user->name }}"
                        id="imgPreview">
                @else
                    <img
                        src="{{ imgDefault('profile.png') }}"
                        class="img-thumbnail d-block" alt="{{ $user->name }}"
                        id="imgPreview">
                @endif
                <a href="#" class="d-lg-block text-decoration-none" id="removeImgPreview">Remove</a>
                <div class="my-2">
                    <input class="form-control" type="file" id="user_img" name="user_img">
                </div>
            </div>
        </div>

        {{-- user content --}}
        <div class="col-md-4">

            @csrf
            @method('PATCH')
            <div class="mb-3">
                <input type="hidden" value="{{ $user->id }}" name="user_id">
                <input
                    type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    placeholder="Name" value="{{ old('name') ?? $user->name }}" autofocus>
                    @error('name') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <input
                    type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    placeholder="Email" value="{{ old('email') ?? $user->email }}" >
                    @error('email') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <strong>Role : </strong><br>
                <select name="roles[]" multiple class="form-control">
                    @forelse ($roles as $item)
                        <option value="{{ $item }}" {{ in_array($item, $userRole) ? "selected" : "" }}>
                            {{ $item }}
                        </option>
                    @empty
                        -
                    @endforelse
                </select>
                @error('roles') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <input
                    type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Password" >
                    @error('password') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <input
                    type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                    placeholder="Confirm Password" >
                    @error('password_confirmation') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <input type="submit" class="btn btn-dark" value="Update">
            </div>

        </div>
    </form>

</div>
@stop

@push('script2')
<script>
$(function(){
    let img_preview = $('#imgPreview');

    function imgSrcCheck(){
        if( img_preview.attr('src').length != '' ){
            $('a#removeImgPreview').css('display', 'block');
        }else{
            $('a#removeImgPreview').css('display', 'none');
        }
    }

    $('a#removeImgPreview').on('click', function(){
        if(confirm('Are you sure ?') == true){
            img_preview.attr('src', '');
            img_preview.attr('alt', '');
            $('[name=user_img]').val('');
            imgSrcCheck();

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: "{{ route('user.edit.remove.image') }}",
                data: {
                    user_id: $('[name=user_id]').val()
                }
            });
        }
    });


})
</script>
@endpush
