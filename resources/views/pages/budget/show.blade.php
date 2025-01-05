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
                    <h2>Rp {{ App\Helpers\MyHelper::rupiah(App\Helpers\BudgetHelper::getTotalBudget($province->id)) ?? '' }}
                    </h2>
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
                                    <th>Relokasi</th>
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
                                        <td>
                                            @if ($budget->relocationsFrom->isNotEmpty() || $budget->relocationsTo->isNotEmpty())
                                                <button class="btn btn-primary btn-sm view-relocation-btn"
                                                    data-budget-id="{{ $budget->id }}">
                                                    View Relocation Details
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="w-25">
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
    <div class="modal fade" id="relocationModal" tabindex="-1" aria-labelledby="relocationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="relocationModalLabel">Relocation Details</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Status:</strong> <span id="modalBudgetStatus">-</span></p>
                    <div id="relocationInfo">
                        <p><strong>Relocation:</strong> <span id="modalRelocation">-</span></p>
                        <p><strong>Amount:</strong> <span id="modalAmount">-</span></p>
                    </div>
                    <p id="noRelocationData" class="text-muted">No relocation data found.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('addon-script')
    <script>
        function confirmDelete(itemId) {
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
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteBudget' + itemId).submit();
                }
            });
        }

        $(document).on('click', '.view-relocation-btn', function() {
            const budgetId = $(this).data('budget-id');

            // Clear modal content
            $('#modalBudgetId').text('-');
            $('#modalBudgetStatus').text('-');
            $('#modalRelocation').text('-');
            $('#modalAmount').text('-');
            $('#relocationInfo').hide();
            $('#noRelocationData').hide();

            // Fetch data from API
            $.ajax({
                url: `/api/relocations/${budgetId}`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const budget = response.budget;
                        const relocationInfo = response.relocation_info;
                        $('#modalBudgetStatus').text(budget.status);

                        if (relocationInfo) {
                            $('#relocationInfo').show();
                            if (budget.status === 'Keluar') {
                                $('#modalRelocation').text(
                                    `To Province: ${relocationInfo.province.name}`);
                            } else if (budget.status === 'Masuk') {
                                $('#modalRelocation').text(
                                    `From Province: ${relocationInfo.province.name}`);
                            }
                            if (budget.status === 'Keluar') {
                                $('#modalAmount').text(`- ${formatRupiah(relocationInfo.amount.toString(), 'Rp')}`);
                                $('#modalAmount').addClass('text-danger');
                            } else if (budget.status === 'Masuk') {
                                $('#modalAmount').text(`+ ${formatRupiah(relocationInfo.amount.toString(), 'Rp')}`);
                                $('#modalAmount').addClass('text-success');
                            }

                           
                        } else {
                            $('#noRelocationData').show();
                        }
                    }
                },
                error: function() {
                    alert('Failed to fetch relocation details.');
                },
            });

            // Show modal
            $('#relocationModal').modal('show');

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

        });
    </script>
@endpush
