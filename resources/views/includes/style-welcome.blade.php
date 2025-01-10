<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .form-section {
        display: none;
    }

    .form-section.active {
        display: block;
    }

    .step-indicator {
        margin-bottom: 30px;
    }

    .step {
        display: inline-block;
        padding: 10px 20px;
        margin: 0 5px;
        background: #f8f9fa;
        border-radius: 5px;
        color: #6c757d;
    }

    .step.active {
        background: #0d6efd;
        color: white;
    }

    .peserta-item {
        border: 1px solid #dee2e6;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
        position: relative;
    }

    .delete-peserta {
        transition: all 0.3s ease;
    }

    .delete-peserta:hover {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .bi-trash {
        font-size: 14px;
    }

    select.form-select {
        color: #000000 !important;
    }

    .select2-container .select2-selection--single {
        height: 40px;
        /* Sesuaikan dengan tinggi yang Anda inginkan */
        display: flex;
        align-items: center;
        padding: 0 10px;
    }

    .select2-container .select2-selection__rendered {
        line-height: 1;
        padding: 0;
        text-align: center;
    }

    .swal2-title {
        color: #575656;
    }

    .spinner-border {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 0.2em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border .75s linear infinite;
    }

    @keyframes spinner-border {
        100% {
            transform: rotate(360deg);
        }
    }
</style>
