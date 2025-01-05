@extends('layouts.main')

@section('title', 'Show Details Budget')

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
                            Budget {{ $province->name }}</h3>
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
    <div class="row mb-3">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h5><i class="ti-flag-alt me-2 text-primary"></i>Total Budget Sekarang</h5>
                    <h2>Rp {{ App\Helpers\MyHelper::rupiah(App\Helpers\BudgetHelper::getTotalBudget($province->id)) ?? '' }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h5><i class="ti-arrow-down me-2 text-success"></i>Budget Masuk</h5>
                    <h2>Rp {{ App\Helpers\MyHelper::rupiah($lastTotalBudgetMasuk) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h5><i class="ti-arrow-up me-2 text-danger"></i>Budget Keluar</h5>
                    <h2>Rp {{ App\Helpers\MyHelper::rupiah($lastTotalBudgetKeluar) }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="budget_table" class="display expandable-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Created By</th>
                                    <th>Description</th>
                                    <th class="w-25">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($province->budgets as $index => $budget)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>Rp. {{ App\Helpers\MyHelper::rupiah($budget->amount) ?? '' }}</td>
                                        <td>
                                            @if ($budget->status == 'Masuk')
                                                <span class="text-success">{{ $budget->status }}</span>
                                            @elseif ($budget->status == 'Keluar')
                                                <span class="text-danger">{{ $budget->status }}</span>
                                            @endif
                                        </td>
                                        {{-- <td>Rp. {{ App\Helpers\MyHelper::rupiah($budget->total_amount) ?? '' }}</td> --}}
                                        <td>{{ App\Helpers\MyHelper::ubahFormatTimestamp($budget->updated_at) ?? '' }}</td>
                                        <td>{{ $budget->user->name }}</td>
                                        <td>{{ $budget->description ?? '-' }}</td>
                                        <td class="w-25">
                                            {{-- <a href="{{ route('budget.show', App\Helpers\MyHelper::encodeID($budget->province_id)) }}" class="btn btn-sm btn-warning btn-icon-text"><i class="ti-eye btn-icon-prepend"></i>Show Detail</a>
                                        <button data-bs-toggle="modal" data-bs-target="#relocationModal{{ $budget->province_id }}"  class="btn btn-sm btn-success btn-icon-text"><i class="ti-direction-alt btn-icon-prepend"></i>Relocation</button> --}}
                                            <form
                                                action="{{ route('budget.destroy', App\Helpers\MyHelper::encodeID($budget->id)) }}"
                                                method="post" id="deleteBudget{{ $budget->id }}">
                                                @method('delete')
                                                @csrf
                                            </form>
                                            <button class="btn btn-sm btn-danger btn-delete"
                                                onclick="confirmDelete({{ $budget->id }})"><i
                                                    class="ti-trash btn-icon-prepend"></i>Delete</button>
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

@endsection

@push('addon-script')
    <script>
        function confirmDelete(itemId) {
            console.log(itemId);
            return Swal.fire({
                title: 'Are you sure?',
                text: "Data will be deleted permanently",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete!',
                background: '#ffff',
                color: "#575656",
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton : 'btn btn-danger'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteBudget' + itemId).submit();
                }
            });
        }
    </script>
@endpush
