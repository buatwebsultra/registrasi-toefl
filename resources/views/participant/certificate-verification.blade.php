<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Sertifikat TOEFL - UPA Bahasa UHO</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --uho-blue: #1e3a8a;
            --uho-blue-dark: #1e40af;
            --uho-accent: #3b82f6;
            --success-bg: #f0fdf4;
            --success-text: #166534;
            --premium-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .verification-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 1.5rem;
            box-shadow: var(--premium-shadow);
            overflow: hidden;
            position: relative;
        }

        .header-accent {
            height: 8px;
            background: linear-gradient(to right, var(--uho-blue), var(--uho-accent));
        }

        .content-padding {
            padding: 2.5rem;
        }

        .verified-badge {
            background: var(--success-bg);
            color: var(--success-text);
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.125rem;
            margin-bottom: 2rem;
            border: 1px solid #bbf7d0;
        }

        .participant-name {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--uho-blue-dark);
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .participant-id {
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
            background: #f1f5f9;
            padding: 1.5rem;
            border-radius: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-label {
            color: #64748b;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-value {
            font-weight: 700;
            color: #1e293b;
        }

        .score-circle {
            width: 100px;
            height: 100px;
            background: white;
            border: 4px solid var(--uho-accent);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);
        }

        .score-number {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--uho-blue-dark);
            line-height: 1;
        }

        .score-label {
            font-size: 0.625rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        .footer-note {
            font-size: 0.825rem;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 1.5rem;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo-img {
            max-height: 40px;
        }

        @media (max-width: 480px) {
            .content-padding {
                padding: 1.5rem;
            }
            .participant-name {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="verification-container">
    <div class="header-accent"></div>
    <div class="content-padding">
        <div class="logo-container">
            <img src="{{ asset('logo-uho-dan-diktisaintek-768x143nobg.png') }}" alt="Logo UHO" class="logo-img">
        </div>

        <div class="text-center">
            <div class="verified-badge">
                <i class="fas fa-check-circle fs-4"></i>
                SERTIFIKAT TERVERIFIKASI
            </div>

            <h1 class="participant-name">{{ $participant->name }}</h1>
            <p class="participant-id">NIM: {{ $participant->nim }}</p>

            <div class="score-circle">
                <div class="score-number">{{ number_format($participant->test_score, 0, '', '') }}</div>
                <div class="score-label">Total Skor</div>
            </div>

            <div class="info-grid text-start">
                <div class="info-item">
                    <span class="info-label">Program Studi</span>
                    <span class="info-value text-end ms-3">{{ $participant->studyProgram ? $participant->studyProgram->name : $participant->major }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Tes</span>
                    <span class="info-value">{{ $participant->schedule->date->format('d F Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status Kelulusan</span>
                    <span class="info-value text-success">LULUS (PASS)</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Berlaku Hingga</span>
                    <span class="info-value">{{ $participant->schedule->date->copy()->addYears(2)->format('d F Y') }}</span>
                </div>
            </div>

            <div class="footer-note">
                Sistem Informasi Pelayanan Bahasa (SIPENA) UHO.<br>
                Dokumen ini adalah data digital resmi dari UPA Bahasa Universitas Halu Oleo.
            </div>
        </div>
    </div>
</div>

</body>
</html>
