<script>
    $('#provinsi').select2();
    $(document).ready(function() {
        let currentStep = 1;
        const totalSteps = 4;

        flatpickr('input[type="date"]');
        flatpickr('input[type="time"]', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });

        $('#nextBtn').click(function() {
            if (validateCurrentSection()) {
                saveCurrentStep();
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateFormDisplay();
                }
            }
        });

        $('#prevBtn').click(function() {
            if (currentStep > 1) {
                currentStep--;
                updateFormDisplay();
            }
        });

        function setLoadingState(isLoading) {
            const submitBtn = $('#submitBtn');
            if (isLoading) {
                // Disable button dan tambahkan loading state
                submitBtn.prop('disabled', true)
                    .html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                    );
            } else {
                // Kembalikan button ke kondisi normal
                submitBtn.prop('disabled', false)
                    .html('Submit');
            }
        }

        $('#entertainForm').submit(function(e) {
            e.preventDefault();
            setLoadingState(true);
            if (validateCurrentSection()) {
                saveCurrentStep();
                submitForm();
            } else {
                setLoadingState(false);
            }
        });

        $('.currency-input').on('input', function() {
            let value = $(this).val();
            value = value.replace(/[^\d]/g, '');
            $(this).val(formatRupiah(value));
            let numericValue = getNominal(value);
            let hiddenInput = $(this).attr('id') + '_value';
            $('#' + hiddenInput).val(numericValue);
        });

        $('#entertainForm').on('submit', function(e) {
            $('.currency-input').each(function() {
                let numericValue = getNominal($(this).val());
                let hiddenInput = $(this).attr('id') + '_value';
                $('#' + hiddenInput).val(numericValue);
            });

        });

        function generatePesertaRows() {
            const maxRows = 10;
            const tbody = document.getElementById('pesertaTableBody');
            tbody.innerHTML = '';

            for (let i = 0; i < maxRows; i++) {
                const row = document.createElement('tr');
                row.id = `peserta-${i}`;

                row.innerHTML = `
                    <td>${i + 1}</td>
                    <td><input type="text" class="form-control form-control-sm" name="peserta[${i}][nama_pelanggan]"></td>
                    <td><input type="text" class="form-control form-control-sm" name="peserta[${i}][internal_icon]"></td>
                `;

                tbody.appendChild(row);
            }
        }
        generatePesertaRows();

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

                    setLoadingState(false);
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
                    setLoadingState(false);
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
                setLoadingState(false);
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
