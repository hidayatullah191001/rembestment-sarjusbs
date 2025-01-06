@extends('layouts.main')

@section('title', 'User Profile')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Setting Profile</h3>
                    <h6 class="font-weight-normal mb-0">Manage user accounts and permissions efficiently.</h6>
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
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                
                        <div class="mb-3">
                            <label for="photo_profile" class="form-label">Photo Profile</label>
                            <input type="file" class="form-control form-control-sm @error('photo_profile') is-invalid @enderror" id="photo_profile" name="photo_profile">
                            @error('photo_profile')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            @if ($user->photo_profile)
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $user->photo_profile) }}" alt="Profile Photo" width="150" class="img-thumbnail">
                                </div>
                            @endif
                        </div>
                
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation">
                        </div>
                
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>        
@endsection
