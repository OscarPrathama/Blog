@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col col-md-6 col-sm-12">
            <h3 class="mb-4">General Settings</h3>
            <form action="{{ route('settings.updateOrCreate') }}" method="POST">
                @csrf
                <table cellpadding="10" class="w-100">
                    <input type="hidden" name="setting_id" value="{{ $site_setting->id }}">
                    <tr>
                        <td>Site Title</td>
                        <td>
                            <input
                                type="text"
                                name="setting_name"
                                id=""
                                class="form-control @error('setting_name') is-invalid @enderror"
                                value="{{ old('setting_name') ?? $site_setting->setting_name }}" />
                            @error('setting_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </td>
                    </tr>
                    <tr>
                        <td>Tagline</td>
                        <td>
                            <input
                                type="text"
                                name="setting_value"
                                id=""
                                class="form-control @error('setting_value') is-invalid @enderror"
                                value="{{ old('setting_value') ?? $site_setting->setting_value }}" />
                            @error('setting_value') <div class="invalid-fedback">{{ $message }}</div> @enderror
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit" class="btn btn-primary"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
@stop
