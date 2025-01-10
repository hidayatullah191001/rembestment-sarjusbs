@extends('layouts.main')

@section('title', 'User Entertain')

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
                            User Entertain {{ $userEntertain->user->name }} -
                            {{ App\Helpers\MyHelper::ubahFormatTimestamp($userEntertain->created_at) }}</h3>
                    </div>
                    <h6 class="font-weight-normal mb-0">Efficiently manage your reimbursement submissions with accurate and
                        timely requests.
                    </h6>

                </div>
                <div class="col-12 col-xl-4">
                    <div class="justify-content-end d-flex">
                        <form action="{{ route('entertain.destroy', App\Helpers\MyHelper::encodeID($userEntertain->id)) }}"
                            method="post" id="deleteEntertain">
                            @method('delete')
                            @csrf
                        </form>
                        <button class="btn btn-block btn-outline-danger btn-icon-text"
                        onclick="confirmDelete()"><i
                            class="ti-trash btn-icon-prepend"></i>Delete User Entertain</button>
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
                        <table id="entertain_table" class="table table-striped" style="width:100%">
                            <tbody>
                                <tr>
                                    <th>User Create</th>
                                    <td>{{ $userEntertain->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>User From Province</th>
                                    <td>{{ $userEntertain->user->province->name }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Account Manager</th>
                                    <td>{{ $userEntertain->nama_account_manager }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Kanwil Manager</th>
                                    <td>{{ $userEntertain->nama_kanwil_manager }}</td>
                                </tr>
                                <tr>
                                    <th>Hari</th>
                                    <td>{{ $userEntertain->hari }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ $userEntertain->tanggal }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu</th>
                                    <td>{{ $userEntertain->waktu }}</td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td>{{ $userEntertain->type->name }}</td>
                                </tr>
                                <tr>
                                    <th>Nilai Entertain</th>
                                    <td>{{ $userEntertain->nilai_entertain }}</td>
                                </tr>
                                <tr>
                                    <th>Revenue</th>
                                    <td>{{ $userEntertain->revenue }}</td>
                                </tr>
                                <tr>
                                    <th>Pelanggan</th>
                                    <td>{{ $userEntertain->pelanggan }}</td>
                                </tr>
                                <tr>
                                    <th>Peserta</th>
                                    <td>
                                        @foreach ($userEntertain->peserta as $index => $item)
                                        <p>{{ $index + 1 }}. {{ $item->nama_pelanggan }}
                                            {{ $item->internal_icon ? '-' . $item->internal_icon : '' }}</p>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Topik</th>
                                    <td>{{ $userEntertain->topik }}</td>
                                </tr>
                                <tr>
                                    <th>Aktivitas</th>
                                    <td>{{ $userEntertain->aktivitas }}</td>
                                </tr>
                                <tr>
                                    <th>Target Pelaksanaan</th>
                                    <td>{{ $userEntertain->target_pelaksanaan }}</td>
                                </tr>
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
    function confirmDelete() {
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
                    $('#deleteEntertain').submit();
                }
            });
        }
    </script>
@endpush