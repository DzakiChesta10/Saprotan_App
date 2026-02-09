<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Saprotan Utama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <style>
        :root {
            --bg-body: #f8f9fa;
            --card-bg: #ffffff;
            --text-label: #1a1a1a;
        }
        [data-bs-theme="dark"] {
            --bg-body: #121212;
            --card-bg: #1e1e1e;
            --text-label: #e8eaed;
        }

        body { 
            background-color: var(--bg-body); 
            transition: background 0.3s ease; 
            color: var(--text-label); 
        }

        .card { 
            border-radius: 15px; 
            background-color: var(--card-bg); 
            border: none; 
            transition: background 0.3s ease;
        }

        .card-header { 
            border-radius: 15px 15px 0 0 !important; 
            font-weight: bold; 
        }
        
        /* Floating Theme Switcher */
        .btn-theme-toggle {
            width: 50px; height: 50px; border-radius: 50%;
            background: var(--card-bg); border: 1px solid rgba(0,0,0,0.1);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .btn-theme-toggle:hover {
            transform: scale(1.1) rotate(15deg);
        }

        [data-bs-theme="dark"] .btn-theme-toggle { 
            border-color: rgba(255,255,255,0.1);
            color: #fff;
        }

        /* Select2 Dark Mode Fix */
        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection {
            background-color: #2b2b2b;
            border-color: #444;
            color: white;
        }
        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: white;
        }
        [data-bs-theme="dark"] .select2-dropdown {
            background-color: #2b2b2b;
            color: white;
        }
    </style>
</head>
<body class="container mt-5">

    <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 2000;">
        <button class="btn btn-theme-toggle shadow-lg" id="themeSwitcherFloating">
            <span id="themeIconFloating">🌙</span>
        </button>
    </div>

    <div class="card col-md-8 mx-auto shadow">
        <div class="card-header bg-warning text-dark text-center">
            EDIT DATA BARANG / TRANSAKSI
        </div>

        <p class="text-center text-muted small mt-3">
            Data ini terakhir diperbarui pada: {{ $barang->updated_at->diffForHumans() }}
        </p>
        
        <div class="card-body">
            <form action="{{ route('barang.update', $barang->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clock"></i> Waktu Server:</span>
                    <strong id="live-clock">--:--:--</strong>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Code</label>
                        <select name="code" class="form-select select2-pencarian" required>
                            @foreach($codes as $c)
                                <option value="{{ $c->code }}" {{ $barang->code == $c->code ? 'selected' : '' }}>
                                    {{ $c->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Name (Nama Pupuk)</label>
                        <select name="nama_pupuk" class="form-select select2-pencarian" required>
                            @foreach($names as $n)
                                <option value="{{ $n->nama_pupuk }}" {{ $barang->nama_pupuk == $n->nama_pupuk ? 'selected' : '' }}>
                                    {{ $n->nama_pupuk }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Locator (Gudang)</label>
                        <select name="locator" class="form-select select2-pencarian" required>
                            @foreach($locators as $l)
                                <option value="{{ $l->locator }}" {{ $barang->locator == $l->locator ? 'selected' : '' }}>
                                    {{ $l->locator }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Uom</label>
                        <select name="uom" class="form-select select2-pencarian" required>
                            @foreach($uoms as $u)
                                <option value="{{ $u->uom }}" {{ $barang->uom == $u->uom ? 'selected' : '' }}>
                                    {{ $u->uom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">STOCK AWAL</label>
                        <input type="number" name="stok_awal" class="form-control text-center" value="{{ $barang->stok_awal }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-success fw-bold">MASUK (+)</label>
                        <input type="number" name="masuk" class="form-control text-center" value="{{ $barang->masuk }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-danger fw-bold">KELUAR (-)</label>
                        <input type="number" name="keluar" class="form-control text-center" value="{{ $barang->keluar }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold">UPDATE DATA & HITUNG STOK</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary w-100 mt-2">BATAL</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            const htmlTag = document.documentElement;
            const themeBtn = document.getElementById('themeSwitcherFloating');
            const themeIcon = document.getElementById('themeIconFloating');

            // Inisialisasi Select2
            $('.select2-pencarian').select2({
                theme: 'bootstrap-5',
                tags: true,
                width: '100%'
            });

            // Fungsi Apply Tema
            function applyTheme(theme) {
                if (theme === 'dark') {
                    htmlTag.setAttribute('data-bs-theme', 'dark');
                    themeIcon.innerText = '☀️';
                } else {
                    htmlTag.removeAttribute('data-bs-theme');
                    themeIcon.innerText = '🌙';
                }
            }

            // Load tema tersimpan
            const savedTheme = localStorage.getItem('theme') || 'light';
            applyTheme(savedTheme);

            // Event Klik Toggle Tema
            themeBtn.addEventListener('click', function() {
                const currentTheme = htmlTag.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                localStorage.setItem('theme', newTheme);
                applyTheme(newTheme);
            });

            // Jam Live
            function updateClock() {
                const now = new Date();
                document.getElementById('live-clock').innerText = now.toLocaleTimeString('id-ID') + " WIB";
            }
            setInterval(updateClock, 1000);
            updateClock();
        });
    </script>
</body>
</html>