<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Ujian TOEFL</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
        }
        .card {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
            border: 1px solid #e0e6ed;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, #007bff, #00c9a7);
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            position: relative;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 8px 0 0 0;
            color: #6c757d;
            font-size: 16px;
            font-weight: 500;
        }
        .university-logo {
            text-align: center;
            margin: -50px 0 20px;
        }
        .university-logo img {
            height: 80px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }
        .content-wrapper {
            display: flex;
            gap: 25px;
            margin-bottom: 25px;
        }
        .photo-section {
            flex: 0 0 140px;
            text-align: center;
        }
        .photo-container {
            width: 120px;
            height: 150px;
            border: 2px solid #e0e6ed;
            border-radius: 8px;
            overflow: hidden;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .info-section {
            flex: 1;
        }
        .participant-info {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .info-label {
            flex: 0 0 130px;
            font-weight: 600;
            color: #495057;
        }
        .info-value {
            flex: 1;
            color: #6c757d;
            font-weight: 500;
        }
        .section-header {
            font-size: 18px;
            font-weight: 600;
            color: #007bff;
            margin: 25px 0 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e0e6ed;
        }
        .qr-code {
            text-align: center;
            margin: 25px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }
        .qr-code p {
            margin: 0 0 10px 0;
            font-weight: 600;
            color: #495057;
        }
        .qr-placeholder {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 12px;
            color: #adb5bd;
        }
        .footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 13px;
            line-height: 1.6;
        }
        .footer p {
            margin: 5px 0;
        }
        .watermark-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 24px;
            font-weight: bold;
            color: rgba(0, 123, 255, 0.08);
            white-space: nowrap;
            z-index: 0;
            pointer-events: none;
        }
        .watermark-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg) scale(1.5);
            opacity: 0.08;
            z-index: 0;
            pointer-events: none;
        }
        .watermark-logo img {
            width: 200px;
            height: auto;
            filter: grayscale(100%);
        }
        .seat-info {
            background: linear-gradient(135deg, #007bff, #00c9a7);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
        }
        .seat-number {
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0;
        }
        .seat-label {
            font-size: 14px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="watermark-logo">
            <img src="{{ asset('logo-uho-dan-diktisaintek-768x143.png') }}" alt="Logo UHO" />
        </div>

        <div class="university-logo">
            <img src="{{ asset('logo-uho-dan-diktisaintek-768x143.png') }}" alt="Logo UHO dan Diktisaintek" />
        </div>

        <div class="watermark-text">UPA Bahasa Universitas Halu Oleo</div>

        <div class="header">
            <h1>KARTU UJIAN TOEFL</h1>
            <p>Kartu Pendaftaran Resmi</p>
        </div>

        <div class="content-wrapper">
            <div class="photo-section">
                <div class="photo-container">
                    <img src="{{ asset('storage/' . $participant->photo_path) }}" alt="Pas Photo Peserta" style="object-fit:cover;">
                </div>
            </div>

            <div class="info-section">
                <div class="participant-info">
                    <div class="info-row">
                        <div class="info-label">Nama Lengkap:</div>
                        <div class="info-value">{{ $participant->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">NIM:</div>
                        <div class="info-value">{{ $participant->nim }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Program Studi:</div>
                        <div class="info-value">{{ $participant->major }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Fakultas:</div>
                        <div class="info-value">{{ $participant->faculty }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="seat-info">
            <div class="seat-label">Nomor Kursi Anda</div>
            <div class="seat-number">{{ $participant->seat_number }}</div>
            <div class="seat-label">Kategori: {{ $participant->test_category }}</div>
        </div>

        <div class="section-header">Detail Ujian</div>
        <div class="participant-info">
            <div class="info-row">
                <div class="info-label">Tanggal Ujian:</div>
                <div class="info-value">{{ $participant->schedule->date->format('d F Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Ruangan:</div>
                <div class="info-value">{{ $participant->schedule->room }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kapasitas:</div>
                <div class="info-value">{{ $participant->schedule->used_capacity }} dari {{ $participant->schedule->capacity }}</div>
            </div>
        </div>

        <div class="qr-code">
            <p><strong>Kode QR untuk Verifikasi</strong></p>
            <div class="qr-placeholder">
                @if(class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode'))
                    @php
                        $verificationUrl = request()->getSchemeAndHttpHost().'/participant/card/'.$participant->id;
                    @endphp
                    {!! QrCode::size(150)->generate($verificationUrl) !!}
                @else
                    QR CODE
                @endif
            </div>
        </div>

        <div class="footer">
            <p>Kartu ini harus dibawa saat ujian berlangsung</p>
            <p>Berlaku hanya untuk tanggal dan ruangan ujian yang tercantum</p>
            <p>Harap datang 30 menit sebelum ujian dimulai</p>
        </div>
    </div>
</body>
</html>