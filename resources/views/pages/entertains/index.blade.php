@extends('layouts.main')
@section('title', 'User Entertain')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">User Entertain</h3>
                    <h6 class="font-weight-normal mb-0">Efficiently manage your reimbursement submissions with accurate and
                        timely requests.
                    </h6>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="justify-content-end d-flex">
                        <a href="{{ route('budget.create') }}" class="btn btn-block btn-outline-primary btn-icon-text"><i
                                class="ti-plus btn-icon-prepend"></i>Create New Budget</a>
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
                    <form action="/export-user-entertain" method="get">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date:</label>
                                    <input type="date" id="start_date" name="start_date"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">End Date:</label>
                                    <input type="date" id="end_date" name="end_date"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-icon-text"><i
                                class="ti-export btn-icon-prepend"></i>Export Excel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="entertain_table" class="display expandable-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>From Province</th>
                                    <th>Entertain Value</th>
                                    <th>Created At</th>
                                    <th class="w-25">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($userEntertains as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->user->email }}</td>
                                        <td>{{ $item->user->province->name }}</td>
                                        <td>Rp. {{ App\Helpers\MyHelper::rupiah($item->nilai_entertain) }}</td>
                                        <td>{{ App\Helpers\MyHelper::ubahFormatTimestamp($item->created_at) }}</td>
                                        <td class="w-25">
                                            <a href="{{ route('entertain.show', App\Helpers\MyHelper::encodeID($item->id)) }}"
                                                class="btn btn-sm btn-warning btn-icon-text"><i
                                                    class="ti-eye btn-icon-prepend"></i>Show Detail</a>
                                            <button id="{{ App\Helpers\MyHelper::encodeID($item->id) }}"
                                                class="btn btn-sm btn-danger btn-icon-text btn-generate"><i
                                                    class="ti-import btn-icon-prepend"></i>Generate PDF</button>
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
        $(document).ready(function() {
            // Simpan template HTML button untuk reset nanti
            const buttonTemplate = '<i class="ti-import btn-icon-prepend"></i>Generate PDF';

            function setLoadingState(button, isLoading) {
                if (isLoading) {
                    // Disable button dan tambahkan loading state
                    button.prop('disabled', true)
                        .html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                            );
                } else {
                    // Kembalikan button ke kondisi normal
                    button.prop('disabled', false)
                        .html(buttonTemplate);
                }
            }

            $("#entertain_table").DataTable();

            $(".btn-generate").click(async function() {
                // Simpan reference ke button yang diklik
                const clickedButton = $(this);
                var id = clickedButton.attr("id");

                setLoadingState(clickedButton, true);

                try {
                    var csrfToken = $("meta[name='csrf-token']").attr("content");
                    // Mengirim request AJAX untuk generate PDF
                    const response = await $.ajax({
                        url: `/api/generatePdf/${id}`,
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        processData: false,
                        contentType: false
                    });

                    if (response.success) {
                        // Mengonversi base64 ke Blob dan memulai unduhan
                        const binaryString = window.atob(response.pdf_content);
                        const bytes = new Uint8Array(binaryString.length);
                        for (let i = 0; i < binaryString.length; i++) {
                            bytes[i] = binaryString.charCodeAt(i);
                        }
                        const blob = new Blob([bytes.buffer], {
                            type: 'application/pdf'
                        });
                        const url = window.URL.createObjectURL(blob);

                        const link = document.createElement('a');
                        link.href = url;
                        link.download = response.file_name;
                        document.body.appendChild(link);
                        link.click();

                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(link);

                        Swal.fire({
                            title: "Success!",
                            text: "PDF berhasil diunduh!",
                            icon: "success",
                            confirmButtonText: "OK",
                            allowOutsideClick: false,
                            background: "#fff",
                            color: "#575656",
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        }).then(() => {
                            window.location.href = response.redirect;
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: response.message || "Terjadi kesalahan!",
                            icon: "error",
                            confirmButtonText: "OK",
                            allowOutsideClick: false,
                            background: "#fff",
                            color: "#575656",
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        title: "Error!",
                        text: "Gagal generate pdf: " + (error.responseJSON?.message || error
                            .message),
                        icon: "error",
                        confirmButtonText: "OK",
                        allowOutsideClick: false,
                        background: "#fff",
                        color: "#575656",
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                } finally {
                    setLoadingState(clickedButton, false);
                }
            });
        });
    </script>
@endpush
