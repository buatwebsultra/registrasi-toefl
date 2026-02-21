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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style nonce="{{ $csp_nonce ?? '' }}">
        :root {
            --uho-blue: #1e3a8a;
            --uho-blue-dark: #1e40af;
            --uho-accent: #3b82f6;
            --success-bg: #f0fdf4;
            --success-text: #166534;
            --danger-bg: #fef2f2;
            --danger-text: #991b1b;
            --premium-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --card-border: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 20px 20px;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .verification-card {
            max-width: 550px;
            width: 100%;
            background: white;
            border-radius: 2rem;
            box-shadow: var(--premium-shadow);
            border: 1px solid var(--card-border);
            overflow: hidden;
            position: relative;
        }

        .top-accent {
            height: 10px;
            background: linear-gradient(to right, var(--uho-blue), var(--uho-accent));
        }

        .card-header-uho {
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .logo-main {
            max-height: 55px;
            margin-bottom: 1rem;
        }

        .header-labels {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin: 0;
        }

        .header-unit {
            font-size: 1rem;
            font-weight: 800;
            color: var(--uho-blue);
            margin-bottom: 0.25rem;
        }

        .card-body-content {
            padding: 2rem;
        }

        .status-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            font-weight: 800;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
        }

        .badge-passed {
            background-color: var(--success-bg);
            color: var(--success-text);
            border-color: #bbf7d0;
        }

        .badge-failed {
            background-color: var(--danger-bg);
            color: var(--danger-text);
            border-color: #fecaca;
        }

        .participant-name {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--uho-blue-dark);
            margin-bottom: 0.25rem;
            line-height: 1.2;
            word-break: break-word;
        }

        .participant-nim {
            font-size: 1rem;
            color: #64748b;
            font-weight: 600;
            letter-spacing: 0.025em;
        }

        .score-display {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: center;
            position: relative;
        }

        .score-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.5rem;
        }

        .score-value {
            font-size: 3.5rem;
            font-weight: 900;
            color: var(--uho-blue);
            line-height: 1;
            margin: 0;
        }

        .info-grid {
            margin-bottom: 2rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.85rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
        }

        .info-value {
            font-size: 0.875rem;
            font-weight: 800;
            color: #1e293b;
            text-align: right;
            max-width: 60%;
        }

        .text-passed {
            color: var(--success-text);
        }

        .text-failed {
            color: var(--danger-text);
        }

        .footer-info {
            background: #f8fafc;
            padding: 1.5rem 2rem;
            text-align: center;
            border-top: 1px solid #f1f5f9;
        }

        .footer-text {
            font-size: 0.8rem;
            color: #94a3b8;
            line-height: 1.5;
            margin: 0;
        }

        .sipena-branding {
            font-weight: 700;
            color: #64748b;
            margin-bottom: 0.25rem;
            display: block;
        }

        @media (max-width: 576px) {
            .verification-card {
                border-radius: 1.5rem;
            }

            .card-body-content {
                padding: 1.5rem;
            }

            .participant-name {
                font-size: 1.5rem;
            }

            .score-value {
                font-size: 2.75rem;
            }
        }
    </style>
</head>

<body>

    <div class="verification-card">
        <div class="top-accent"></div>

        <header class="card-header-uho">
            <img src="{{ asset('logo-uho-dan-diktisaintek-768x143.png') }}" alt="Logo UHO" class="logo-main">
            <h2 class="header-unit">UPA BAHASA (LANGUAGE CENTER)</h2>
            <p class="header-labels">HALU OLEO UNIVERSITY</p>
        </header>

        <main class="card-body-content">
            <div class="status-header">
                <div class="verified-badge {{ $participant->passed ? 'badge-passed' : 'badge-failed' }}">
                    <i class="fas {{ $participant->passed ? 'fa-check-double' : 'fa-exclamation-triangle' }}"></i>
                    {{ $participant->passed ? 'Sertifikat Terverifikasi' : 'Hasil Tes Terverifikasi' }}
                </div>

                <h1 class="participant-name">{{ $participant->name }}</h1>
                <p class="participant-nim">NIM: {{ $participant->nim }}</p>
            </div>

            <div class="score-display">
                <div class="score-label">Total TOEFL Score</div>
                <h3 class="score-value">{{ number_format($participant->test_score, 0, '', '') }}</h3>
            </div>

            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">Program Studi</span>
                    <span class="info-value">
                        {{ $participant->studyProgram ? $participant->studyProgram->name : ($participant->major ?? '-') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Ujian</span>
                    <span class="info-value">{{ $participant->schedule->date->format('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status Kelulusan</span>
                    <span class="info-value {{ $participant->passed ? 'text-passed' : 'text-failed' }}">
                        {{ $participant->passed ? 'LULUS (PASS)' : 'TIDAK LULUS (FAILED)' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Masa Berlaku</span>
                    <span class="info-value">
                        Hingga {{ $participant->schedule->date->copy()->addYears(2)->format('d F Y') }}
                    </span>
                </div>
            </div>
        </main>

        <footer class="footer-info">
            <span class="sipena-branding">SIPENA UHO</span>
            <p class="footer-text">
                Dokumen ini merupakan data digital resmi dari Unit Pelaksana Akademik (UPA) Bahasa Universitas Halu
                Oleo.
            </p>
        </footer>
    </div>

</body>

</html>