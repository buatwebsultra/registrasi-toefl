<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
    <style>
        @page {
            size: 216mm 135mm;
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 4mm 5mm 3mm 5mm;
            box-sizing: border-box;
            background: white;
            font-size: 8.5pt;
            line-height: 1.0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0.5mm;
        }
        .header-logo {
            width: 260px;
            vertical-align: middle;
        }
        .header-text {
            text-align: center;
            vertical-align: middle;
        }
        .header-title-en {
            font-weight: bold;
            font-size: 11pt;
            text-transform: uppercase;
        }
        .header-univ {
            font-weight: bold;
            font-size: 12pt;
            text-transform: uppercase;
            margin: 1px 0;
        }
        .header-unit {
            font-weight: bold;
            font-size: 11pt;
            font-style: italic;
        }
        .header-address {
            font-size: 8pt;
            margin-top: 1px;
        }
        .thick-line {
            border-bottom: 2px solid black;
            margin-bottom: 1mm;
        }

        /* Main Grid */
        .main-container {
            width: 100%;
        }
        .grid-table {
            width: 100%;
            border-collapse: collapse;
        }
        .grid-col-left { width: 28%; vertical-align: top; }
        .grid-col-center { width: 42%; vertical-align: top; }
        .grid-col-right { width: 30%; vertical-align: top; text-align: right; position: relative; }

        .serial-number {
            font-weight: bold;
            margin-bottom: 2px;
            text-align: left;
            font-size: 9pt;
        }
        .certificate-title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 1px 0;
            vertical-align: top;
            font-size: 8.5pt;
        }
        .info-label { width: 40%; }
        .info-value { font-weight: bold; text-transform: uppercase; }

        .participant-photo {
            width: 20mm;
            height: 30mm;
            border: 2px dashed #000;
            object-fit: cover;
            position: absolute;
            top: 0;
            right: 0;
            z-index: 5;
        }

        /* Verification Row */
        .verification-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
        }
        .qr-section {
            width: 28%;
            vertical-align: top;
            text-align: center;
            font-size: 7pt;
        }
        .score-section {
            width: 42%;
            vertical-align: top;
            padding: 0 10px;
        }
        .score-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }
        .score-table td {
            border: 1px solid #000;
            padding: 3px 6px;
        }
        .score-value {
            font-weight: bold;
            text-align: center;
            width: 50px;
        }
        .total-row {
            font-weight: bold;
            background-color: #eee;
        }

        .date-sign-section {
            width: 30%;
            vertical-align: top;
            text-align: center;
        }
        .date-box {
            border: 1px solid #000;
            padding: 5px;
            margin-bottom: 5px;
            width: 100%;
            box-sizing: border-box;
            font-size: 9pt;
        }
        .signature-block {
            margin-top: 5px;
        }
        .signature-title {
            margin-bottom: 30px;
            font-size: 9.5pt;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            font-size: 10pt;
            white-space: nowrap;
        }
        .signature-nip {
            font-size: 9pt;
        }

        .footer-note {
            font-size: 7.5pt;
            margin-top: 8mm;
            text-align: left;
            line-height: 1.2;
        }

        /* Watermark Styles */
        .watermark-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1000;
            pointer-events: none;
        }
        .watermark-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }
        .watermark-table td {
            width: 20%;
            height: 20%;
            text-align: center;
            vertical-align: middle;
            padding: 5px;
        }
        .watermark-table img {
            width: 100%;
            opacity: 0.05; /* Reduced from 0.08 */
            display: block;
            margin: 0 auto;
            transform: rotate(60deg);
        }
    </style>
</head>
<body>
    <!-- Watermark: 25 Logos (5x5 Grid) -->
    <div class="watermark-container">
        <table class="watermark-table">
            @for($i = 0; $i < 5; $i++)
            <tr>
                @for($j = 0; $j < 5; $j++)
                <td>
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('logo-uho-dan-diktisaintek-768x143.png'))) }}" alt="Watermark Logo">
                </td>
                @endfor
            </tr>
            @endfor
        </table>
    </div>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 260px;">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('logo-uho-dan-diktisaintek-768x143.png'))) }}" alt="Logo UHO" class="header-logo">
            </td>
            <td class="header-text">
                <div class="header-title-en">MINISTRY OF HIGHER EDUCATION, SCIENCE AND TECHNOLOGY</div>
                <div class="header-univ" style="font-size: 13pt;">HALU OLEO UNIVERSITY</div>
                <div class="header-unit">UPA BAHASA (Language Center)</div>
                <div class="header-address">
                    Kampus Hijau Bumi Tridharma, Kendari, 93232<br>
                    Phone/Fax : (0401) 3195241, Email : uptbahasa@uho.ac.id
                </div>
            </td>
        </tr>
    </table>
    <div class="thick-line"></div>

    <div class="main-container">
        <!-- Top Row: Labels, Values, Photo -->
        <table class="grid-table">
            <tr>
                <td class="grid-col-left">
                    <div class="serial-number">Serial Number : {{ $participant->id }}{{ $participant->nim }}</div>
                    <table class="info-table">
                        <tr><td class="info-label">Name</td></tr>
                        <tr><td class="info-label">Date of Birth</td></tr>
                        <tr><td class="info-label">Faculty</td></tr>
                        <tr><td class="info-label">Study Program/Dept.</td></tr>
                        <tr><td class="info-label">NIM/Participant's ID</td></tr>
                        <tr><td class="info-label">Gender</td></tr>
                    </table>
                </td>
                <td class="grid-col-center">
                    <div class="certificate-title">TOEFL-LIKE SCORE</div>
                    <table class="info-table">
                        <tr><td class="info-value">{{ $participant->name }}</td></tr>
                        <tr><td class="info-value">{{ $participant->birth_date ? $participant->birth_date->format('d F Y') : '-' }}</td></tr>
                        <tr><td class="info-value">{{ is_object($participant->faculty) ? $participant->faculty->name : ($participant->faculty ?? '-') }}</td></tr>
                        <tr><td class="info-value">
                            @if($participant->academic_level_display)
                                {{ $participant->academic_level_display }} 
                            @endif
                            {{ is_object($participant->studyProgram) ? $participant->studyProgram->name : ($participant->major ?? '-') }}
                        </td></tr>
                        <tr><td class="info-value">{{ $participant->nim }}</td></tr>
                        <tr><td class="info-value">{{ in_array(strtolower($participant->gender), ['male', 'l']) ? 'MALE' : 'FEMALE' }}</td></tr>
                    </table>
                </td>
                <td class="grid-col-right">
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
                        <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($photoFullPath)) }}" class="participant-photo">
                    @else
                        <div class="participant-photo" style="display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                            <span style="font-size: 8pt; color: #666; text-align: center;">FOTO 3x4</span>
                        </div>
                    @endif
                    <!-- Spacer to maintain layout -->
                    <div style="width: 20mm; height: 30mm; display: inline-block; visibility: hidden;"></div>
                </td>
            </tr>
        </table>

        <!-- Bottom Row: QR, Scores, Date/Sign -->
        <table class="verification-table">
            <tr>
                <td class="qr-section">
                    @php
                        $qrcode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->generate($verificationUrl));
                    @endphp
                    <img src="data:image/svg+xml;base64,{{ $qrcode }}" alt="QR Code" style="width: 85px; height: 85px;"><br>
                    <div style="margin-top: 5px; line-height: 1.2;">
                        Pindai kode untuk memeriksa<br>
                        keaslian sertifikat ini
                    </div>
                </td>
                <td class="score-section">
                    <table class="score-table">
                        <tr>
                            <td>Listening Comprehension</td>
                            <td class="score-value">{{ $participant->listening_score_pbt ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Structure and Written Expression</td>
                            <td class="score-value">{{ $participant->structure_score_pbt ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Reading Comprehension</td>
                            <td class="score-value">{{ $participant->reading_score_pbt ?? '-' }}</td>
                        </tr>
                        <tr class="total-row">
                            <td>TOTAL SCORE</td>
                            <td class="score-value">{{ $participant->test_score ? (int)round($participant->test_score) : '-' }}</td>
                        </tr>
                    </table>
                </td>
                <td class="date-sign-section">
                    <table class="date-box">
                        <tr>
                            <td style="font-weight: bold;">Test Date</td>
                            <td style="font-weight: bold;">Valid Until</td>
                        </tr>
                        <tr>
                            <td>{{ $participant->schedule ? $participant->schedule->date->format('d F Y') : '-' }}</td>
                            <td>{{ $participant->schedule ? $participant->schedule->date->copy()->addYears(2)->format('d F Y') : '-' }}</td>
                        </tr>
                    </table>

                    <div class="signature-block" style="position: relative;">
                        <div class="signature-title">Head of UPA Bahasa UHO,</div>
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('signature.png'))) }}" alt="Signature" style="position: absolute; width: 120px; height: auto; left: 50%; transform: translateX(-50%); top: 30px; z-index: 10;">
                        <div class="signature-name" style="margin-top: 75px;">{{ $participant->schedule->signature_name ?? 'Ir. Uniadi Mangidi, S.T., M.T., M.Eng.Sc' }}</div>
                        <div class="signature-nip">{{ $participant->schedule->signature_nip ?? '19750614 200212 1 002' }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        * Hanya digunakan sebagai syarat untuk mengikuti ujian {{ $participant->exam_type }} bagi mahasiswa {{ $participant->academic_level_full }} pada Universitas Halu Oleo.
    </div>
</body>
</html>