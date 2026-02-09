<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring - Saprotan Utama</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --bg-body: #f8fafc;
            --bg-sidebar: #ffffff;
            --bg-card: #ffffff;
            --sidebar-width: 280px;
            --sidebar-gradient: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            --accent-soft: rgba(13, 110, 253, 0.05);
        }

        [data-bs-theme="dark"] {
            --bg-body: #121212;
            --bg-sidebar: #1e1e1e;
            --bg-card: #242424;
            --sidebar-gradient: linear-gradient(180deg, #242424 0%, #1e1e1e 100%);
            --accent-soft: rgba(255, 255, 255, 0.05);
        }

        body { 
            background-color: var(--bg-body); 
            color: var(--text-main); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            transition: all 0.3s ease; 
        }

        /* Sidebar Styling */
        .sidebar { 
            background: var(--sidebar-gradient) !important; 
            border-right: 1px solid rgba(0,0,0,0.05) !important; 
            height: 100vh; padding: 25px; position: fixed; width: var(--sidebar-width); 
            z-index: 1050; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar.collapsed { transform: translateX(-100%); }

        /* Filter Labels & Inputs */
        .filter-label {
            font-size: 11px; font-weight: 700; color: #64748b;
            text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; display: block;
        }

        .form-control-sm, .form-select-sm {
            border-radius: 10px; border: 1px solid rgba(0,0,0,0.1); padding: 8px 12px;
        }

        /* Main Content */
        .main-content { 
            margin-left: var(--sidebar-width); padding: 40px; 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .main-content.expanded { margin-left: 0; }

        /* Card & Scorecards */
        .card { 
            border: none; border-radius: 20px; 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); 
            background: var(--bg-card); transition: transform 0.2s;
        }
        
        .scorecard { overflow: hidden; position: relative; }
        .scorecard-icon {
            position: absolute; right: -10px; bottom: -10px;
            font-size: 5rem; opacity: 0.1; transform: rotate(-15deg);
        }

        .btn-theme-toggle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--bg-card); /* Mengikuti variabel tema yang sudah ada */
            border: 1px solid rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* Efek Hover */
        .btn-theme-toggle:hover {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
        }

        [data-bs-theme="dark"] .btn-theme-toggle {
            background: #2d2d2d;
            border-color: rgba(255,255,255,0.1);
            color: #fff;
        }

        /* Table Improvements */
        .table-container { border-radius: 20px; overflow: hidden; background: var(--bg-card); }
        .table thead th { 
            background: var(--accent-soft); border: none; 
            padding: 15px 20px; font-size: 12px; 
        }
        .table tbody td { padding: 15px 20px; border-bottom: 1px solid rgba(0,0,0,0.02); }
        
        /* Custom Toggle Button */
        #sidebarToggle {
            background: var(--bg-card); color: var(--primary-color);
            border: 1px solid rgba(0,0,0,0.05); width: 50px; height: 50px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }


        /* Container untuk membatasi tinggi tabel */
        .table-scroll-container {
            max-height: 300px; /* Sesuaikan angka ini agar pas dengan 5 baris + header */
            overflow-y: auto;
            position: relative;
        }

        /* Membuat Header tetap terlihat saat di-scroll (Sticky Header) */
        .table-scroll-container thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: var(--accent-soft) !important; /* Warna background header */
            box-shadow: inset 0 -1px 0 rgba(0,0,0,0.1); /* Garis bawah halus */
        }

        /* Mempercantik tampilan scrollbar (Opsional) */
        .table-scroll-container::-webkit-scrollbar {
            width: 6px;
        }
        .table-scroll-container::-webkit-scrollbar-thumb {
            background-color: rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        

        [data-bs-theme="dark"] .table-scroll-container thead th {
            background: #2d2d2d !important; /* Warna abu gelap yang solid */
            color: #e8eaed !important; /* Warna teks putih keabuan */
            box-shadow: inset 0 -1px 0 rgba(255,255,255,0.1);
        }


        /* Indikator Sortir */
        .sortable {
            cursor: pointer;
            position: relative;
            transition: background 0.2s;
        }

        .sortable:hover {
            background: rgba(13, 110, 253, 0.1) !important;
        }

        .sortable::after {
            content: ' \2195'; /* Simbol panah atas bawah netral */
            opacity: 0.3;
            font-size: 0.8rem;
            margin-left: 5px;
        }

        .sortable.sort-asc::after {
            content: ' \2191'; /* Panah atas */
            opacity: 1;
            color: var(--primary-color);
        }

        .sortable.sort-desc::after {
            content: ' \2193'; /* Panah bawah */
            opacity: 1;
            color: var(--primary-color);
        }

        /* Marquee Alert Premium */
        .marquee-container {
            background: #fb5a5a; 
            border-left: 4px solid #ef4444; 
            border-radius: 12px;
            overflow: hidden; /* Penting agar teks tidak keluar jalur */
            white-space: nowrap;
            position: relative;
            height: 40px;
            display: flex;
            align-items: center;
        }

        .marquee-text {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 20s linear infinite;
        }

        @keyframes marquee {
            0%   { transform: translate(0, 0); }
            100% { transform: translate(-100%, 0); }
        }

        .marquee-container:hover .marquee-text {
            animation-play-state: paused;
        }

        /* Tambahan untuk Sidebar agar smooth */
        .sidebar {
            transition: all 0.3s ease-in-out;
        }
        .main-content {
            transition: all 0.3s ease-in-out;
        }

        /* Styling khusus untuk layar HP (Mobile) */
        @media (max-width: 768px) {
            .sidebar {
                width: 100% !important;
                height: auto !important;
                max-height: 80vh; /* Agar tidak menutupi seluruh layar secara kaku */
                top: 0;
                left: 0;
                transform: translateY(-100%); /* Sembunyi ke atas */
                border-right: none !important;
                border-bottom: 1px solid rgba(0,0,0,0.1);
                padding-top: 70px; /* Jarak agar tidak tertutup tombol toggle */
                overflow-y: auto;
            }

            .sidebar.collapsed {
                transform: translateY(-100%); /* Tetap di atas saat tertutup */
            }

            /* Saat aktif (muncul), tarik ke posisi 0 */
            .sidebar:not(.collapsed) {
                transform: translateY(0);
            }

            .main-content {
                margin-left: 0 !important;
                padding-top: 80px; /* Beri ruang untuk tombol di atas */
            }
        }

        /* Styling transisi agar smooth dari atas */
        .sidebar {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
    </style>

    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            }
        })();
    </script>
</head>
<body>
    <div class="position-fixed top-0 start-0 p-2 d-flex gap-2" style="z-index: 1100;">
        <button class="btn btn-white rounded-circle shadow-sm border" id="sidebarToggle">
            <span id="toggleIcon">☰</span>
        </button>
    </div>

    <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 2000;">
        <button class="btn btn-theme-toggle shadow-lg" id="themeSwitcher">
            <span id="themeIcon">🌙</span>
        </button>
    </div>

        <div class="sidebar shadow-sm">
            <div class="d-flex align-items-center gap-2 mb-4 mt-0 pt-3 ms-4">
                <img src="{{ asset('images/logo-su-w2.png') }}" style="max-height: 40px;">
                <h6 class="fw-800 m-0 text-primary">DASHBOARD</h6>
            </div>
            
            <div class="mb-4">
                <a href="/" class="btn btn-white w-100 rounded-3 text-start small fw-bold py-2 border">
                    🏠 Ke Portal Utama
                </a>
            </div>

            <form action="{{ route('barang.dashboard') }}" method="GET" class="px-1">
                <label class="filter-label text-primary">🗓️ Periode Waktu</label>
                <div class="p-3 rounded-4 mb-3 ">
                    <input type="text" name="start_date" id="start_date" class="form-control form-control-sm mb-2 border-1 shadow-none" placeholder="Tanggal Mulai" value="{{ request('start_date') }}">
                    <input type="text" name="end_date" id="end_date" class="form-control form-control-sm border-1 shadow-none" placeholder="Tanggal Selesai" value="{{ request('end_date') }}">
                </div>

                <label class="filter-label">📍 Filter Lokasi</label>
                <select name="locator" class="form-select form-select-sm mb-4 rounded-3" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <option value="REGULER" {{ request('locator') == 'REGULER' ? 'selected' : '' }}>REGULER</option>
                    <option value="HOLD" {{ request('locator') == 'HOLD' ? 'selected' : '' }}>HOLD</option>
                    <option value="NOK" {{ request('locator') == 'NOK' ? 'selected' : '' }}>NOK</option>
                </select>

                <label class="filter-label">📦 Satuan (UOM)</label>
                <select name="uom" class="form-select form-select-sm mb-4 rounded-3" onchange="this.form.submit()">
                    <option value="">Semua Satuan</option>
                    @foreach($allUoms as $u)
                        <option value="{{ $u }}" {{ request('uom') == $u ? 'selected' : '' }}>{{ $u }}</option>
                    @endforeach
                </select>

                <label class="filter-label">📊 STOK STATUS</label>
                <select name="status" class="form-select form-select-sm mb-4 rounded-3" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Safe Stock" {{ request('status') == 'Safe Stock' ? 'selected' : '' }}>Safe Stock (>500)</option>
                    <option value="Low Stock" {{ request('status') == 'Low Stock' ? 'selected' : '' }}>Low Stock </option>
                </select>

                
                <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold shadow-sm">Update Data</button>
                <a href="{{ route('barang.dashboard') }}" class="btn btn-light btn-sm w-100 mt-2 border">Reset</a>
            </form>
        </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h4 class="fw-bold m-0">Monitoring Stok Pupuk - Gudang Kembangarum</h4>
            
            <div class="d-flex flex-column align-items-end">
                
                <button onclick="window.location.reload();" class="btn btn-white btn-sm border shadow-sm px-3 w-100">
                    🔄 Refresh Data
                </button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card p-4 scorecard border-start border-primary border-4">
                    <div class="scorecard-title">Total SKU</div>
                    <div class="scorecard-value">{{ $totalSKU }}</div>
                    <div class="scorecard-icon text-primary">📦</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4 scorecard border-start border-success border-4">
                    <div class="scorecard-title">Total Stok</div>
                    <div class="scorecard-value">{{ number_format($totalStok) }}</div>
                    <div class="scorecard-icon text-success">📊</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3 alert-custom shadow-sm h-100 d-flex flex-column justify-content-center">
                    <div class="scorecard-title text-danger">⚠️ Perlu Perhatian (Low Stock Terdeteksi)</div>
                    
                    <div class="marquee-container mt-1">
                        @if($lowStockItem->count() > 0)
                            <div class="marquee-text fw-bold fs-5 text-uppercase">
                                @foreach($lowStockItem as $item)
                                    <span>
                                        {{ $item->nama_pupuk }} ({{ number_format($item->stok_total) }} UNIT) 
                                        <span class="mx-4 text-dark"> || </span> 
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <div class="fw-bold fs-5 px-3">✅ Semua stok dalam batas aman</div>
                        @endif
                    </div>
                    
                    @if($lowStockItem->count() > 0) 
                        <small class="text-muted">Segera cek gudang untuk {{ $lowStockItem->count() }} item di atas.</small> 
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-5">
                <div class="card p-4 h-100 shadow-sm">
                    <h6 class="fw-bold mb-4">Distribusi Stok per Lokasi</h6>
                    <div style="max-height: 300px;">
                        <canvas id="donutLokasi"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card p-4 h-100 shadow-sm">
                    <h6 class="fw-bold mb-4">Top 10 Produk Berdasarkan Status Stok</h6>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="barProduk"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-scroll-container">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped shadow-sm table-clickable" id="stockTable">
                                    <thead class="table-light text-muted">
                                        <tr>
                                            <th class="ps-4 sortable" data-type="string">Nama Produk</th>
                                            <th class="sortable" data-type="string">Lokasi</th>
                                            <th class="sortable" data-type="string">Satuan</th>
                                            <th class="text-end sortable" data-type="number">Stok Akhir</th>
                                            <th class="text-center sortable" data-type="string">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($barangs as $b)
                                        <tr onclick="toggleFilter('search', '{{ $b->nama_pupuk }}')">
                                            <td class="ps-4 fw-medium">{{ $b->nama_pupuk }}</td>
                                            <td>{{ $b->locator }}</td>
                                            <td>{{ $b->uom }}</td>
                                            <td class="text-end fw-bold">{{ number_format($b->stok_total) }}</td>
                                            <td class="text-center">
                                                @if($b->stok_total < 500)
                                                    <span class="badge bg-danger rounded-pill">Low Stock</span>
                                                @else
                                                    <span class="badge bg-primary rounded-pill">Safe Stock</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">Tidak ada data yang sesuai filter</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        // 1. Inisialisasi Kalender
        flatpickr("#start_date", { dateFormat: "Y-m-d" });
        flatpickr("#end_date", { dateFormat: "Y-m-d" });

        // Registrasi plugin secara global
        Chart.register(ChartDataLabels);

        // 2. Donut Chart (Kategori Lokasi)
        const ctxDonut = document.getElementById('donutLokasi');
        if (ctxDonut) {
            new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: ['REGULER', 'HOLD', 'NOK'],
                    datasets: [{
                        data: [{{ $reguler }}, {{ $hold }}, {{ $nok }}],
                        backgroundColor: ['#34A853', '#FBBC05', '#EA4335'],
                        borderWidth: 0
                    }]
                },
                options: { 
                    cutout: '65%',
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { 
                            position: 'right', 
                            labels: { usePointStyle: true, font: { size: 11 } } 
                        },
                        datalabels: {
                            color: '#fff',
                            font: { weight: 'bold', size: 12 },
                            formatter: (value, ctx) => {
                                let sum = 0;
                                let dataArr = ctx.chart.data.datasets[0].data;
                                dataArr.map(data => { sum += data; });
                                let percentage = (value * 100 / sum).toFixed(1) + "%";
                                return value > 0 ? percentage : '';
                            }
                        }
                    },
                    onClick: (evt, item) => {
                        if (item.length > 0) {
                            const index = item[0].index;
                            const labels = ['REGULER', 'HOLD', 'NOK'];
                            const selectedLabel = labels[index];
                            
                            const url = new URL(window.location.href);
                            const currentLocator = url.searchParams.get('locator');

                            // LOGIKA TOGGLE:
                            // Jika label yang diklik sama dengan filter yang sedang aktif, hapus filternya (Reset)
                            if (currentLocator === selectedLabel) {
                                url.searchParams.delete('locator');
                            } else {
                                // Jika beda atau belum ada filter, pasang filter baru
                                url.searchParams.set('locator', selectedLabel);
                            }

                            window.location.href = url.href;
                        }
                    }
                }
            });
        }

        // 3. Bar Chart (Top 10)
        const ctxBar = document.getElementById('barProduk');
        if (ctxBar) {
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($labelsBar) !!},
                    datasets: [{
                        label: 'Stok Akhir',
                        data: {!! json_encode($dataBar) !!},
                        backgroundColor: {!! json_encode($colorsBar) !!},
                        borderRadius: 4,
                        barThickness: 20
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (evt, item) => {
                        if (item.length > 0) {
                            const index = item[0].index;
                            const selectedProduct = evt.chart.data.labels[index];
                            
                            const url = new URL(window.location.href);
                            const currentSearch = url.searchParams.get('search');

                            if (currentSearch === selectedProduct) {
                                url.searchParams.delete('search');
                            } else {
                                url.searchParams.set('search', selectedProduct);
                            }
                            window.location.href = url.href;
                        }
                    },
                    plugins: { 
                        legend: { display: false },
                        datalabels: {
                            display: false 
                        },
                        tooltip: { enabled: true }
                    },
                    scales: { 
                        x: { 
                            beginAtZero: true,
                            // Membatasi agar bar tidak "off-side"
                            grace: '5%', 
                            grid: { 
                                display: true,
                                drawBorder: false,
                                color: 'rgba(150, 150, 150, 0.2)',
                                borderDash: [5, 5]
                            }, 
                            ticks: { 
                                font: { size: 10 },
                                maxRotation: 0 
                            } 
                        },
                        y: { 
                            type: 'category',
                            grid: { display: false }, 
                            ticks: { 
                                font: { size: 10 },
                                callback: function(val, index) {
                                    let label = this.getLabelForValue(val);
                                    if (label && label.length > 20) {
                                        return label.substr(0, 20) + '...';
                                    }
                                    return label;
                                }
                            } 
                        }
                    }
                }
            });
        }


        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('stockTable');
            const headers = table.querySelectorAll('th.sortable');
            const tableBody = table.querySelector('tbody');
            const rows = tableBody.querySelectorAll('tr');

            headers.forEach(header => {
                header.addEventListener('click', () => {
                    const type = header.getAttribute('data-type');
                    const column = header.cellIndex;
                    const sortOrder = header.classList.contains('sort-asc') ? 'desc' : 'asc';

                    // Reset semua header
                    headers.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));
                    header.classList.add(sortOrder === 'asc' ? 'sort-asc' : 'sort-desc');

                    const sortedRows = Array.from(rows).sort((a, b) => {
                        let aColText = a.querySelector(`td:nth-child(${column + 1})`).innerText.trim();
                        let bColText = b.querySelector(`td:nth-child(${column + 1})`).innerText.trim();

                        // Jika angka, hapus koma pemisah ribuan agar bisa dihitung
                        if (type === 'number') {
                            aColText = parseFloat(aColText.replace(/,/g, '')) || 0;
                            bColText = parseFloat(bColText.replace(/,/g, '')) || 0;
                        }

                        if (aColText < bColText) return sortOrder === 'asc' ? -1 : 1;
                        if (aColText > bColText) return sortOrder === 'asc' ? 1 : -1;
                        return 0;
                    });

                    // Masukkan kembali row yang sudah diurutkan
                    while (tableBody.firstChild) {
                        tableBody.removeChild(tableBody.firstChild);
                    }
                    tableBody.append(...sortedRows);
                });
            });
        });


        function toggleFilter(param, value) {
            const url = new URL(window.location.href);
            const currentValue = url.searchParams.get(param);

            if (currentValue === value) {
                url.searchParams.delete(param);
            } else {
                url.searchParams.set(param, value);
            }

            window.location.href = url.href;
        }


        // --- LOGIC DARK MODE ---
        const themeSwitcher = document.getElementById('themeSwitcher');
        const themeIcon = document.getElementById('themeIcon');
        const htmlTag = document.documentElement;

        function updateChartTheme(isDark) {
            const textColor = isDark ? '#e8eaed' : '#5f6368';
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
            
            Object.values(Chart.instances).forEach(chart => {
                if (chart.options.plugins?.legend?.labels) {
                    chart.options.plugins.legend.labels.color = textColor;
                }
                
                if (chart.options.scales) {
                    ['x', 'y'].forEach(axis => {
                        if (chart.options.scales[axis]) {
                            chart.options.scales[axis].ticks.color = textColor;
                            chart.options.scales[axis].grid.color = gridColor;
                        }
                    });
                }
                chart.update('none'); // 'none' agar update lebih cepat tanpa animasi berlebih
            });
        }

        // Fungsi untuk ganti tema saat tombol diklik
        function toggleTheme() {
            const isDark = htmlTag.getAttribute('data-bs-theme') === 'dark';
            const newTheme = isDark ? 'light' : 'dark';
            
            if (newTheme === 'dark') {
                htmlTag.setAttribute('data-bs-theme', 'dark');
                themeIcon.innerText = '☀️';
                localStorage.setItem('theme', 'dark');
                updateChartTheme(true);
            } else {
                htmlTag.removeAttribute('data-bs-theme');
                themeIcon.innerText = '🌙';
                localStorage.setItem('theme', 'light');
                updateChartTheme(false);
            }
        }

        themeSwitcher.addEventListener('click', toggleTheme);

        // Jalankan saat load
        document.addEventListener("DOMContentLoaded", function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            
            // Sinkronisasi icon saja (karena atribut HTML sudah dipasang di HEAD)
            themeIcon.innerText = (savedTheme === 'dark') ? '☀️' : '🌙';
            
            // Update chart setelah render pertama
            setTimeout(() => {
                updateChartTheme(savedTheme === 'dark');
            }, 200);

            // ... (Logika sidebar Anda yang sudah benar sebelumnya) ...
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
            }
            const mainContent = document.querySelector('.main-content');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                    toggleBtn.classList.toggle('collapsed');
                    document.getElementById('toggleIcon').innerText = sidebar.classList.contains('collapsed') ? '☰' : '☰';
                    
                    setTimeout(() => {
                        Object.values(Chart.instances).forEach(chart => chart.resize());
                    }, 350);
                });
            }
        });


    </script>
</body>
</html>