<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saprotan Utama - Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>
    <style>
        /* 1. DEFINISI VARIABEL */
        :root {
            --bg-gradient: radial-gradient(circle at top right, #f8f9fa, #e9ecef);
            --card-bg: rgba(255, 255, 255, 0.9);
            --text-title: #1a1a1a;
            --card-hover: #ffffff;
            --badge-bg: white;
            --icon-bg: #f0f4ff;
        }

        [data-bs-theme="dark"] {
            --bg-gradient: radial-gradient(circle at top right, #121212, #1e1e1e);
            --card-bg: rgba(45, 45, 45, 0.9);
            --text-title: #ffffff;
            --card-hover: #353535;
            --badge-bg: #2d2d2d;
            --icon-bg: #3d3d3d;
        }

        /* 2. STYLE DASAR */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
            margin: 0;
            transition: background 0.3s ease;
        }

        .portal-container {
            max-width: 900px;
            width: 100%;
            padding: 0 20px;
        }

        /* 3. CARD & KOMPONEN */
        .portal-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            color: inherit;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .portal-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important;
            background: var(--card-hover);
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: var(--icon-bg);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 20px;
            transition: 0.3s;
        }

        .user-badge {
            background: var(--badge-bg);
            color: var(--text-title);
            padding: 10px 20px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .fs-mobile-title {
            color: var(--text-title) !important;
            letter-spacing: -1px;
        }

        #liveClock {
            font-weight: 600;
            color: #6c757d;
        }

        [data-bs-theme="dark"] #liveClock {
            color: #a0a0a0;
        }

        /* 4. TOMBOL & LAINNYA */
        .btn-portal {
            border-radius: 12px;
            font-weight: 600;
            padding: 10px;
            transition: 0.3s;
        }

        .btn-logout-custom {
            color: #dc3545;
            border: 2px solid #dc3545;
            border-radius: 12px;
            font-weight: 700;
            padding: 10px 25px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
        }

        .btn-logout-custom:hover {
            background-color: #dc3545;
            color: #ffffff;
            transform: scale(1.05);
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

        @media (max-width: 768px) {
            .fs-mobile-title { font-size: 1.5rem !important; }
            .portal-card { padding: 30px !important; }
        }
    </style>
</head>
<body>
    <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 2000;">
        <button class="btn btn-theme-toggle shadow-lg" id="themeSwitcherFloating">
            <span id="themeIconFloating">🌙</span>
        </button>
    </div>

<div class="portal-container text-center">
    <div class="mb-5">
        <img src="{{ asset('images/logo-su-w2.png') }}" alt="Logo" class="img-fluid mb-4" style="max-width: 180px;">
        
        @auth
            <div class="d-block">
                <div class="user-badge mb-2">
                    <span class="text-primary">👋</span>
                    <span class="fw-bold">Halo, {{ Auth::user()->name }}</span>
                </div>
                <div id="liveClock" class="small mt-1"></div>
            </div>
        @endauth
        
        <h2 class="fw-800 text-uppercase mt-4 fs-mobile-title" style="letter-spacing: -1px; color: #1a1a1a;">
            Sistem Monitoring Stok
        </h2>
    </div>

    <div class="row justify-content-center g-4 mt-2">
        @auth
            <div class="col-11 col-md-5">
                <a href="{{ route('barang.index') }}" class="card portal-card shadow-sm p-4 p-md-5">
                    <div class="icon-wrapper">📦</div>
                    <h4 class="fw-bold">Input Data</h4>
                    <p class="text-muted small mb-4">Kelola stok, pergerakan barang, dan manajemen inventory harian.</p>
                    <div class="btn btn-primary btn-portal shadow-sm">Buka Inventory</div>
                </a>
            </div>

            <div class="col-11 col-md-5">
                <a href="{{ route('barang.dashboard') }}" class="card portal-card shadow-sm p-4 p-md-5">
                    <div class="icon-wrapper">📈</div>
                    <h4 class="fw-bold">Visualisasi</h4>
                    <p class="text-muted small mb-4">Analisis data real-time, grafik stok, dan ringkasan performa gudang.</p>
                    <div class="btn btn-success btn-portal shadow-sm">Buka Dashboard</div>
                </a>
            </div>
            
            <div class="col-12 mt-5">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-logout-custom shadow-sm">
                        🔒 Keluar dari Sistem
                    </button>
                </form>
            </div>
        @else
            <div class="col-11 col-md-6">
                <div class="card portal-card shadow-lg p-5">
                    <div class="icon-wrapper">🔒</div>
                    <h4 class="fw-bold">Akses Terbatas</h4>
                    <p class="text-muted mb-4">Selamat datang di Portal Saprotan Utama. Silakan masuk untuk mengakses fitur inventory.</p>
                    <div class="d-grid gap-3">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-portal shadow-sm">Login Pegawai</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-portal">Daftar Akun</a>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit', 
            minute: '2-digit',
            second: '2-digit'
        };
        const timeString = now.toLocaleDateString('id-ID', options);
        document.getElementById('liveClock').textContent = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();


    document.addEventListener("DOMContentLoaded", function() {
        const themeBtn = document.getElementById('themeSwitcherFloating');
        const themeIcon = document.getElementById('themeIconFloating');
        const htmlTag = document.documentElement;

        // 1. Fungsi Update Icon & Chart
        function applyTheme(theme) {
            if (theme === 'dark') {
                htmlTag.setAttribute('data-bs-theme', 'dark');
                themeIcon.innerText = '☀️';
                if (typeof updateChartTheme === "function") updateChartTheme(true);
            } else {
                htmlTag.removeAttribute('data-bs-theme');
                themeIcon.innerText = '🌙';
                if (typeof updateChartTheme === "function") updateChartTheme(false);
            }
        }

        // 2. Jalankan saat halaman dibuka
        const savedTheme = localStorage.getItem('theme') || 'light';
        applyTheme(savedTheme);

        // 3. Logika Klik Tombol
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