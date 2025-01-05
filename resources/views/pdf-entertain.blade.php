{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Entertain Penjualan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-family: Arial, sans-serif; }
        .form-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .form-table th, .form-table td { border: 1px solid #000; padding: 5px; text-align: left; }
        .header-title { text-align: center; font-size: 18px; font-weight: bold; }
        .signature { margin-top: 50px; text-align: center; }
        .signature div { margin-bottom: 30px; }
    </style>
    @include('includes.style')
</head>
<body>
    <div class="container">
        <div class="bg-secondary py-3 px-3 d-flex justify-content-center align-items-center">
            <h2 class="header-title mb-0">FORM ENTERTAIN PENJUALAN</h2>
        </div>
        <table class="form-table ">
            <tr>
                <td style="width: 40px">HARI</td>   
                <td style="width: 25px">:</td>
                <td>{{ $hari }}</td>
            </tr>
            <tr>
                <td style="width: 40px">TANGGAL</td>   
                <td style="width: 15px">:</td>
                <td>{{ $tanggal }}</td>
            </tr>
            <tr>
                <td style="width: 40px">WAKTU</td>   
                <td style="width: 15px">:</td>
                <td>{{ $waktu }}</td>
            </tr>
            <tr>
                <td style="width: 40px">TIPE</td>   
                <td style="width: 15px">:</td>
                <td>{{ $tipe }}</td>
            </tr>
        </table>
        <div class="bg-secondary d-block mt-3 mb-3" style="height: 10px"></div>
        <table class="form-table ">
            <tr>
                <td style="width: 25px">NILAI ENTERTAIN</td>   
                <td style="width: 25px">:</td>
                <td>Rp. {{ $nilai_entertain }}</td>
            </tr>
            <tr>
                <td style="width: 40px">REVENUE</td>   
                <td style="width: 15px">:</td>
                <td>Rp. {{ $revenue }}</td>
            </tr>
            <tr>
                <td style="width: 40px">PELANGGAN</td>   
                <td style="width: 15px">:</td>
                <td>{{ $pelanggan }}</td>
            </tr>
        </table>
        <div class="row mt-3">
            <div class="col-md-2">
                PESERTA
            </div>
            <div class="col-md-10">
                <table class="table m-0">
                    <thead>
                        <tr><th class="p-0">NO</th><th class="p-0">PELANGGAN</th><th class="p-0">INTERNAL ICON+</th></tr>
                    </thead>
                    <tbody>
                        @foreach($peserta as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item['nama_pelanggan'] }}</td>
                            <td>{{ $item['internal_icon'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <h5 class="mt-3">RENCANA TINDAK LANJUT</h5>
        <table class="form-table">
            <thead>
                <tr><th>TOPIK</th><th>AKTIVITAS</th><th>TARGET PELAKSANAAN</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $topik }}</td>
                    <td>{{ $aktivitas }}</td>
                    <td>{{ $target_pelaksanaan }}</td>
                </tr>
            </tbody>
        </table>

        <div class="signature">
            <div>Yang Mengusulkan,</div>
            <div>Manager Kantor Perwakilan</div>
        </div>
    </div>
</body>
</html> --}}


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            background-color: #f0f0f0;
            padding: 10px;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 10px;
        }
        
        .form-label {
            display: inline-block;
            width: 150px;
        }
        
        .form-value {
            display: inline-block;
            min-width: 200px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        
        th {
            background-color: #d8d8d8;
            color: black;
        }
        
        .footer {
            margin-top: 30px;
            width: 100%;
            position: relative;
        }
        
        .top-signatures {
            width: 100%;
            margin-bottom: 60px;
        }
        
        .signature-block {
            float: left;
            text-align: center;
            width: 45%;
            margin-right: 5%;
        }
        
        .signature-block:last-child {
            margin-right: 0;
            float: right;
        }
        
        .bottom-signature {
            clear: both;
            width: 40%;
            margin: 0 auto;
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-bottom: 5px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .space-mt {
            margin-top: 80px;
        }
        
        .clear {
            clear: both;
            height: 1px;
            width: 100%;
        }
        
        .bold {
            font-weight: bold;
        }
        
        .name-size {
            font-size: 14px;
        }

        .signature-container {
            page-break-inside: avoid;
            margin-top: 20px;
            position: relative;
        }
    </style>
</head>
<body>
    <div class="header">
        FORM ENTERTAIN PENJUALAN
    </div>
    
    <div class="form-group">
        <span class="form-label">HARI</span>: 
        <span class="form-value">{{ $hari }}</span>
    </div>
    
    <div class="form-group">
        <span class="form-label">TANGGAL</span>: 
        <span class="form-value">{{ $tanggal }}</span>
    </div>
    
    <div class="form-group">
        <span class="form-label">WAKTU</span>: 
        <span class="form-value">{{ $waktu }}</span>
    </div>
    
    <div class="form-group">
        <span class="form-label">TIPE</span>: 
        <span class="form-value">{{ $tipe }}</span>
    </div>
    
    <div class="form-group">
        <span class="form-label">NILAI ENTERTAIN</span>: Rp. 
        <span class="form-value">{{ App\Helpers\MyHelper::rupiah($nilai_entertain) }}</span>
    </div>
    
    <div class="form-group">
        <span class="form-label">REVENUE</span>: Rp. 
        <span class="form-value">{{ App\Helpers\MyHelper::rupiah($revenue) }}</span>
    </div>
    <div class="form-group">
        <span class="form-label">PELANGGAN</span>: 
        <span class="form-value">{{ $pelanggan }}</span>
    </div>
    
    @if (count($peserta) > 0)
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>PELANGGAN</th>
                    <th>INTERNAL ICON+</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peserta as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['nama_pelanggan'] }}</td>
                    <td>{{ $item['internal_icon'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <table>
        <thead>
            <tr>
                <th>TOPIK</th>
                <th>AKTIVITAS</th>
                <th>TARGET PELAKSANAAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $topik }}</td>
                <td>{{ $aktivitas }}</td>
                <td>{{ $target_pelaksanaan }}</td>
            </tr>
        </tbody>
    </table>
    
    <div class="signature-container">
        <div class="top-signatures">
            <div class="signature-block">
                <div>Yang mengajukan,</div>
                <div class="space-mt"></div>
                <div class="name-size">{{$nama_account_manager}}</div>
                <div class="signature-line"></div>
                <div class="bold">ACCOUNT MANAGER</div>
            </div>
            
            <div class="signature-block">
                <div>Mengetahui,</div>
                <div class="space-mt"></div>
                <div class="name-size">{{$nama_kanwil_manager}}</div>
                <div class="signature-line"></div>
                <div class="bold">MANAGER KANTOR PERWAKILAN</div>
            </div>
            
            <div class="clear"></div>
        </div>
        
        <div class="bottom-signature">
            <div>Menyetujui,</div>
            <div class="space-mt"></div>
            <div class="name-size">HIZMA JONATHAPOLI</div>
            <div class="signature-line"></div>
            <div class="bold">MANAGER PEMASARAN DAN PENJUALAN SBU SUMBASEL</div>
        </div>
    </div>
</body>
</html>