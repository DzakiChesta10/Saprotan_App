<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Saprotan Utama</title>
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
            --bg-body: #f8f9fa;
        }
        
        [data-bs-theme="dark"] {
            --card-bg: #242424;
            --text-color: #e8eaed;
            --bg-body: #121212;
        }

        body {
            transition: background-color 0.3s, color 0.3s;
            font-size: 16px;
            background-color: var(--bg-body) !important;
            color: var(--text-color);
        }

        .auth-card {
            max-width: 450px;
            width: 100%;
            border-radius: 15px;
            border: none;
            background-color: var(--card-bg);
            margin: auto;
        }

        .form-control {
            font-size: 16px;
        }

        /* Tombol Floating Theme Switcher */
        .btn-theme-toggle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--card-bg);
            border: 1px solid rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
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
<body class="d-flex align-items-center justify-content-center min-vh-100">

    <div class="container d-flex justify-content-center">
        <div class="card auth-card shadow-lg p-4">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo-su-w2.png') }}" style="max-height: 50px;" alt="Logo">
                <h5 class="mt-3 fw-bold">Registrasi Pengguna Baru</h5>
                <p class="text-muted small">Silakan buat akun untuk akses sistem</p>
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

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Email Kerja</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="nama@saprotan.com">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Minimal 8 karakter">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi password">
                </div>

                <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm">Daftar Akun</button>
            </form>
            
            <div class="text-center mt-4">
                <p class="small text-muted mb-0">Sudah punya akun?</p>
                <a href="{{ route('login') }}" class="small fw-bold text-decoration-none">Login di sini</a>
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 2000;">
        <button class="btn btn-theme-toggle shadow-lg" id="themeSwitcherFloating">
            <span id="themeIconFloating">🌙</span>
        </button>
    </div>

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

            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            applyTheme(savedTheme);

            // Toggle logic
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