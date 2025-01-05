@extends('layouts.main')

@section('title', 'Users Management')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Users Management</h3>
                    <h6 class="font-weight-normal mb-0">Manage user accounts and permissions efficiently.</h6>
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
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="example" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Created At</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Province</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($users as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ App\Helpers\MyHelper::ubahFormatTimestamp($user->created_at) }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if ($user->role_id == 1)
                                                        <span class="badge bg-success text-white">{{ $user->role->name }}</span>
                                                    @else
                                                        <span class="badge bg-warning text-white">{{ $user->role->name }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($user->is_active == 1)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>{{ $user->province->name ?? '-' }}</td>
                                                <td>
                                                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-primary btn-icon-text"><i class="ti-pencil btn-icon-prepend"></i>Edit</a>
                                                    <button data-id="{{ $user->id }}" data-name="{{ $user->name }}" class="btn btn-sm btn-danger btn-delete"><i class="ti-trash btn-icon-prepend"></i>Delete</button>
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
        </div>
    </div>
@endsection

@push('addon-script')
    <script>
        $('#example').DataTable();
    </script>

<script>
    $(document).on('click', '.btn-delete', function () {
        const userId = $(this).data('id');
        const userName = $(this).data('name');

        Swal.fire({
            title: "Konfirmasi Hapus",
            text: `Apakah Anda yakin ingin menghapus user "${userName}"?`,
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
                    url: `/user/${userId}`,
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