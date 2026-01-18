<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Informasi Pelayanan Bahasa UHO (SIPENA)')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style nonce="{{ $csp_nonce ?? '' }}">
        body {
            padding-top: 56px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
        }

        /* Rounded corners for navbar as separate class */
        .navbar.rounded-custom {
            border-radius: 0 0 10px 10px !important;
        }

        /* Remove mt-4 from body since navbar is fixed-top again */
        body {
            padding-top: 56px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
        }
        .sidebar {
            min-height: calc(100vh - 56px);
        }
        .card-hover {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
        }
        .navbar-brand img {
            max-height: 40px;
            width: auto;
        }
        @media (max-width: 576px) {
            .navbar-brand img {
                max-height: 30px;
            }
        }
        
        .navbar-logo {
            height: 30px;
            width: auto;
        }
        
        .footer-logo {
            height: 50px;
            width: auto;
        }
        
        .welcome-logo {
            max-width: 350px;
            height: auto;
            border: 2px solid #dee2e6;
        }
        
        .w-5p { width: 5% !important; }
        .w-20p { width: 20% !important; }
        .w-35px { width: 35px !important; }
        .h-35px { height: 35px !important; }
        .w-40px { width: 40px !important; }
        .h-40px { height: 40px !important; }
        .w-60px { width: 60px !important; }
        .h-60px { height: 60px !important; }
        .w-80px { width: 80px !important; }
        .h-80px { height: 80px !important; }
        .w-100px { width: 100px !important; }
        .h-100px { height: 100px !important; }
        .w-120px { width: 120px !important; }
        .w-150px { width: 150px !important; }
        .mh-150px { max-height: 150px !important; }
        .mh-200px { max-height: 200px !important; }
        .mh-300px { max-height: 300px !important; }
        .h-300px { height: 300px !important; }
        .min-h-500px { min-height: 500px !important; }
        .fs-07rem { font-size: 0.7rem !important; }
        .fs-2rem { font-size: 2rem !important; }
        .object-cover { object-fit: cover !important; }
        .grid-4-1 { display: grid !important; grid-template-columns: 4fr 1fr !important; gap: 1.5rem !important; }
        .bg-teal-200 { background-color: #99f6e4 !important; }
        .border-dashed { border-style: dashed !important; }

        /* Custom scrollbar styling for scrollable tables */
        .table-responsive[style*="max-height"] {
            -ms-overflow-style: -ms-autohiding-scrollbar; /* for Internet Explorer */
            scrollbar-width: thin; /* for Firefox */
        }

        .table-responsive[style*="max-height"]::-webkit-scrollbar {
            width: 8px; /* for Chrome, Safari, and Opera */
        }

        .table-responsive[style*="max-height"]::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive[style*="max-height"]::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .table-responsive[style*="max-height"]::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* CSS animation for auto-scrolling table */
        .marquee-table {
            animation: marqueeScroll linear infinite;
            animation-play-state: running;
        }

        .marquee-table.paused {
            animation-play-state: paused;
        }

        @keyframes marqueeScroll {
            0% { transform: translateY(0); }
            100% { transform: translateY(-50%); }
        }

        /* Sticky header for schedule table on welcome page */
        body.welcome-page #scheduleTableContainer .table thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 10;
            border-bottom: 2px solid #dee2e6; /* Add border to make header separation clear */
        }


        body.welcome-page .schedule-card {
            transition: all 0.3s ease;
            border-left: 4px solid #0d6efd !important;
        }

        body.welcome-page .schedule-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1) !important;
            border-left: 4px solid #0b5ed7 !important;
        }

        /* Custom scrollbar styling for schedule cards container */
        body.welcome-page .schedule-list-container {
            scrollbar-width: thin;
            scrollbar-color: #0d6efd #f8f9fa;
        }

        body.welcome-page .schedule-list-container::-webkit-scrollbar {
            width: 8px;
        }

        body.welcome-page .schedule-list-container::-webkit-scrollbar-track {
            background: #f8f9fa;
            border-radius: 4px;
        }

        body.welcome-page .schedule-list-container::-webkit-scrollbar-thumb {
            background-color: #0d6efd;
            border-radius: 4px;
        }

        /* Make footer align with registration steps section width */
        body.welcome-page .footer .container {
            max-width: 100%;
        }

        /* Align footer width with registration container */
        body.welcome-page .align-footer-with-content {
            max-width: 100%;
            margin: 0 auto;
        }

        /* Ensure footer width matches the registration content width */
        body.welcome-page .footer {
            width: 100%;
        }

        body.welcome-page .footer > .align-footer-with-content {
            width: 100%;
        }

        /* Match the exact width of the registration container */
        @media (min-width: 576px) {
            body.welcome-page .align-footer-with-content {
                max-width: 540px;
            }
        }

        @media (min-width: 768px) {
            body.welcome-page .align-footer-with-content {
                max-width: 720px;
            }
        }

        @media (min-width: 992px) {
            body.welcome-page .align-footer-with-content {
                max-width: 960px;
            }
        }

        @media (min-width: 1200px) {
            body.welcome-page .align-footer-with-content {
                max-width: 1140px;
            }
        }

        @media (min-width: 1400px) {
            body.welcome-page .align-footer-with-content {
                max-width: 1320px;
            }
        }

        /* Add padding to match registration container */
        body.welcome-page .align-footer-with-content {
            padding-left: 15px;
            padding-right: 15px;
        }

        /* Make footer content area match the registration card exactly */
        body.welcome-page .footer-content-match {
            max-width: 100%;
        }

        /* Adjust footer text colors to match dark theme when inside light card */
        body.welcome-page .footer .card .text-light {
            color: #212529 !important;
        }

        body.welcome-page .footer .card a.text-light,
        body.welcome-page .footer .card .text-light h5,
        body.welcome-page .footer .card .text-light p,
        body.welcome-page .footer .card .text-light span {
            color: #212529 !important;
        }

        body.welcome-page .footer .card hr {
            background-color: #6c757d;
        }

        /* Round the corners of the footer */
        .footer.rounded {
            border-radius: 0.5rem !important;
            overflow: hidden;
        }

        /* Round the corners of the header to match footer */
        .navbar.rounded {
            border-radius: 0.5rem !important;
            overflow: hidden;
        }

        /* Make navbar width match footer width exactly */
        .navbar.fixed-top.navbar-aligned {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: auto;
            margin: 0 auto;
            max-width: 100%;
        }

        /* Use the same container widths as Bootstrap for consistency with footer */
        @media (min-width: 576px) {
            .navbar.fixed-top.navbar-aligned {
                max-width: 540px;
            }
        }

        @media (min-width: 768px) {
            .navbar.fixed-top.navbar-aligned {
                max-width: 720px;
            }
        }

        @media (min-width: 992px) {
            .navbar.fixed-top.navbar-aligned {
                max-width: 960px;
            }
        }

        @media (min-width: 1200px) {
            .navbar.fixed-top.navbar-aligned {
                max-width: 1140px;
            }
        }

        @media (min-width: 1400px) {
            .navbar.fixed-top.navbar-aligned {
                max-width: 1320px;
            }
        }

        .step-number-container {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #0d6efd 0%, #00d2ff 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 800;
            border: 4px solid #fff;
            transition: transform 0.3s ease;
        }

        .card-hover:hover .step-number-container {
            transform: scale(1.1) rotate(5deg);
        }

        .rounded-4 {
            border-radius: 1.5rem !important;
        }

    </style>
</head>
<body class="@yield('body-class')">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top navbar-aligned rounded-custom">
        <div class="container-fluid px-3">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('logo-uho-dan-diktisaintek-768x143nobg.png') }}"
                     alt="Logo UHO dan Diktisaintek"
                     class="me-3 navbar-logo">
                <span>SIPENA UPA BAHASA UHO</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if(Auth::guard('web')->check())
                        <li class="nav-item">
                            @if(Auth::user()->role === 'prodi')
                                <a class="nav-link" href="{{ route('prodi.dashboard') }}">Dashboard Prodi</a>
                            @else
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                            @endif
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.profile') }}">Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="#" id="btn-logout-nav">
                                <i class="fas fa-sign-out-alt me-1"></i>Keluar
                            </a>
                            <form id="logout-form-nav" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container mt-4">
        <main>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer bg-dark text-white mt-5 pt-5 pb-3 rounded">
            <div class="row g-3 px-3">
                <!-- Information Section -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <img src="{{ asset('logo-uho-dan-diktisaintek-768x143nobg.png') }}"
                             alt="Logo UHO dan Diktisaintek"
                             class="mb-2 footer-logo">
                    </div>
                    <p class="text-light mb-3">
                        Sistem Informasi Pelayanan Bahasa (SIPENA) UHO Kendari.
                        Platform layanan bahasa yang mudah, cepat, dan aman.
                    </p>
                    <div>
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>

                <!-- Quick Links Section -->
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3 text-light">Tautan Cepat</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-1"><a href="{{ url('/') }}" class="text-light text-decoration-none">Beranda</a></li>
                                <li class="mb-1"><a href="{{ route('participant.register.form') }}" class="text-light text-decoration-none">Pendaftaran</a></li>
                                <li class="mb-1"><a href="{{ route('participant.login') }}" class="text-light text-decoration-none">Login Peserta</a></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-1"><a href="#" class="text-light text-decoration-none">Jadwal Ujian</a></li>
                                <li class="mb-1"><a href="#" class="text-light text-decoration-none">Cara Pendaftaran</a></li>
                                <li class="mb-1"><a href="#" class="text-light text-decoration-none">FAQ</a></li>
                                <li class="mb-1"><a href="#" class="text-light text-decoration-none">Kontak</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3 text-light">Kontak</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            <span class="text-light">Kampus Universitas Halu Oleo, Anduonohu, Kendari, Sulawesi Tenggara</span>
                        </li>
                        <li class="mb-2">
                            <i class="fab fa-whatsapp text-primary me-2"></i>
                            <span class="text-light">Help Desk: +6281392955256 <small>(WhatsApp Only)</small></span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <span class="text-light">upabahasa@uho.ac.id</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <span class="text-light">Senin - Jumat: 09:00 - 15:00 WITA</span>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="my-3 bg-secondary">

            <div class="row align-items-center px-3">
                <div class="col-md-6">
                    <p class="mb-0 text-light">
                        &copy; {{ date('Y') }} Sistem Informasi Pelayanan Bahasa UHO (SIPENA) - Universitas Halu Oleo. Hak Cipta Dilindungi.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-light text-decoration-none me-3">Kebijakan Privasi</a>
                    <a href="#" class="text-light text-decoration-none">Syarat & Ketentuan</a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script nonce="{{ $csp_nonce ?? '' }}">
        document.addEventListener('DOMContentLoaded', function() {
            const logoutNavBtn = document.getElementById('btn-logout-nav');
            if (logoutNavBtn) {
                logoutNavBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('logout-form-nav').submit();
                });
            }
        });
    </script>

    @yield('modals')
    @yield('scripts')
</body>
</html>