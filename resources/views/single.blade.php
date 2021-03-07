@extends('main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container my-4 single-post">
    <div class="row">
        <div class="col-md-12">
            <h3 class="post-title">{{ $post->post_title }}</h3>
            <div class="post-date my-3 fs-6">
                <small>Created at : <i>{{ $post->created_at->diffForHumans() }}</i></small>
            </div>
            <div class="post-content">
                {!! nl2br($post->post_content) !!}
            </div>
        </div>
    </div>
</div>
@stop