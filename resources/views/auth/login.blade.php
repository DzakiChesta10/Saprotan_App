<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Saprotan Utama</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-su-w2.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <style>
        :root {
            --card-bg: #ffffff;
            --text-color: #212529;
        }
        
        [data-bs-theme="dark"] {
            --card-bg: #242424;
            --text-color: #e8eaed;
        }

        body { 
            transition: background-color 0.3s;
            font-size: 16px; 
        }

        [data-bs-theme="dark"] body { 
            background-color: #121212 !important; 
            color: var(--text-color); 
        }

        .auth-card { 
            max-width: 400px; 
            width: 100%; 
            border-radius: 15px; 
            border: none; 
            background-color: var(--card-bg);
            margin: auto;
        }

        .form-control {
            font-size: 16px;
        }

        .btn-theme-toggle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--bg-card);
            border: 1px solid rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .btn-theme-toggle:hover {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
        }

        [data-bs-theme="dark"] .btn-theme-toggle {
            background: #2d2d2d;
            border-color: rgba(255,255,255,0.1);
            color: #fff;
        }

        @media (max-width: 576px) {
            .auth-card {
                margin: 15px;
                padding: 1.5rem !important;
            }
        }
    </style>
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

    <div class="container d-flex justify-content-center">
        <div class="card auth-card shadow-lg p-4">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo-su-w2.png') }}" style="max-height: 60px;" alt="Logo Saprotan Utama">
                <h5 class="mt-3 fw-bold">Login</h5>
                <p class="text-muted small">Masuk untuk mengelola stok inventory</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm small">
                    <ul class="mb-0 list-unstyled text-center">
                        @foreach ($errors->all() as $error)
                            <li>⚠️ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm small text-center">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" 
                           placeholder="nama@saprotan.com" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" 
                           placeholder="Masukkan password" required>
                </div>
                
                <div class="form-check mb-3 small">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Ingatkan Saya
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">Masuk</button>
            </form>
            
            <div class="text-center mt-4">
                <p class="small text-muted mb-0">Belum punya akun?</p>
                <a href="{{ route('register') }}" class="small fw-bold text-decoration-none">Daftar Akun Baru</a>
            </div>
        </div>
    </div>

    <script>
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
    <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 2000;">
        <button class="btn btn-theme-toggle shadow-lg" id="themeSwitcherFloating">
            <span id="themeIconFloating">🌙</span>
        </button>
    </div>  
</body>
</html>