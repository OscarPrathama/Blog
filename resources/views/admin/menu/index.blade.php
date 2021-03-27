@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <h3>Menus</h3>
            <form action="{{ route('menus.store') }}" method="post">
                @csrf
                <input type="hidden" name="menu_position" value="main_menu">
                <div id="InputsWrapper">
                    @if (!empty($main_menu))
                        @php $i = 1; @endphp
                        @foreach ($main_menu['menus'] as $key => $value)
                            <div id="field_{{ $i }}" class="field-menu-wrapper">
                                <input type="text" name="menus[{{ $key }}][menu_title]" value="{{ $value->menu_title }}" placeholder="Menu Title" class="my-form-control" required />
                                <input type="text" name="menus[{{ $key }}][menu_link]" value="{{ $value->menu_link }}" placeholder="Menu Link" class="my-form-control" size="30" required />
                                <a href="javascript:void(0)" class="removeclass text-decoration-none"></a>
                            </div>
                            @php $i++; @endphp
                        @endforeach
                    @endif
                </div>

                <a href="javascript:void(0)" id="AddField" class="btn btn-success mt-3">Add field</a>
                <br><br>

                <input type="submit" id="submit" name="save_menu" value="Save" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>
@stop
