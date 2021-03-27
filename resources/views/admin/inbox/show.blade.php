@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@endsection

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <h3 class="float-left">{{ __('Inbox') }}</h3>
            <span>
                <a href="javascript:void(0)" id="inbox-destroy" class="text-decoration-none">{{ __('Remove') }}</a>
            </span>
            <hr>
            <input type="hidden" name="inbox_id" value="{{ $inbox->id }}">
            <p><b>Name :</b> {{ $inbox->name }}</p>
            <p><b>Email :</b> {{ $inbox->email }}</p>
            <p><b>Message :</b><br> {{ $inbox->message }}</p>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$(function(){
    $('#inbox-destroy').on('click', function(){
        if ( confirm('Delete it ?') ){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: "{{ route('ajax.inbox.delete') }}",
                data: {
                    inbox_id: $('[name=inbox_id]').val(),
                },
                success: function (res) {
                    window.location.href = "{{ route('inbox.index') }}";
                }
            });
        }
    });
});
</script>
@stop
