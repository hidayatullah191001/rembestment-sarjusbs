<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Rembestment Sarjusbs</title>
    @include('includes.style')
    @include('includes.style-welcome')
    <style>
        input[readonly] {
    background-color: #e9ecef !important; /* Warna abu-abu ringan */
    cursor: not-allowed;
}
    </style>
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="main-panel w-100">
                <div class="content-wrapper">
                    <div class="row text-center w-100 mx-0">
                        <div class="col-lg-4 mx-auto">
                            <div class="brand-logo">
                                <img src="{{ asset('storage/' . App\Helpers\MyHelper::getSetting('logo_large')) }}" alt="logo" />
                            </div>
                            <h4>Halo, Silahkan isi Form Entertain</h4>
                        </div>
                    </div>
                    <div class="row w-100 mx-0 mt-3">
                        <div class="col-lg-8 mx-auto">
                            <div class="step-indicator text-center">
                                <div class="step active" id="step-1">Informasi Dasar</div>
                                <div class="step" id="step-2">Informasi Entertain</div>
                                <div class="step" id="step-3">Informasi Tindak Lanjut</div>
                            </div>

                            <!-- Form Sections -->
                            <form id="entertainForm" method="post" action="{{ route('store-entertain') }}">
                                @csrf
                                <!-- Step 1: Informasi Dasar -->
                                <div class="form-section active" id="section-1">
                                    <div class="mb-3">
                                        <label class="form-label">Provinsi</label>
                                        <select class="form-select" name="province_id" id="provinsi" required>
                                            <option selected disabled>Pilih Provinsi</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}"
                                                    {{ old('province_id', $step1Data['province_id'] ?? '') == $province->id ? 'selected' : '' }}>
                                                    {{ $province->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="user">Data User</label>
                                        <select class="form-control select2" id="user" name="user_id">
                                            <option selected disabled>Pilih User</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control form-control-sm" id="nama_lengkap"
                                            name="nama_lengkap" value="{{ old('nama_lengkap', $step1Data['nama_lengkap'] ?? '') }}" readonly required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control form-control-sm" id="email"
                                            name="email" value="{{ old('email', $step1Data['email'] ?? '') }}" readonly required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Account Manager</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="{{ old('nama_account_manager', $step1Data['nama_account_manager'] ?? '') }}"
                                            name="nama_account_manager" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Kanwil Manager</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="{{ old('nama_kanwil_manager', $step1Data['nama_kanwil_manager'] ?? '') }}"
                                            name="nama_kanwil_manager" required>
                                    </div>
                                </div>

                                <!-- Step 2: Informasi Entertain -->
                                <div class="form-section" id="section-2">
                                    <div class="mb-3">
                                        <label class="form-label">Hari</label>
                                        <select class="form-select" name="hari" required>
                                            <option selected disabled>Pilih hari</option>
                                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                                <option value="{{ $hari }}"
                                                    {{ old('hari', $step2Data['hari'] ?? '') == $hari ? 'selected' : '' }}>
                                                    {{ $hari }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal</label>
                                        <input type="date" class="form-control form-control-sm" name="tanggal"
                                            value="{{ old('tanggal', $step2Data['tanggal'] ?? '') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Waktu</label>
                                        <input type="time" class="form-control form-control-sm" name="waktu"
                                            value="{{ old('waktu', $step2Data['waktu'] ?? '') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tipe</label>
                                        <select class="form-select" name="type_id" required>
                                            <option selected disabled>Pilih Tipe</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}"
                                                    {{ old('type_id', $step2Data['type_id'] ?? '') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nilai Entertain</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control currency-input form-control-sm"
                                                name="nilai_entertain" id="nilai_entertain"
                                                value="{{ old('nilai_entertain', $step2Data['nilai_entertain'] ?? '') }}"
                                                required>
                                            <input type="hidden" name="nilai_entertain_value"
                                                id="nilai_entertain_value"
                                                value="{{ old('nilai_entertain', $step2Data['nilai_entertain'] ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Revenue</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control currency-input form-control-sm"
                                                name="revenue" id="revenue"
                                                value="{{ old('nilai_entertain', $step2Data['nilai_entertain'] ?? '') }}"
                                                required>
                                            <input type="hidden" name="revenue_value" id="revenue_value"
                                                value="{{ old('nilai_entertain', $step2Data['nilai_entertain'] ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pelanggan</label>
                                        <input type="text" class="form-control form-control-sm" name="pelanggan"
                                            value="{{ old('pelanggan', $step2Data['pelanggan'] ?? '') }}" required>
                                    </div>

                                    <!-- Peserta Section -->
                                    <div id="pesertaContainer">
                                        <h5>Data Peserta (Opsional)</h5>
                                        <div class="peserta-item" id="peserta-0">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="mb-0">Peserta #1</h6>
                                                <button type="button" class="btn btn-danger btn-sm delete-peserta"
                                                    data-id="0">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama Pelanggan</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="peserta[0][nama_pelanggan]">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Internal Icon</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="peserta[0][internal_icon]">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-secondary" id="addPeserta">Tambah
                                        Peserta</button>
                                </div>

                                <!-- Step 3: Informasi Tindak Lanjut -->
                                <div class="form-section" id="section-3">
                                    <div class="mb-3">
                                        <label class="form-label">Topik</label>
                                        <textarea class="form-control" name="topik" rows="3" required>{{ old('topik', $step3Data['topik'] ?? '') }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Aktivitas</label>
                                        <textarea class="form-control" name="aktivitas" rows="3" required>{{ old('aktivitas', $step3Data['aktivitas'] ?? '') }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Target Pelaksanaan</label>
                                        <textarea class="form-control" name="target_pelaksanaan" rows="3" required>{{ old('target_pelaksanaan', $step3Data['target_pelaksanaan'] ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary" id="prevBtn"
                                        style="display: none;">Previous</button>
                                    <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                                    <button type="submit" class="btn btn-success" id="submitBtn"
                                        style="display: none;">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @include('includes.footer')
            </div>
        </div>
    </div>

    @include('includes.script')
    @include('includes.script-welcome')
    <script>
        $(document).ready(function() {
            $('#user').select2();
            // Fungsi untuk mendapatkan users berdasarkan province
            $('#provinsi').on('change', function() {
                var provinceId = $(this).val();

                // Reset dropdown user
                $('#user').html('<option value="">Pilih User</option>');

                // Ambil users berdasarkan province
                $.ajax({
                    url: `/get-users-by-province/${provinceId}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.length > 0) {
                            // Tambahkan users ke dropdown
                            $.each(response, function(index, user) {
                                $('#user').append(`<option value="${user.id}" 
                                    data-name="${user.name}" 
                                    data-email="${user.email}">
                                    ${user.name}
                                </option>`);
                            });

                            // Tambahkan opsi manual input
                            $('#user').append('<option value="manual">Input Manual</option>');
                        } else {
                            // Jika tidak ada user, langsung set ke manual input
                            $('#user').append('<option value="manual">Input Manual</option>');
                            $('#user').val('manual');
                            enableManualInput();
                        }
                    },
                    error: function() {
                        alert('Gagal mengambil data users');
                    }
                });

                // Reset input nama dan email
                $('#nama_lengkap, #email').val('');
            });

            // Handler untuk select user
            $('#user').on('change', function() {
                var selectedOption = $(this).find('option:selected');

                if ($(this).val() === 'manual') {
                    // Aktifkan input manual
                    enableManualInput();
                } else {
                    // Nonaktifkan input manual dan isi data
                    disableManualInput();

                    // Isi nama dan email dari data user
                    $('#nama_lengkap').val(selectedOption.data('name'));
                    $('#email').val(selectedOption.data('email'));
                }
            });

            // Fungsi untuk mengaktifkan input manual
            function enableManualInput() {
                $('#nama_lengkap, #email').prop('readonly', false);
                $('#nama_lengkap, #email').val('');
            }

            // Fungsi untuk menonaktifkan input manual
            function disableManualInput() {
                $('#nama_lengkap, #email').prop('readonly', true);
            }
        });
    </script>
</body>

</html>
