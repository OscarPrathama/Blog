@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container media-container my-5">
    <div class="row">
        <div class="col-md-12">
            <h3>Images</h3>
        </div>
    </div>
    <div class="row">

        @foreach ($images as $key => $value)
            @if( isset($value->url) )
                <div class="col-md-3">
                    <img
                    src="{{ asset('storage'.$value->url) }}"
                    alt="{{ $value->just_name }}"
                    class="w-75 p-2" >
                </div>
            @endif
        @endforeach

    </div>
</div>
@stop
