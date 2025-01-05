@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Welcome {{ Auth::user()->name }}</h3>
                    <h6 class="font-weight-normal mb-0">Your gateway to information, connection, and progress</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-tale">
                <div class="card-body">
                    <p class="mb-4">Total Budget</p>
                    <p class="fs-30 mb-2">Rp. {{ App\Helpers\MyHelper::rupiah($totalBudget) }}</p>
                    <a href="{{ route('budget.index') }}" class="text-white btn-link">See Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card  card-dark-blue">
                <div class="card-body">
                    <p class="mb-4">Total Province</p>
                    <p class="fs-30 mb-2">{{ $totalProvince }}</p>
                    <a href="{{ route('province.index') }}" class="text-white btn-link">See Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-light-blue">
                <div class="card-body">
                    <p class="mb-4">Total Entertain User</p>
                    <p class="fs-30 mb-2">Rp. {{ App\Helpers\MyHelper::rupiah($totalRembestment) }}</p>
                    <a href="{{ route('entertain.index') }}" class="text-white btn-link">See Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-light-danger">
                <div class="card-body">
                    <p class="mb-4">Total User Guest</p>
                    <p class="fs-30 mb-2">{{ $totalUserGuest }}</p>
                    <a href="{{ route('user.index') }}" class="text-white btn-link">See Details</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">User Submit Form</p>
                    <p class="font-weight-500">Track and analyze the number of forms submitted by users each month.</p>
                    <div class="form-group">
                        <label for="year">Filter Tahun:</label>
                        <select id="year" class="form-control">
                            @foreach (range(date('Y'), date('Y') - 10) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <canvas id="userInputFormChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">User Entertain By Month</p>
                    <p class="font-weight-500">Track monthly reimbursements and entertainment expenses with clear insights.
                    </p>
                    <div class="form-group">
                        <label for="yearFilter">Pilih Tahun</label>
                        <select id="yearFilter" class="form-control">
                            @for ($year = now()->year; $year >= now()->year - 5; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <canvas id="userEntertainChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Budget Provinces</p>
                    <p class="font-weight-500">Analyze and compare the allocated budget for each province to ensure effective resource management.
                    </p>
                    <div class="form-group">
                        <label for="year-budget">Filter Tahun:</label>
                        <select id="year-budget" class="form-control">
                            @foreach (range(date('Y'), date('Y') - 10) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <canvas id="budgetChart" ></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('addon-script')
    <script>
        const ctx = document.getElementById('userEntertainChart').getContext('2d');
        let chartEntertainData;
        const ctx2 = document.getElementById('userInputFormChart').getContext('2d');
        let chartEntertainInputData;
        const ctx3 = document.getElementById('budgetChart').getContext('2d');
        let chartBudget;

        // Fungsi untuk memuat data dari server
        async function loadChartData(year) {
            try {
                const response = await fetch(`/get_entertain_data?year=${year}`);
                const {
                    data,
                    months
                } = await response.json();

                const datasets = Object.keys(data).map((typeName, index) => {
                    const totals = Array.from({
                        length: 12
                    }, (_, i) => data[typeName][i + 1] || 0);
                    const colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];

                    return {
                        label: typeName, // Menggunakan nama type
                        data: totals,
                        backgroundColor: colors[index % colors.length],
                        borderColor: colors[index % colors.length],
                        borderWidth: 1,
                    };
                });
                if (chartEntertainData) chartEntertainData.destroy();

                chartEntertainData = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: months.map(m => new Date(0, m - 1).toLocaleString('default', {
                            month: 'short'
                        })),
                        datasets: datasets,
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: `Chart Nilai Entertain Tahun ${year}`,
                            },
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Bulan',
                                },
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Total Nilai Entertain',
                                },
                            },
                        },
                    },
                });
            } catch (error) {
                console.error('Error fetching chart data:', error);
            }
        }

        async function loadUserChart(year) {
            try {
                const response = await fetch(`/get_entertain_user_input_data?year=${year}`);
                const {
                    data,
                    months
                } = await response.json();

                const datasets = Object.keys(data).map((provinceName, index) => {
                    const totals = Array.from({
                        length: 12
                    }, (_, i) => data[provinceName][i + 1] || 0);
                    const colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];

                    return {
                        label: provinceName, // Nama provinsi sebagai label
                        data: totals,
                        backgroundColor: 'rgba(0, 0, 0, 0)', // Transparan untuk background
                        borderColor: colors[index % colors.length],
                        borderWidth: 2,
                        fill: false, // Tidak mengisi area bawah garis
                        tension: 0.1, // Garis lebih smooth
                    };
                });

                if (chartEntertainInputData) chartEntertainInputData.destroy();

                chartEntertainInputData = new Chart(ctx2, {
                    type: 'line', // Menggunakan Line Chart
                    data: {
                        labels: months.map(m => new Date(0, m - 1).toLocaleString('default', {
                            month: 'short'
                        })),
                        datasets: datasets,
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    // Menampilkan jumlah user pada tooltip
                                    label: function(tooltipItem) {
                                        return `Jumlah User: ${tooltipItem.raw}`;
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: `Jumlah User Isi Form Tahun ${year}`,
                            },
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Bulan',
                                },
                            },
                            y: {
                                beginAtZero: false,
                                title: {
                                    display: true,
                                    text: 'Jumlah User',
                                },
                            },
                        },
                    },
                });
            } catch (error) {
                console.error('Error fetching user chart data:', error);
            }
        }

        async function loadBudgetChart(year) {
            try {
                const response = await fetch(`/get_budget_data?year=${year}`);
                const {
                    provinces,
                    masukData,
                    keluarData
                } = await response.json();

                const dataset = [{
                        label: 'Masuk', // Label untuk status Masuk
                        data: masukData,
                        backgroundColor: 'rgba(0, 123, 255, 0.5)', // Warna background untuk Masuk
                        borderColor: 'rgba(0, 123, 255, 1)', // Warna border untuk Masuk
                        borderWidth: 1,
                    },
                    {
                        label: 'Keluar', // Label untuk status Keluar
                        data: keluarData,
                        backgroundColor: 'rgba(220, 53, 69, 0.5)', // Warna background untuk Keluar
                        borderColor: 'rgba(220, 53, 69, 1)', // Warna border untuk Keluar
                        borderWidth: 1,
                    },
                ];

                if (chartBudget) chartBudget.destroy();

                chartBudget = new Chart(ctx3, {
                    type: 'bar', // Menggunakan Bar Chart
                    data: {
                        labels: provinces, // Provinsi di sumbu X
                        datasets: dataset, // Masuk dan Keluar data pada sumbu Y
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return `Total: ${tooltipItem.raw}`;
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: `Total Budget Provinsi - Tahun ${year}`,
                            },
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Provinsi',
                                },
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Total Budget',
                                },
                            },
                        },
                    },
                });
            } catch (error) {
                console.error('Error fetching budget chart data:', error);
            }
        }


        // Event listener untuk filter tahun
        document.getElementById('yearFilter').addEventListener('change', (e) => {
            loadChartData(e.target.value);
        });
        document.getElementById('year').addEventListener('change', function() {
            loadUserChart(this.value);
        });
        document.getElementById('year-budget').addEventListener('change', function() {
            loadBudgetChart(this.value);
        });
        // Muat data awal
        document.addEventListener('DOMContentLoaded', () => {
            loadChartData(new Date().getFullYear());
            loadUserChart(new Date().getFullYear());
            loadBudgetChart(new Date().getFullYear(), 'Masuk');
        });
    </script>
@endpush
