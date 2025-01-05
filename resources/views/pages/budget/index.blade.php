@extends('layouts.main')

@section('title', 'Budget')

@push('addon-style')
    <style>
        input[readonly] {
    background-color: #e9ecef !important; /* Warna abu-abu ringan */
    cursor: not-allowed;
}
    </style>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Budget</h3>
                <h6 class="font-weight-normal mb-0">Ensure efficient budget management and maintain financial stability.</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="{{ route('budget.create') }}" class="btn btn-block btn-outline-primary btn-icon-text"><i class="ti-plus btn-icon-prepend"></i>Create New Budget</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class=" mb-3">
    @include('includes.alert')
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="budget_table" class="display expandable-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Province</th>
                                <th>Total Budget</th>
                                <th>Last Updated</th>
                                <th class="w-25">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($budgets as $index => $budget)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $budget->province->name }}</td>
                                    <td>Rp. {{ App\Helpers\MyHelper::rupiah(App\Helpers\BudgetHelper::getTotalBudget($budget->province_id)) ?? '' }}</td>
                                    <td>{{ App\Helpers\MyHelper::ubahFormatTimestamp($budget->updated_at) ?? '' }}</td>
                                    <td class="w-25">
                                        <a href="{{ route('budget.show', App\Helpers\MyHelper::encodeID($budget->province_id)) }}" class="btn btn-sm btn-warning btn-icon-text"><i class="ti-eye btn-icon-prepend"></i>Show Detail</a>
                                        <button data-bs-toggle="modal" data-bs-target="#relocationModal{{ $budget->province_id }}"  class="btn btn-sm btn-success btn-icon-text"><i class="ti-direction-alt btn-icon-prepend"></i>Relocation</button>
                                        {{-- <button data-id="{{ $budget->province_id }}" data-name="{{ $budget->name }}" class="btn btn-sm btn-danger btn-delete""><i class="ti-trash btn-icon-prepend"></i>Delete</button> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('pages.budget.relocation')
@endsection


@push('addon-script')
    <script>
        $(document).ready(function() {
            $('#budget_table').DataTable();
            $('[id^="to_province"]').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                    width: '100%'
                });
            });
        }); 

        $('#province_id').select2();

        $('.currency-input').on('input', function() {
            let value = $(this).val();
            let id = $(this).attr('id').split('amount_relocation')[1]; // Mendapatkan ID unik

            // Hapus semua karakter kecuali angka
            value = value.replace(/[^\d]/g, '');

            // Format ke rupiah
            $(this).val(formatRupiah(value));

            // Simpan nilai numerik ke hidden input yang sesuai dengan ID unik
            let numericValue = getNominal(value);
            let hiddenInput = '#amount_relocation_value' + id;  // ID unik hidden input
            $(hiddenInput).val(numericValue);
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