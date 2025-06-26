<!-- start page title -->
@extends('layouts.master')
@section('title')
@lang('translation.Datatables')
@endsection
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('path/to/apexcharts/dist/apexcharts.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="{{ asset('js/dashboard-charts.js') }}"></script>
<link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/assets/libs/chart-js/Chart.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    .chart-container {
        /* position: relative; */
        width: 100%;
        /* height: 300px; */
    }
</style>

@section('content')

<!-- <div id="loading-screen" style="
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: white;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
">
    <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;"></div>
    <p class="mt-3">Memuat dashboard...</p>
</div> -->

<div class="row">
    
    <div class="col-12">
        <div class="page-title-box">
            <h2 class="mb-0">Dashboard</h2>
            
            <h4 class="mb-0">
                <?php
                date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu sesuai dengan lokasi Anda
                $currentTime = date('H:i'); // Ambil jam saat ini

                // Tentukan ucapan berdasarkan jam
                if ($currentTime >= '05:00' && $currentTime < '10:00') {
                    $greeting = '🌞 Selamat Pagi'; // Emoji tangan menyapa di sini
                } elseif ($currentTime >= '10:01' && $currentTime < '14:59') {
                    $greeting = '☀️ Selamat Siang'; // Emoji tangan menyapa di sini
                } elseif ($currentTime >= '15:00' && $currentTime < '17:30') {
                    $greeting = '🌅 Selamat Sore'; // Emoji tangan menyapa di sini
                } else {
                    $greeting = '🌙 Selamat Malam'; // Emoji tangan menyapa di sini
                }

                // Akses nama pengguna melalui auth()
                $userName = auth()->user()->fname ?? "Guest"; // Jika tidak ada pengguna terautentikasi, gunakan "Guest"

                echo $greeting . ', ' . $userName;
                ?>
            </h4>
            <p class="mb-0">
                <?php echo date('l, j F Y'); ?>
            </p>
        </div>
    </div>
</div>
<div class="row">
    <!-- <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="float-end mt-2">
                    <i class="fas fa-address-card text-blue" style="font-size: 40px;"></i>
                </div>
                <div>
                    <h4 class="mb-1 mt-1"><span>{{$totalSupplier}}</span></h4>
                    <p class="text-muted mb-0">Jumlah Vendor</p>
                </div>
            </div>
        </div>
    </div> -->
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="float-end mt-2">
                    <i class="fas fa-address-card text-blue" style="font-size: 40px;"></i>
                </div>
                <div>
                    <h4 class="mb-1 mt-1">
                        <span>
                            Rp. {{ number_format($totalCashbonTahunIni, 0, ',', '.') }}
                        </span>
                    </h4>
                    <p class="text-muted mb-0">Jumlah cashbon anda di tahun {{$tahun}}</p>
                </div>
            </div>
        </div>
    </div>   
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="float-end mt-2">
                    <i class="fas fa-address-card text-blue" style="font-size: 40px;"></i>
                </div>
                <div>
                    <h4 class="mb-1 mt-1">
                        <span>
                            Rp. {{ number_format($totalBelumLPJThn, 0, ',', '.') }}
                        </span>
                    </h4>
                    <p class="text-muted mb-0">Jumlah yang Belum LPJ di tahun {{$tahun}} </p>
                </div>
            </div>
        </div>
    </div> 
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="float-end mt-2">
                    <i class="fas fa-address-card text-blue" style="font-size: 40px;"></i>
                </div>
                <div>
                    <h4 class="mb-1 mt-1">
                        <span>
                            Rp. {{ number_format($totalSudahLPJ, 0, ',', '.') }}
                        </span>
                    </h4>
                    <p class="text-muted mb-0">Jumlah yang sudah LPJ di tahun {{$tahun}} </p>
                </div>
            </div>
        </div>
    </div>         
</div>
<div class="row">        
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <br>
    <div class="col-xl-3">
        <div class="card bg-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-sm-8">
                        <p class="text-white font-size-18">Ingin Menuju ke Data Cashbon Langsung ?</p>
                        <div class="mt-4">
                            <a href={{ route('cashbon.list') }} class="btn btn-success waves-effect waves-light">Klik Disini !</a>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mt-4 mt-sm-0">
                            <img src="assets/images/setup-analytics-amico.svg" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
        
</div>
<div class="row">
    <div class="col-12 col-md-12 col-xl-6">
        <form method="GET" action="{{ route('dashboard') }}" class="mb-3 d-flex align-items-center gap-2">
            <label class="mb-0">Tahun:</label>
            <select name="tahun" onchange="this.form.submit()" class="form-select w-auto">
                @for ($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Grafik Cashbon & LPJ Tahun {{ $tahun }}</h5>
                <div class="chart-container w-100 mx-auto" style="max-width: 1000px;">
                    <canvas id="chartCashbonLPJ"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
@section('script')
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/apexcharts.init.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="{{ asset('js/dashboard-charts.js') }}"></script>
<script>
    window.onload = function () {
        const loadingModal = document.getElementById('loadingModal');
        if (loadingModal) {
            const modalInstance = bootstrap.Modal.getInstance(loadingModal) || new bootstrap.Modal(loadingModal);
            modalInstance.hide();
        }
    };
</script>
<script>
    const ctx = document.getElementById('chartCashbonLPJ').getContext('2d');

    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [
                {
                    label: 'Total Cashbon',
                    data: @json($grafikCashbon),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Total LPJ',
                    data: @json($grafikLPJ),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            aspectRatio: window.innerWidth < 768 ? 1.2 : 2,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Perbandingan Cashbon & LPJ per Bulan'
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    window.addEventListener('resize', () => {
        let newAspectRatio = window.innerWidth < 768 ? 1.2 : 2;
        chart.options.aspectRatio = newAspectRatio;
        chart.update();
    });
</script>
@endsection