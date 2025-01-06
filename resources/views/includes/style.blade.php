<link rel="stylesheet" href="{{ asset('assets') }}/vendors/feather/feather.css" />
<link rel="stylesheet" href="{{ asset('assets') }}/vendors/ti-icons/css/themify-icons.css" />
<link rel="stylesheet" href="{{ asset('assets') }}/vendors/css/vendor.bundle.base.css" />
<link rel="stylesheet" href="{{ asset('assets') }}/vendors/font-awesome/css/font-awesome.min.css" />
<link rel="stylesheet" href="{{ asset('assets') }}/vendors/mdi/css/materialdesignicons.min.css" />
<link rel="stylesheet" href="{{ asset('assets') }}/css/style.css" />
<link rel="shortcut icon" href="{{ asset('storage/' . App\Helpers\MyHelper::getSetting('app_icon'))  }}" />
{{-- <link rel="stylesheet" href="{{ asset('assets') }}/vendors/datatables.net-bs5/dataTables.bootstrap5.css"> --}}
<link rel="stylesheet" href="{{ asset('assets') }}/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/js/select.dataTables.min.css">

<link rel="stylesheet" href="{{ asset('assets') }}/vendors/select2/select2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">

<link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">

<style>
    #dt-length-0{
        width : 70px !important;
        color: #4d4d4d;
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
</style>