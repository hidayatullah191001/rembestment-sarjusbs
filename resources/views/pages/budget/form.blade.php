@extends('layouts.main')

@section('title', 'Budget')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <a type="button" href="{{ route('budget.index') }}"
                            class="d-flex justify-content-center align-items-center btn btn-inverse-primary btn-rounded btn-icon">
                            <i class="ti-arrow-left"></i>
                        </a>
                        <h3 class="font-weight-bold m-0">
                            {{ isset($budget) ? 'Edit Budget #' . $budget->id : 'Create new Budget' }}</h3>

                    </div>
                    <h6 class="font-weight-normal mb-0">Ensure efficient budget management and maintain financial stability.
                    </h6>
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
                    <form action="{{ route('budget.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($budget))
                            @method('PUT')
                        @endif
                        <div class="mb-3">
                            <label for="province_id" class="form-label">Province Name</label>
                            <select class="form-select @error('province_id') is_invalid @enderror" id="province_id"
                                name="province_id" required>
                                <option value="" disabled {{ isset($user) ? '' : 'selected' }}>Pilih Province
                                </option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}"
                                        {{ old('province_id', $budget->province_id ?? '') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control currency-input form-control-sm"
                                    name="amount" id="amount"
                                    value="{{ old('amount', $bugdet->amount ?? '') }}"
                                    required>
                                <input type="hidden" name="amount_value"
                                    id="amount_value"
                                    value="{{ old('amount', $bugdet->amount ?? '') }}">
                            </div>
                            @error('amount')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Description (Opsional)</label>
                            <input type="text" class="form-control form-control-sm @error('description') is_invalid @enderror"
                                id="description" name="description" value="{{ old('description', $budget->description ?? '') }}">
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">{{ isset($budget) ? 'Update' : 'Create' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('addon-script')
    <script>
        $('#province_id').select2();

        $('.currency-input').on('input', function() {
            let value = $(this).val();

            // Hapus semua karakter kecuali angka
            value = value.replace(/[^\d]/g, '');

            // Format ke rupiah
            $(this).val(formatRupiah(value));

            // Simpan nilai numerik ke hidden input
            let numericValue = getNominal(value);
            let hiddenInput = $(this).attr('id') + '_value';
            $('#' + hiddenInput).val(numericValue);
        });

        function formatRupiah(angka, prefix) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }

        function getNominal(rupiah) {
            if (typeof rupiah !== "string") return rupiah;
            return parseInt(rupiah.replace(/,.*|[^0-9]/g, ''));
        }
    </script>
@endpush
