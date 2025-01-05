@extends('layouts.main')

@section('title', 'Users Management')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <a type="button" href="{{ route('user.index') }}"
                            class="d-flex justify-content-center align-items-center btn btn-inverse-primary btn-rounded btn-icon">
                            <i class="ti-arrow-left"></i>
                        </a>
                        <h3 class="font-weight-bold m-0">{{ isset($user) ? 'Edit User ' . $user->name : 'Add New User' }}</h3>

                    </div>
                    <h6 class="font-weight-normal mb-0">Manage user accounts and permissions efficiently.</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 mb-3">
        @include('includes.alert')
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="{{ isset($user) ? route('user.update', $user->id) : route('user.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @if (isset($user))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control form-control-sm @error('name') is_invalid @enderror"
                                id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control form-control-sm @error('email') is_invalid @enderror"
                                id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role_id" class="form-label">Role</label>
                            <select class="form-select @error('role_id') is_invalid @enderror" id="role_id" name="role_id"
                                required>
                                <option value="" disabled {{ isset($user) ? '' : 'selected' }}>Pilih Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="province_id" class="form-label">Province</label>
                            <select class="form-select @error('province_id') is_invalid @enderror" id="province_id"
                                name="province_id" required>
                                <option value="" disabled {{ isset($user) ? '' : 'selected' }}>Pilih Province
                                </option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}"
                                        {{ old('province_id', $user->province_id ?? '') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if (isset($user))
                            <div class="form-check mb-3">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ $user->is_active == true ? 'checked' : '' }}> Active 
                                <i class="input-helper"></i></label>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Update' : 'Create' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('addon-script')
    <script>
        $('#province_id').select2();
    </script>
@endpush
