@extends('layouts.main')

@section('title', 'Province Master')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <a type="button" href="{{ route('province.index') }}"
                            class="d-flex justify-content-center align-items-center btn btn-inverse-primary btn-rounded btn-icon">
                            <i class="ti-arrow-left"></i>
                        </a>
                        <h3 class="font-weight-bold m-0">{{ isset($province) ? 'Edit Province ' . $province->name : 'Add New Province' }}</h3>

                    </div>
                    <h6 class="font-weight-normal mb-0">All regions are well-organized!</h6>
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
                    <form action="{{ isset($province) ? route('province.update', $province->id) : route('province.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @if (isset($province))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Province</label>
                            <input type="text" class="form-control form-control-sm @error('name') is_invalid @enderror"
                                id="name" name="name" value="{{ old('name', $province->name ?? '') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="nama_manager_unit_layanan" class="form-label">Nama Manager Unit Layanan</label>
                            <input type="text" class="form-control form-control-sm @error('nama_manager_unit_layanan') is_invalid @enderror"
                                id="nama_manager_unit_layanan" name="nama_manager_unit_layanan" value="{{ old('nama_manager_unit_layanan', $province->nama_manager_unit_layanan ?? '') }}" required>
                            @error('nama_manager_unit_layanan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input type="text" class="form-control form-control-sm @error('jabatan') is_invalid @enderror"
                                id="jabatan" name="jabatan" value="{{ old('jabatan', $province->jabatan ?? '') }}" required>
                            @error('jabatan')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">{{ isset($province) ? 'Update' : 'Create' }}</button>
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
