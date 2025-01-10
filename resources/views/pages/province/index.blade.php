@extends('layouts.main')

@section('title', 'Province Management')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Province Management</h3>
                    <h6 class="font-weight-normal mb-0">All regions are well-organized!</h6>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="justify-content-end d-flex">
                        <a href="{{ route('province.create') }}" class="btn btn-block btn-outline-primary btn-icon-text"><i class="ti-plus btn-icon-prepend"></i>Add New Province</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 mb-3">
        @include('includes.alert')
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="display expandable-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Created At</th>
                                    <th>Name</th>
                                    <th>Nama Manager Unit Layanan</th>
                                    <th>Jabatan Manager Unit Layanan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($provinces as $index => $province)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ App\Helpers\MyHelper::ubahFormatTimestamp($province->created_at) }}</td>
                                        <td>{{ $province->name }}</td>
                                        <td>{{ $province->nama_manager_unit_layanan }}</td>
                                        <td>{{ $province->jabatan }}</td>
                                        <td>
                                            <a href="{{ route('province.edit', $province->id) }}" class="btn btn-sm btn-primary btn-icon-text"><i class="ti-pencil btn-icon-prepend"></i>Edit</a>
                                            <button data-id="{{ $province->id }}" data-name="{{ $province->name }}" class="btn btn-sm btn-danger btn-delete""><i class="ti-trash btn-icon-prepend"></i>Delete</button>
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
        $('#example').DataTable();
    </script>

<script>
    $(document).on('click', '.btn-delete', function () {
        const provinceId = $(this).data('id');
        const userName = $(this).data('name');

        Swal.fire({
            title: "Konfirmasi Hapus",
            text: `Apakah Anda yakin ingin menghapus provinsi "${userName}"?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus",
            cancelButtonText: "Batal",
            background: "#fff",
            color: "#575656",
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim permintaan AJAX untuk menghapus data
                $.ajax({
                    url: `/province/${provinceId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: response.message,
                                icon: "success",
                                confirmButtonText: "OK",
                                background: "#fff",
                                color: "#575656",
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            }).then(() => {
                                // Refresh halaman atau hapus elemen di tabel
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal!",
                                text: response.message,
                                icon: "error",
                                confirmButtonText: "OK",
                                background: "#fff",
                                color: "#575656",
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                        }
                    },
                    error: function (error) {
                        Swal.fire({
                            title: "Gagal!",
                            text: "Terjadi kesalahan saat menghapus data.",
                            icon: "error",
                            confirmButtonText: "OK",
                            background: "#fff",
                            color: "#575656",
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                    }
                });
            }
        });
    });
</script>

@endpush