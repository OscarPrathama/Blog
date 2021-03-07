@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            {{ 'Hello Dashboard' }}
        </div>
    </div>
</div>
@stop