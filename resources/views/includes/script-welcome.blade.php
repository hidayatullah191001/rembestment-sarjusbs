<script>
    $('#provinsi').select2();
    $(document).ready(function() {

        let currentStep = 1;
        const totalSteps = 3;

        // Initialize Flatpickr
        flatpickr('input[type="date"]');
        flatpickr('input[type="time"]', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });

        // Handle Next button
        $('#nextBtn').click(function() {
            if (validateCurrentSection()) {
                saveCurrentStep();
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateFormDisplay();
                }
            }
        });

        // Handle Previous button
        $('#prevBtn').click(function() {
            if (currentStep > 1) {
                currentStep--;
                updateFormDisplay();
            }
        });

        // Handle form submission
        $('#entertainForm').submit(function(e) {
            e.preventDefault();
            if (validateCurrentSection()) {
                saveCurrentStep();
                submitForm();
            }
        });

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

        // Validasi sebelum submit
        $('#entertainForm').on('submit', function(e) {
            $('.currency-input').each(function() {
                let numericValue = getNominal($(this).val());
                let hiddenInput = $(this).attr('id') + '_value';
                $('#' + hiddenInput).val(numericValue);
            });

        });

        // Add Peserta button handler
        let pesertaCount = 1;
        $('#addPeserta').click(function() {
            const newPeserta = `
                <div class="peserta-item" id="peserta-${pesertaCount}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0">Peserta #${pesertaCount + 1}</h6>
                        <button type="button" class="btn btn-danger btn-sm delete-peserta" data-id="${pesertaCount}">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control form-control-sm" name="peserta[${pesertaCount}][nama_pelanggan]">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Internal Icon</label>
                        <input type="text" class="form-control form-control-sm" name="peserta[${pesertaCount}][internal_icon]">
                    </div>
                </div>
            `;
            $('#pesertaContainer').append(newPeserta);
            pesertaCount++;
            updatePesertaNumbers();
        });

        $(document).on('click', '.delete-peserta', function() {
            const pesertaId = $(this).data('id');
            const totalPeserta = $('.peserta-item').length;

            // Cek apakah ini peserta terakhir
            if (totalPeserta > 1) {
                $(`#peserta-${pesertaId}`).remove();
                updatePesertaNumbers();
            } else {
                alert('Minimal harus ada satu peserta!');
            }
        });

        // $(document).on('click', '.delete-peserta', function() {
        //     const pesertaId = $(this).data('id');
        //     $(`#peserta-${pesertaId}`).remove();
        //     updatePesertaNumbers();
        // });

        // Fungsi untuk update nomor urut peserta
        function updatePesertaNumbers() {
            $('.peserta-item').each(function(index) {
                $(this).find('h6').text(`Peserta #${index + 1}`);
            });
        }

        function updateFormDisplay() {
            // Update sections visibility
            $('.form-section').removeClass('active');
            $(`#section-${currentStep}`).addClass('active');

            // Update step indicators
            $('.step').removeClass('active');
            $(`#step-${currentStep}`).addClass('active');

            // Update buttons
            $('#prevBtn').toggle(currentStep > 1);
            $('#nextBtn').toggle(currentStep < totalSteps);
            $('#submitBtn').toggle(currentStep === totalSteps);
        }

        function validateCurrentSection() {
            const currentSection = $(`#section-${currentStep}`);
            let valid = true;

            currentSection.find('input[required], select[required], textarea[required]').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    valid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            return valid;
        }

        async function saveCurrentStep() {
            if (currentStep === 2) {
                // Update hidden inputs sebelum save
                $('.currency-input').each(function() {
                    let numericValue = getNominal($(this).val());
                    let hiddenInput = $(this).attr('id') + '_value';
                    $('#' + hiddenInput).val(numericValue);
                });
            }

            const formData = new FormData($('#entertainForm')[0]);
            try {
                await $.ajax({
                    url: '/save-step-' + currentStep,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false
                });
            } catch (error) {
                console.error('Error saving step:', error);
            }
        }

        async function submitForm() {
            await saveCurrentStep();
            try {
                const response = await $.ajax({
                    url: '/store-entertain',
                    method: 'POST',
                    data: new FormData($('#entertainForm')[0]),
                    processData: false,
                    contentType: false
                });

                if (response.success) {
                    // SweetAlert pertama untuk memulai proses unduh
                    Swal.fire({
                        title: "Processing...",
                        text: "Mengunduh PDF. Harap tunggu.",
                        icon: "info",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        background: "#fff",
                        color: "#575656",
                        customClass: {
                            popup: 'shadow-lg rounded-lg'
                        }
                    });

                    // Konversi base64 ke blob dan mulai unduhan
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

                    // Cleanup
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(link);

                    // Tunggu beberapa detik untuk memastikan unduhan selesai
                    setTimeout(() => {
                        Swal.fire({
                            title: "Success!",
                            text: "Data berhasil disimpan dan PDF berhasil diunduh!",
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
                            // Redirect setelah SweetAlert sukses
                            window.location.href = response.redirect;
                        });
                    }, 3000);
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
                    text: "Gagal menyimpan data : " + (error.responseJSON?.message || error
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
            }
        }


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

        // Fungsi untuk mendapatkan nilai numerik dari format rupiah
        function getNominal(rupiah) {
            if (typeof rupiah !== "string") return rupiah;
            return parseInt(rupiah.replace(/,.*|[^0-9]/g, ''));
        }
    });
</script>
