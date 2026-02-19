<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang - Saprotan Utama</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-su-w2.png') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <style>
        :root {
            --primary-color: #0d6efd;
            --sidebar-width: 280px;
            --bg-body: #f8f9fa;
            --card-bg: #ffffff;
            --sidebar-gradient: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            --text-main: #212529;
            --border-color: rgba(0,0,0,0.1);
            --accent-soft: rgba(13, 110, 253, 0.05);
        }

        [data-bs-theme="dark"] {
            --bg-body: #121212;
            --card-bg: #242424;
            --sidebar-gradient: linear-gradient(180deg, #242424 0%, #1e1e1e 100%);
            --text-main: #e8eaed;
            --border-color: rgba(255,255,255,0.05);
        }

        body { 
            background-color: var(--bg-body); 
            color: var(--text-main);
            transition: all 0.3s ease; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }

        .sidebar { 
            background: var(--sidebar-gradient) !important; 
            border-right: 1px solid var(--border-color) !important; 
            height: 100vh; 
            padding: 25px; 
            position: fixed; 
            width: var(--sidebar-width); 
            z-index: 1050; 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar.collapsed { transform: translateX(-100%); }

        .filter-label {
            font-size: 11px; font-weight: 700; color: #64748b;
            text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; display: block;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }

        .main-content.expanded { margin-left: 0; }

        .nav-link-custom {
            display: block;
            align-items: center;
            padding: 12px 20px;
            color: #64748b;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s;
            font-size: 14px;
        }

        .nav-link-custom:hover {
            background: var(--accent-soft);
            color: var(--primary-color);
        }

        .nav-link-custom.active {
            background: var(--primary-color);
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        [data-bs-theme="dark"] .nav-link-custom {
            color: #a1a1aa;
        }

        [data-bs-theme="dark"] .nav-link-custom:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
        }

        #sidebarToggle {
            background: var(--card-bg); 
            color: var(--primary-color);
            border: 1px solid var(--border-color); 
            width: 50px; height: 50px;
            z-index: 1050;
        }

        #themeSwitcher {
            width: 50px; height: 50px;
            border-radius: 50%;
            background: var(--card-bg);
            border: 1px solid rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .table-container {
            background: var(--card-bg);
            border-radius: 15px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .sidebar {
            transition: all 0.3s ease-in-out;
        }
        .main-content {
            transition: all 0.3s ease-in-out;
        }

        .select2-container--default .select2-selection--single {
            background-color: var(--card-bg) !important;
            border: 1px solid var(--border-color) !important;
            height: 40px !important;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .select2-dropdown {
            background-color: var(--card-bg) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-main) !important;
            z-index: 1060;
        }

        .select2-container--default .select2-results__option {
            background-color: var(--card-bg);
            color: var(--text-main) !important;
            padding: 8px 12px;
        }
        html body .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #0d6efd !important; 
            color: #ffffff !important;  
        }

        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: var(--accent-soft) !important;
            color: #0d6efd !important;
        }

        [data-bs-theme="dark"] .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3d8bfd !important; 
            color: #ffffff !important;
        }
        [data-bs-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e8eaed !important;
        }


        @media (max-width: 768px) {
            .sidebar {
                width: 100% !important;
                height: auto !important;
                max-height: 80vh;
                top: 0;
                left: 0;
                transform: translateY(-100%);
                border-right: none !important;
                border-bottom: 1px solid rgba(0,0,0,0.1);
                padding-top: 70px;
                overflow-y: auto;
            }

            .sidebar.collapsed {
                transform: translateY(-100%);
            }

            .sidebar:not(.collapsed) {
                transform: translateY(0);
            }

            .main-content {
                margin-left: 0 !important;
                padding-top: 80px;
            }
        }

        .sidebar {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
    </style>
</head>
<body>

    <div class="position-fixed top-0 start-0 p-2 d-flex gap-2" style="z-index: 1100;">
        <button class="btn shadow-sm rounded-circle shadow-sm border" id="sidebarToggle">
            <span id="toggleIcon">☰</span>
        </button>
    </div>

    <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 2000;">
        <button class="btn shadow-lg" id="themeSwitcher">
            <span id="themeIcon">🌙</span>
        </button>
    </div>

    <div class="sidebar shadow-sm">
        <div class="text-center mb-3">
            <img src="{{ asset('images/logo-su-w2.png') }}" alt="Logo" width="80">
            <h5 class="mt-3 fw-bold text-uppercase" style="letter-spacing: 1px; ">Saprotan Utama</h5>
        </div>

        <hr class="mx-3 opacity-10">

        <label class="filter-label text-primary px-3"><i class="bi bi-house"></i> Main Menu</label>
        <div class="nav flex-column gap-1 mb-4 px-2">
            
            <a href="{{ route('barang.dashboard') }}" class="nav-link-custom {{ request()->routeIs('barang.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a href="{{ route('barang.index') }}" class="nav-link-custom {{ request()->routeIs('barang.index') ? 'active' : '' }}">
                <i class="bi bi-box-seam me-2"></i> Stock Inventory
            </a>
        </div>

        <hr class="mx-3 opacity-10">

        <label class="filter-label text-danger px-3"><i class="bi bi-door-open"></i> Session</label>
        <div class="nav flex-column gap-1 mb-4 px-2">
            <a href="#" class="nav-link-custom text-danger" 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-left me-2"></i> Sign Out
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>

    <main class="main-content" id="mainContent">
        <div class="header-section d-flex align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Stock Inventory</h2>
                <p class="text-muted mb-0">Manajemen data stok barang harian</p>
            </div>
            <div class="ms-auto d-flex gap-2">
                <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="bi bi-file-earmark-arrow-down"></i>
                    <span class="d-none d-sm-inline ms-1">Import</span>
                </button>
                <a href="{{ route('barang.export') }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-arrow-up"></i>
                    <span class="d-none d-sm-inline ms-1">Export</span>
                </a>
                <a href="{{ route('barang.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    <span class="d-none d-sm-inline ms-1">Transaksi Baru</span>
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4 p-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <select id="searchBarang" class="form-control select2">
                        <option></option> @foreach($listBarang as $lb)
                            <option value="{{ $lb->code }}">{{ $lb->code }} - {{ $lb->nama_pupuk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <form action="{{ route('barang.index') }}" method="GET">
                        <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="{{ route('barang.destroyByDate') }}" method="POST" onsubmit="return confirm('Hapus SEMUA data pada tanggal ini?')">
                        @csrf @method('DELETE')
                        <input type="hidden" name="date" value="{{ $date }}">
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash"></i> Bersihkan Hari Ini
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="alert alert-primary shadow-sm d-flex justify-content-between align-items-center mb-4 border-0">
            <span><i class="bi bi-calendar3 me-2"></i> Laporan: <strong>{{ date('d M Y', strtotime($date)) }}</strong></span>
            <span class="badge bg-white text-primary px-3 py-2 fs-6 shadow-sm">{{ $barangs->count() }} Item</span>
        </div>

        <div class="table-container shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th>Code</th>
                            <th class="text-start">Nama Barang</th>
                            <th>Locator</th>
                            <th>Uom</th>
                            <th>Awal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th class="bg-primary">Akhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $b)
                        <tr>
                            <td class="text-center small fw-bold">{{ $b->code }}</td>
                            <td>
                                <div class="fw-bold">{{ $b->nama_pupuk }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">Update: {{ $b->updated_at->format('H:i') }}</small>
                            </td>
                            <td class="text-center small">{{ $b->locator }}</td>
                            <td class="text-center">{{ $b->uom }}</td>
                            <td class="text-center text-muted">{{ $b->stok_awal }}</td>
                            <td class="text-center fw-bold text-success">+{{ $b->masuk }}</td>
                            <td class="text-center fw-bold text-danger">-{{ $b->keluar }}</td>
                            <td class="text-center fw-bold {{ $b->stok_total < 100 ? 'text-danger' : '' }}">{{ $b->stok_total }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('barang.edit', $b->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('barang.destroy', $b->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data?')"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data transaksi pada tanggal ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalImport" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('barang.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Import Laporan Stok</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">1. Pilih File Excel (.xlsx)</label>
                            <input type="file" name="file_excel" id="excel_file" class="form-control" required accept=".xlsx">
                        </div>
                        <div class="mb-3" id="sheet_selector_wrapper" style="display: none;">
                            <label class="form-label">2. Pilih Sheet</label>
                            <select name="sheet_index" id="sheet_index" class="form-select"></select>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" id="btn_submit_import" class="btn btn-primary w-100" disabled>Upload & Proses</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function() {
            // 1. Initialize Select2
            $('#searchBarang').select2({
                width: '100%',
                placeholder: "🔍 Cari Kode atau Nama Barang...",
                allowClear: true
            });

            let kodesDiTabel = [];
            $("tbody tr td:first-child").each(function() {
                kodesDiTabel.push($(this).text().trim());
            });

            $('#searchBarang option').each(function() {
                let optionValue = $(this).val();
                if (optionValue !== "" && !kodesDiTabel.includes(optionValue)) {
                    $(this).remove();
                }
            });

            // 2. Real-time Search Table
            $('#searchBarang').on('change', function() {
                let val = $(this).val().toLowerCase();
                $("tbody tr").each(function() {
                    let rowText = $(this).text().toLowerCase();
                    $(this).toggle(rowText.includes(val));
                });
            });

            // 3. Sidebar Logic
            const sidebar = $('.sidebar');
            const mainContent = $('.main-content');
            const toggleBtn = $('#sidebarToggle');

            function checkMobile() {
                if (window.innerWidth <= 768) {
                    sidebar.addClass('collapsed');
                }
            }
            checkMobile();

            toggleBtn.on('click', function() {
                sidebar.toggleClass('collapsed');
                
                if (window.innerWidth > 768) {
                    mainContent.toggleClass('expanded');
                }
            });

            // 4. Dark Mode Logic
            const themeSwitcher = $('#themeSwitcher');
            const themeIcon = $('#themeIcon');
            const htmlTag = $('html');

            themeSwitcher.on('click', function() {
                const currentTheme = htmlTag.attr('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                htmlTag.attr('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                themeIcon.text(newTheme === 'dark' ? '☀️' : '🌙');
            });

            const savedTheme = localStorage.getItem('theme') || 'light';
            themeIcon.text(savedTheme === 'dark' ? '☀️' : '🌙');

            // 5. Excel Sheet Reader
            $('#excel_file').on('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {type: 'array'});
                    const sheetNames = workbook.SheetNames;
                    const select = $('#sheet_index');

                    select.empty();
                    sheetNames.forEach((name, index) => {
                        select.append(`<option value="${index}">Sheet ${index + 1}: ${name}</option>`);
                    });

                    $('#sheet_selector_wrapper').fadeIn();
                    $('#btn_submit_import').prop('disabled', false);
                };
                reader.readAsArrayBuffer(file);
            });
        });
    </script>
</body>
</html>