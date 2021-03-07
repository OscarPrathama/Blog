@extends('main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container my-4 frontpage">
    <div class="row">
        <div class="col-md-12">
            Frontpage
        </div>
    </div>
</div>
@stop