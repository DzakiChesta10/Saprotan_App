<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang - Saprotan Utama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <style>
        /* 1. VARIABEL TEMA */
        :root {
            --bg-body: #f8f9fa;
            --card-bg: #ffffff;
            --text-main: #212529;
            --border-color: rgba(0,0,0,0.1);
            --table-header: #212529;
        }

        [data-bs-theme="dark"] {
            --bg-body: #121212;
            --card-bg: #1e1e1e;
            --text-main: #e8eaed;
            --border-color: rgba(255,255,255,0.1);
            --table-header: #000000;
        }

        body { 
            background-color: var(--bg-body); 
            color: var(--text-main);
            transition: all 0.3s ease;
        }
        
        /* 2. STYLE TABEL & CARD */
        .table-container {
            background: var(--card-bg);
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .table { color: inherit; }
        
        [data-bs-theme="dark"] .table-hover tbody tr:hover {
            background-color: rgba(255,255,255,0.05);
            color: white;
        }

        

        /* Merapikan Header di Mobile */
        @media (max-width: 768px) {
            .header-section { flex-direction: column; text-align: center; }
            .header-actions { align-items: center !important; width: 100%; margin-top: 15px; }
            .btn-group-mobile { display: flex; width: 100%; gap: 5px; }
            .btn-group-mobile a { flex: 1; font-size: 0.8rem; padding: 8px 5px; }
            .filter-date { width: 100% !important; }
            .table { font-size: 0.85rem; }
            th, td { padding: 8px 4px !important; }
        }

        /* Tombol Tema Floating */
        .btn-theme-toggle {
            width: 50px; height: 50px; border-radius: 50%;
            background: var(--card-bg); border: 1px solid var(--border-color);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; transition: all 0.3s ease;
        }
        .btn-theme-toggle:hover { transform: scale(1.1); }

        /* Custom Scrollbar */
        .table-responsive::-webkit-scrollbar { height: 6px; }
        .table-responsive::-webkit-scrollbar-thumb {
            background: #ccc; border-radius: 10px;
        }
    </style>
</head>
<body class="container py-4">

    <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 2000;">
        <button class="btn btn-theme-toggle shadow-lg" id="themeSwitcherFloating">
            <span id="themeIconFloating">🌙</span>
        </button>
    </div>
    
    <div class="header-section d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">Stock Inventory</h2>
        
        <div class="header-actions d-flex flex-column align-items-end gap-2">
            <div class="btn-group-mobile d-flex gap-2">
                <a href="/" class="btn btn-secondary shadow-sm">⬅️ Menu</a>
                <a href="{{ route('barang.export') }}" class="btn btn-success shadow-sm">Export to CSV</a>
                <a href="{{ route('barang.create') }}" class="btn btn-primary shadow-sm">+ Transaksi</a>
            </div>

            <form action="{{ route('barang.index') }}" method="GET" class="filter-date d-flex align-items-center gap-2 bg-white p-2 rounded border shadow-sm">
                <label class="fw-bold small mb-0 text-dark">Tanggal:</label>
                <input type="date" name="date" class="form-control form-control-sm border-0" value="{{ $date }}" onchange="this.form.submit()">
            </form>
        </div>
    </div>

    <div class="alert alert-info border-0 shadow-sm py-2 d-flex justify-content-between align-items-center">
        <span>📅 <strong>{{ date('d M Y', strtotime($date)) }}</strong></span>
        <span class="badge bg-primary">{{ $barangs->count() }} Data</span>
    </div>

    @if($barangs->isEmpty())
        <div class="card border-0 shadow-sm p-5 text-center my-4" style="background: var(--card-bg);">
            <h4 class="text-muted">Kosong</h4>
            <p class="mb-0">Tidak ada transaksi pada tanggal ini.</p>
        </div>
    @else

    <div class="table-container shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="min-width: 80px;">Code</th>
                        <th style="min-width: 150px;">Nama Barang</th>
                        <th style="min-width: 100px;">Locator</th>
                        <th>Uom</th>
                        <th>Stok Awal</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th class="bg-primary text-white">Stok Akhir</th>
                        <th style="min-width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangs as $b)
                    <tr>
                        <td class="small fw-bold text-center">{{ $b->code }}</td>
                        <td>
                            <div class="fw-bold text-wrap" style="max-width: 200px;">{{ $b->nama_pupuk }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">Update: {{ $b->updated_at->format('H:i') }}</div>
                        </td>
                        <td class="text-center small">{{ $b->locator }}</td>
                        <td class="text-center">{{ $b->uom }}</td>
                        <td class="text-center text-muted">{{ $b->stok_awal }}</td>
                        <td class="text-center fw-bold text-success">{{ $b->masuk > 0 ? '+'.$b->masuk : '0' }}</td>
                        <td class="text-center fw-bold text-danger">{{ $b->keluar > 0 ? '-'.$b->keluar : '0' }}</td>
                        <td class="text-center fw-bold bg-opacity-10 {{ $b->stok_total < 500 ? 'text-danger' : '' }}">{{ $b->stok_total }}</td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('barang.edit', $b->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('barang.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="text-center d-block d-md-none mt-2 text-muted small">
        Geser tabel ke samping &rarr;
    </div>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const themeBtn = document.getElementById('themeSwitcherFloating');
            const themeIcon = document.getElementById('themeIconFloating');
            const htmlTag = document.documentElement;

            function applyTheme(theme) {
                if (theme === 'dark') {
                    htmlTag.setAttribute('data-bs-theme', 'dark');
                    themeIcon.innerText = '☀️';
                } else {
                    htmlTag.removeAttribute('data-bs-theme');
                    themeIcon.innerText = '🌙';
                }
            }

            const savedTheme = localStorage.getItem('theme') || 'light';
            applyTheme(savedTheme);

            themeBtn.addEventListener('click', function() {
                const currentTheme = htmlTag.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('theme', newTheme);
                applyTheme(newTheme);
            });
        });
    </script>
</body>
</html>