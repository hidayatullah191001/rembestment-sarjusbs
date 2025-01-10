@extends('layouts.main')

@section('title', 'App Settings')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">App Settings</h3>
                <h6 class="font-weight-normal mb-0">Customize your app preferences and configure settings to enhance your experience.</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('user.create') }}" class="btn btn-block btn-outline-primary btn-icon-text"><i class="ti-plus btn-icon-prepend"></i>Add New User</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mb-3">
    @include('includes.alert')
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
            
                    <div class="mb-3">
                        <label for="app_name" class="form-label">App Name</label>
                        <input type="text" class="form-control form-control-sm" id="app_name" name="app_name" value="{{ $settings['app_name'] ?? '' }}" required>
                    </div>
            
                    <div class="mb-3">
                        <label for="logo_large" class="form-label">Icon App</label>
                        <input type="file" class="form-control form-control-sm" id="icon_app" name="icon_app">
                        @if (!empty($settings['icon_app']))
                            <img src="{{ asset('storage/' . $settings['icon_app']) }}" alt="Logo Large" width="150" class="mt-2">
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label for="logo_large" class="form-label">Logo Large</label>
                        <input type="file" class="form-control form-control-sm" id="logo_large" name="logo_large">
                        @if (!empty($settings['logo_large']))
                            <img src="{{ asset('storage/' . $settings['logo_large']) }}" alt="Logo Large" width="150" class="mt-2">
                        @endif
                    </div>
            
                    <div class="mb-3">
                        <label for="logo_mini" class="form-label">Logo Mini</label>
                        <input type="file" class="form-control form-control-sm" id="logo_mini" name="logo_mini">
                        @if (!empty($settings['logo_mini']))
                            <img src="{{ asset('storage/' . $settings['logo_mini']) }}" alt="Logo Mini" width="100" class="mt-2">
                        @endif
                    </div>
            
                    <div class="mb-3">
                        <label for="app_footer" class="form-label">Footer Text</label>
                        <input type="text" class="form-control form-control-sm" id="app_footer" name="app_footer" value="{{ $settings['app_footer'] ?? '' }}" required>
                    </div>
            
                    <div class="mb-3">
                        <label for="app_url" class="form-label">App URL</label>
                        <input type="text" class="form-control form-control-sm  " id="app_url" value="{{ $settings['app_url'] ?? config('app.url') }}" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nama_manager_pemasaran" class="form-label">Nama Manager Pemasaran</label>
                        <input type="text" class="form-control form-control-sm" name="nama_manager_pemasaran" value="{{ $settings['nama_manager_pemasaran'] ?? '' }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="jabatan_lengkap" class="form-label">Jabatan Lengkap</label>
                        <input type="text" class="form-control form-control-sm" name="jabatan_lengkap" value="{{ $settings['jabatan_lengkap'] ?? '' }}" required>
                    </div>
            
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection