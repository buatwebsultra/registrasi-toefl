@if(!isset($isPdf) || !$isPdf)
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Digital Card - {{ $participant->name }}</title>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
            rel="stylesheet">
        <style nonce="{{ $csp_nonce ?? '' }}">
            :root {
                --primary: #0ea5e9;
                --primary-dark: #0369a1;
                --accent: #8b5cf6;
                --success: #10b981;
                --bg: #f8fafc;
                --card-bg: #ffffff;
                --text-main: #1e293b;
                --text-muted: #64748b;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                -webkit-tap-highlight-color: transparent;
            }

            body {
                font-family: 'Outfit', sans-serif;
                background-color: var(--bg);
                color: var(--text-main);
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 20px;
            }

            .digital-card {
                width: 100%;
                max-width: 400px;
                background: var(--card-bg);
                border-radius: 24px;
                overflow: hidden;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
                position: relative;
            }

            .card-header {
                background: linear-gradient(135deg, #0ea5e9, #6366f1);
                padding: 40px 20px 80px;
                text-align: center;
                position: relative;
            }

            .card-header::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 40px;
                background: var(--card-bg);
                clip-path: ellipse(50% 100% at 50% 100%);
            }

            .verification-badge {
                background: rgba(255, 255, 255, 0.2);
                backdrop-filter: blur(10px);
                color: white;
                padding: 6px 16px;
                border-radius: 100px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: 1px;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                margin-bottom: 15px;
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            .profile-section {
                margin-top: -60px;
                position: relative;
                z-index: 10;
                text-align: center;
                padding: 0 20px;
            }

            .avatar-container {
                width: 120px;
                height: 120px;
                border-radius: 24px;
                margin: 0 auto 15px;
                padding: 5px;
                background: white;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                transform: rotate(-3deg);
            }

            .avatar-container img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 20px;
                transform: rotate(3deg);
            }

            .participant-name {
                font-size: 22px;
                font-weight: 800;
                color: var(--text-main);
                margin-bottom: 4px;
            }

            .participant-nim {
                font-size: 14px;
                color: var(--text-muted);
                font-weight: 500;
                letter-spacing: 1px;
            }

            .details-section {
                padding: 30px 25px;
            }

            .detail-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 0;
                border-bottom: 1px solid #f1f5f9;
            }

            .detail-item:last-child {
                border-bottom: none;
            }

            .detail-label {
                font-size: 13px;
                color: var(--text-muted);
                font-weight: 500;
            }

            .detail-value {
                font-size: 14px;
                color: var(--text-main);
                font-weight: 600;
                text-align: right;
            }

            .seat-highlight {
                background: #f0f9ff;
                border: 1px solid #bae6fd;
                border-radius: 16px;
                padding: 20px;
                margin-top: 10px;
                text-align: center;
            }

            .seat-label {
                font-size: 11px;
                font-weight: 700;
                color: var(--primary-dark);
                text-transform: uppercase;
                letter-spacing: 2px;
                margin-bottom: 5px;
            }

            .seat-num {
                font-size: 32px;
                font-weight: 900;
                color: #0284c7;
            }

            .card-footer {
                padding: 20px;
                text-align: center;
                font-size: 11px;
                color: var(--text-muted);
                background: #f8fafc;
            }

            @media screen and (max-width: 380px) {
                body {
                    padding: 10px;
                }

                .digital-card {
                    border-radius: 0;
                    position: fixed;
                    top: 0;
                    left: 0;
                    height: 100%;
                    max-width: none;
                    overflow-y: auto;
                }
            }
        </style>
    </head>

    <body>
        <div class="digital-card">
            <div class="card-header">
                <div class="verification-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    VERIFIED IDENTITY
                </div>
                <h2
                    style="color: white; font-size: 14px; opacity: 0.9; font-weight: 400; letter-spacing: 1px; text-transform: uppercase;">
                    UPA BAHASA - UHO</h2>
            </div>

            <div class="profile-section">
                <div class="avatar-container">
                    @php
                        $photoFullPath = null;
                        if ($participant->photo_path) {
                            if (file_exists(storage_path('app/private/' . $participant->photo_path))) {
                                $photoFullPath = storage_path('app/private/' . $participant->photo_path);
                            } elseif (file_exists(storage_path('app/public/' . $participant->photo_path))) {
                                $photoFullPath = storage_path('app/public/' . $participant->photo_path);
                            }
                        }
                    @endphp
                    @if($photoFullPath)
                        <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($photoFullPath)) }}" alt="Profile">
                    @else
                        <div
                            style="width:100%;height:100%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;color:#64748b;font-weight:bold;border-radius:20px;">
                            NO PHOTO</div>
                    @endif
                </div>
                <h1 class="participant-name">{{ strtoupper($participant->name) }}</h1>
                <p class="participant-nim">{{ $participant->nim }}</p>
            </div>

            <div class="details-section">
                <div class="detail-item">
                    <span class="detail-label">Program Studi</span>
                    <span class="detail-value">{{ $participant->major }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tanggal Ujian</span>
                    <span class="detail-value">{{ $participant->schedule->date->format('d M Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Waktu</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($participant->schedule->time)->format('H:i') }}
                        WITA</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Ruangan</span>
                    <span class="detail-value">{{ $participant->schedule->room }}</span>
                </div>

                <div class="seat-highlight">
                    <div class="seat-label">Nomor Kursi</div>
                    <div class="seat-num">{{ $participant->effective_seat_number }}</div>
                    <div style="font-size: 10px; color: var(--primary-dark); font-weight: 600; margin-top: 5px;">
                        Kategori:
                        {{ \Str::contains(strtolower($participant->academic_level), ['s2', 's3']) ? 'TOEFL-EQUIVALENT' : 'TOEFL-LIKE' }}
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <p>Generated on {{ now()->format('d F Y') }}</p>
                <p style="margin-top: 5px; font-weight: 600;">© 2026 UPA Bahasa Universitas Halu Oleo</p>
            </div>
        </div>
    </body>

    </html>
@else
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kartu Ujian TOEFL</title>
        <style nonce="{{ $csp_nonce ?? '' }}">
            @page {
                size: 215.9mm 330.2mm;
                margin: 0;
            }

            body {
                font-family: 'Arial', 'Helvetica', sans-serif;
                margin: 0;
                padding: 0;
                line-height: 1.4;
                color: #333;
                background-color: white;
            }

            .page {
                width: 100%;
                box-sizing: border-box;
                padding: 3mm 5mm;
                position: relative;
            }

            .page-header {
                background: linear-gradient(135deg, #1e3a8a, #1d4ed8);
                color: white;
                text-align: center;
                padding: 5px;
                margin-bottom: 2px;
            }

            .page-header h1 {
                margin: 0;
                font-size: 18px;
                font-weight: bold;
                letter-spacing: 1px;
            }

            .page-header p {
                margin: 4px 0 0 0;
                font-size: 13px;
            }

            .card-container {
                background: white;
                border-radius: 8px;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
                border: 1px solid #e1e5e9;
                padding: 1mm;
                margin: 0.5mm;
                position: relative;
            }

            .row {
                margin-bottom: 2mm;
                width: 100%;
            }

            .logo-row {
                text-align: center;
                padding: 2px 0;
            }

            .logo-img {
                height: 35px;
                width: auto;
                object-fit: contain;
            }

            .info-photo-row {
                display: flex;
                gap: 3mm;
                align-items: flex-start;
            }

            .info-col {
                flex: 1;
                min-width: 0;
            }

            .info-photo {
                width: 110px;
                text-align: center;
                vertical-align: top;
            }

            .info-section {
                background: white;
                border-radius: 6px;
                padding: 5px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
                border: 1px solid #e2e8f0;
            }

            .info-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 10.5px;
            }

            .info-label {
                font-weight: bold;
                color: #1e293b;
                width: 130px;
                padding: 1px 5px 1px 0;
                vertical-align: top;
            }

            .info-colon {
                font-weight: bold;
                color: #1e40af;
                width: 20px;
                padding: 1px 2px 1px 0;
                vertical-align: top;
            }

            .info-value {
                color: #1e293b;
                font-weight: 500;
                padding: 1px 0 1px 0;
                vertical-align: top;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .photo-container {
                margin: 0;
                width: 100px;
                height: 130px;
                border: 1px solid #e2e8f0;
                border-radius: 4px;
                overflow: hidden;
                background: #f8fafc;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .seat-section {
                background-color: #f0f9ff !important;
                border: 1px solid #bae6fd !important;
                border-radius: 8px !important;
                padding: 15px !important;
                height: 120px !important;
                text-align: center !important;
                box-sizing: border-box !important;
            }

            .qr-section {
                background-color: #ffffff !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 8px !important;
                padding: 10px !important;
                height: 120px !important;
                text-align: center !important;
                box-sizing: border-box !important;
            }

            .qr-flex-container {
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                height: 85px !important;
            }

            .regulations-container {
                background: white;
                border-radius: 8px;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
                border: 1px solid #e1e5e9;
                padding: 1mm;
                margin: 1mm 0.5mm 0 0.5mm;
            }

            .instructions {
                font-weight: bold;
                color: #dc2626;
                font-size: 12px;
                text-transform: uppercase;
                text-align: center;
                padding: 6px;
                background: linear-gradient(135deg, #fef2f2, #fee2e2);
                border-radius: 6px;
                border: 1px solid #fecaca;
                margin-bottom: 5px;
            }

            .regulations-section {
                padding: 4px;
                background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
                border-radius: 6px;
                font-size: 8.5px;
                color: #1e293b;
                border: 1px solid #bae6fd;
                margin-bottom: 3px;
            }

            .regulations-section h4 {
                margin: 0 0 2px 0;
                color: #1e40af;
                font-size: 11px;
                font-weight: bold;
                padding-bottom: 2px;
                border-bottom: 1px solid #7dd3fc;
                text-transform: uppercase;
                text-align: center;
            }

            .regulations-section ol {
                margin: 2px 0;
                padding-left: 15px;
            }

            .regulations-section li {
                margin-bottom: 1px;
                line-height: 1.2;
            }

            .test-info-section {
                padding: 6px;
                background: white;
                border-radius: 6px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            }

            .footer {
                margin-top: 2px;
                text-align: center;
                color: #475569;
                font-size: 7px;
            }

            .footer p {
                margin: 2px 0;
            }

            .watermark-container {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1000;
                pointer-events: none;
                overflow: hidden;
                background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMjUwIDE1MCI+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZpbGw9IiMxZTNhOGEiIGZpbGwtb3BhY2l0eT0iMC4wNSIgZm9udC1mYW1pbHk9IkFyaWFsLCBIZWx2ZXRpY2EsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTYiIGZvbnQtd2VpZ2h0PSJib2xkIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiB0cmFuc2Zvcm09InJvdGF0ZSgtMjAsIDEyNSwgNzUpIj5VUEEgQmFoYXNhIFVITzwvdGV4dD48L3N2Zz4=");
                background-repeat: repeat;
            }
        </style>
    </head>

    <body>
        <div class="watermark-container"></div>
        <div class="page">
            <div class="page-header">
                @php
                    $isGraduateTitle = \Str::contains(strtolower($participant->academic_level), ['s2', 's3', 'magister', 'doktor', 'master', 'doctor']);
                    $cardTitle = $isGraduateTitle ? 'KARTU UJIAN TOEFL-EQUIVALENT' : 'KARTU UJIAN TOEFL-LIKE';
                @endphp
                <h1>{{ $cardTitle }}</h1>
                <p>Universitas Halu Oleo - UPA Bahasa</p>
            </div>

            <div class="card-container">
                <div class="row logo-row">
                    @if(file_exists(public_path('logo-uho-diktisaintek-text-biru.png')))
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('logo-uho-diktisaintek-text-biru.png'))) }}"
                            alt="Logo UHO dan Diktisaintek" class="logo-img" style="height: 55px;" />
                    @else
                        <div
                            style="height: 40px; text-align: center; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 12px; font-weight: bold;">
                            LOGO UHO</div>
                    @endif
                    <div style="text-align: center; font-weight: bold; font-size: 15px; margin-top: 2px; color: #1e40af;">
                        KARTU TES</div>
                </div>

                <div class="row info-photo-row">
                    <div class="info-col">
                        <div class="info-section">
                            <table class="info-table">
                                <tr>
                                    <td class="info-label" colspan="4"
                                        style="font-weight: bold; text-align: center; background: linear-gradient(135deg, #0ea5e9, #1e40af); color: white; padding: 8px; border-radius: 6px; letter-spacing: 1px;">
                                        INFORMASI PESERTA</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Nama Lengkap</td>
                                    <td class="info-colon">:</td>
                                    <td class="info-value">{{ $participant->name }}</td>
                                    <td class="info-photo" rowspan="7"
                                        style="vertical-align: top; text-align: center; padding: 0; padding-left: 10px;">
                                        <div class="photo-container">
                                            @php
                                                $photoFullPath = null;
                                                if ($participant->photo_path) {
                                                    if (file_exists(storage_path('app/private/' . $participant->photo_path))) {
                                                        $photoFullPath = storage_path('app/private/' . $participant->photo_path);
                                                    } elseif (file_exists(storage_path('app/public/' . $participant->photo_path))) {
                                                        $photoFullPath = storage_path('app/public/' . $participant->photo_path);
                                                    }
                                                }
                                            @endphp

                                            @if($photoFullPath)
                                                <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($photoFullPath)) }}"
                                                    alt="Photo Peserta" style="width: 100%; height: 100%; object-fit: cover;" />
                                            @else
                                                <div
                                                    style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; color: #64748b; font-size: 8px; text-align: center; padding: 5px; background: linear-gradient(135deg, #f1f5f9, #e2e8f0);">
                                                    PAS PHOTO<br>PESERTA</div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="info-label">Nomor Induk Mahasiswa</td>
                                    <td class="info-colon">:</td>
                                    <td class="info-value">{{ $participant->nim }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Program Studi</td>
                                    <td class="info-colon">:</td>
                                    <td class="info-value">
                                        {{ $participant->academic_level_display ?? ($participant->major) }} -
                                        {{ $participant->major }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Fakultas</td>
                                    <td class="info-colon">:</td>
                                    <td class="info-value">{{ $participant->faculty }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Jenis Kelamin</td>
                                    <td class="info-colon">:</td>
                                    <td class="info-value">{{ $participant->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="info-label">Tempat & Tanggal Lahir</td>
                                    <td class="info-colon">:</td>
                                    <td class="info-value">{{ $participant->birth_place }},
                                        {{ $participant->birth_date->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">No Telp Aktif WA</td>
                                    <td class="info-colon">:</td>
                                    <td class="info-value">{{ $participant->phone }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <table
                    style="width: 100%; border-collapse: separate; border-spacing: 5mm 0; margin-top: 10px; margin-bottom: 10px;">
                    <tr>
                        <td style="width: 50%; vertical-align: top; padding: 0;">
                            <div class="seat-section">
                                <div style="color: #0369a1; font-weight: bold; font-size: 13px; margin-bottom: 8px;">NOMOR
                                    KURSI ANDA</div>
                                <div style="font-size: 32px; font-weight: 800; color: #0284c7; margin: 5px 0;">
                                    {{ $participant->effective_seat_number }}</div>
                                <div style="font-size: 12px; color: #0369a1; font-weight: bold;">Kategori:
                                    {{ $isGraduateTitle ? 'TOEFL-EQUIVALENT' : 'TOEFL-LIKE' }}</div>
                            </div>
                        </td>
                        <td style="width: 50%; vertical-align: top; padding: 0;">
                            <div class="qr-section">
                                <div
                                    style="color: #64748b; font-weight: bold; font-size: 11px; margin-bottom: 5px; text-transform: uppercase;">
                                    KODE VERIFIKASI</div>
                                <div class="qr-flex-container">
                                    @php
                                        $qrcode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(150)->generate($verificationUrl));
                                    @endphp
                                    <img src="data:image/svg+xml;base64,{{ $qrcode }}" alt="QR Code"
                                        style="width: 85px; height: 85px; display: inline-block;">
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="regulations-container">
                <div class="instructions">PESERTA WAJIB MEMBAWA PRINT OUT KARTU TES INI</div>
                <div class="regulations-section">
                    <h4>TATA TERTIB MENGIKUTI TES TOEFL UPA-BAHASA UNIVERSITA HALU OLEO</h4>
                    <ol>
                        <li>Tes TOEFL dilaksanakan tepat waktu Peserta harus sudah berada di depan ruangan 30 menit sebelum
                            Jam/Waktu Tes.</li>
                        <li>Peserta harus membawa kartu identitas diri (KTP/SIM/kartu mahasiswa/kartu perpustakaan). Jika
                            tidak dapat menunjukkan kartu identitas diri, peserta tidak diperkenankan mengikuti tes.</li>
                        <li>Peserta wajib membawa SLIP PEMBAYARAN TES TOEFL (BERWARNA PINK) pada saat tes.</li>
                        <li>Peserta yang terlambat dengan alasan apapun tidak diperkenankan mengikuti tes.</li>
                        <li>Peserta yang tidak hadir pada saat tes harus mendaftar kembali jika ingin mengikuti tes TOEFL.
                        </li>
                        <li>Sebelum tes dimulai peserta harus menonaktifkan semua alat komunikasi (handphone, dsb).</li>
                        <li>Peserta yang mengaktifkan alat komunikasi pada saat tes berlangsung akan dikeluarkan dari
                            ruangan tes.</li>
                        <li>Selama tes berlangsung peserta tidak diperbolehkan berkomunikasi dalam bentuk apapun dengan
                            sesama peserta tes.</li>
                        <li>Selama tes berlangsung peserta tidak boleh meninggalkan ruangan tes tanpa ijin dari pengawas
                            tes.</li>
                        <li>Peserta tes tidak boleh membawa catatan/buku pada saat tes berlangsung.</li>
                        <li>Peserta tes tidak boleh menulis/mengotori atau membawa buku tes keluar dari ruangan tes.</li>
                        <li>Peserta tes harus mengerjakan tes sesuai dengan section yang sedang berlangsung.</li>
                        <li>Setelah tes selesai, lembar jawaban diselipkan di dalam buku tes dan diletakkan di meja/kursi
                            masing-masing peserta.</li>
                        <li>Setelah tes berakhir, peserta tes meninggalkan ruangan tes secara bersamaan dengan tertib.</li>
                        <li>Tidak diperkenankan meninggalkan ruangan tes sebelum waktu tes berakhir.</li>
                    </ol>
                </div>

                <div class="test-info-section">
                    <table class="info-table">
                        <tr>
                            <td class="info-label" colspan="4"
                                style="font-weight: bold; text-align: center; background: linear-gradient(135deg, #0ea5e9, #1e40af); color: white; padding: 8px; border-radius: 6px;">
                                INFORMASI TES</td>
                        </tr>
                        <tr>
                            <td class="info-label">Tanggal Tes</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $participant->schedule->date->format('d F Y') }}</td>
                            <td class="info-photo" rowspan="5" style="vertical-align: top; text-align: center; padding: 0;">
                                <div
                                    style="width: 100px; height: 75px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border: 1px solid #bae6fd; border-radius: 6px;">
                                    <div style="font-size: 14px; font-weight: bold; color: #0ea5e9; text-align: center;">
                                        PENGAWAS</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">Jam/Waktu Tes</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">
                                @php
                                    $startTime = \Carbon\Carbon::parse($participant->schedule->time);
                                    $endTime = $startTime->copy()->addHours(3);
                                @endphp
                                {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }} WITA
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">Ruangan</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $participant->schedule->room }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Kapasitas</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">
                                {{ $participant->schedule->used_capacity }}/{{ $participant->schedule->capacity }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Status Pendaftaran</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">
                                <span
                                    style="color: {{ $participant->status === 'confirmed' ? '#16a34a' : ($participant->status === 'pending' ? '#ca8a04' : '#dc2626') }}; font-weight: bold;">
                                    {{ $participant->status === 'confirmed' ? 'Terkonfirmasi' : ($participant->status === 'pending' ? 'Tertunda' : 'Dibatalkan') }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="footer">
                    <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
                    <p>Universitas Halu Oleo - UPA Bahasa | Kendari, Sulawesi Tenggara</p>
                </div>
            </div>
        </div>
    </body>

    </html>
@endif