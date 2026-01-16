<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOEFL Test Card</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        .card {
            width: 100%;
            height: 100%;
            background: white;
            padding: 20px;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            border: 1px solid #e2e8f0;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, #1a365d, #3498db, #1a365d);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
            position: relative;
            background: linear-gradient(135deg, #1a365d, #2c5282);
            color: white;
            padding: 20px 20px 15px;
            border-radius: 8px 8px 0 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header h1 {
            color: white;
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        .header p {
            margin: 5px 0 0 0;
            color: rgba(255,255,255,0.9);
            font-size: 14px;
            font-weight: 500;
        }
        .university-logo {
            text-align: center;
            margin: -40px 0 15px;
        }
        .university-logo img {
            height: 60px;
            width: auto;
            object-fit: contain;
        }
        .main-content {
            margin-bottom: 15px;
        }
        .photo-container {
            width: 100px;
            height: 130px;
            border: 3px solid #fff;
            border-radius: 8px;
            overflow: hidden;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            align-self: flex-start; /* Align photo to top */
            position: relative;
        }
        .photo-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #1a365d, #2c5282, #1a365d, #2c5282);
            border-radius: 10px;
            z-index: -1;
            background-size: 400% 400%;
        }
        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .participant-info {
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e2e8f0;
            padding: 4px 0;
        }
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .info-label {
            flex: 0 0 140px;
            font-weight: 600;
            color: #2d3748;
            font-size: 12px;
            position: relative;
        }
        .info-label::after {
            content: ':';
            position: absolute;
            right: -6px;
        }
        .info-value {
            flex: 1;
            color: #2d3748;
            font-weight: 500;
            font-size: 12px;
        }
        .section-header {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin: 20px 0 12px;
            padding-bottom: 6px;
            font-size: 14px;
            text-align: center;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .section-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50%;
            height: 3px;
            background: linear-gradient(to right, transparent, #4299e1, transparent);
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            width: 140px;
        }
        .qr-code p {
            margin: 0 0 8px 0;
            font-weight: 600;
            color: #495057;
            font-size: 12px;
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
            font-size: 10px;
            color: #adb5bd;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 10px;
            line-height: 1.4;
            background: linear-gradient(to bottom, #ffffff, #f8fafc);
        }
        .footer p {
            margin: 4px 0;
        }
        .watermark-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg) scale(1.5);
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
        }
        .watermark-logo img {
            width: 150px;
            height: auto;
            filter: grayscale(100%);
        }
        .watermark-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 18px;
            font-weight: bold;
            color: rgba(0, 123, 255, 0.05);
            white-space: nowrap;
            z-index: 0;
            pointer-events: none;
        }
        .seat-info {
            background: linear-gradient(135deg, #42a5f5, #1e88e5, #0d47a1);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 8px 20px rgba(30, 136, 229, 0.3);
            border: 2px solid rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }
        .seat-number {
            font-size: 36px;
            font-weight: bold;
            margin: 8px 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            background: linear-gradient(to right, #ffffff, #e3f2fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-family: 'Arial Black', sans-serif;
        }
        .seat-label {
            font-size: 16px;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        .qr-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="watermark-logo">
            @if(file_exists(public_path('logo-uho-dan-diktisaintek-768x143.png')))
                <img src="file://{{ public_path('logo-uho-dan-diktisaintek-768x143.png') }}" alt="Logo UHO" />
            @else
                <div style="width: 150px; height: auto;">Logo</div>
            @endif
        </div>

        <div class="watermark-text">UPA Bahasa Universitas Halu Oleo</div>

        <div class="university-logo">
            @if(file_exists(public_path('logo-uho-dan-diktisaintek-768x143.png')))
                <!-- Use asset() for web preview, but file:// for PDF generation -->
                @php
                    $isPdfRoute = str_contains(request()->route()->getName() ?? '', 'card.download');
                @endphp
                @if($isPdfRoute)
                    <img src="file://{{ public_path('logo-uho-dan-diktisaintek-768x143.png') }}" alt="Logo UHO dan Diktisaintek" />
                @else
                    <img src="{{ asset('logo-uho-dan-diktisaintek-768x143.png') }}" alt="Logo UHO dan Diktisaintek" />
                @endif
            @else
                <div style="height: 60px; width: auto;">Logo</div>
            @endif
        </div>

        <div class="header">
            <h1>TOEFL TEST CARD</h1>
            <p>Official Registration Card</p>
        </div>

        <div class="main-content">
            <div style="display: flex; width: 100%; gap: 20px;">
                <!-- Photo Section -->
                <div style="flex: 0 0 120px; text-align: center;">
                    <div class="photo-container">
                        @if($participant->photo_path && file_exists(storage_path('app/public/' . $participant->photo_path)))
                            <!-- Use asset() for web preview, but file:// for PDF generation -->
                            @php
                                $isPdfRoute = str_contains(request()->route()->getName() ?? '', 'card.download');
                            @endphp
                            @if($isPdfRoute)
                                <img src="file://{{ storage_path('app/public/' . $participant->photo_path) }}" alt="Participant Photo" style="object-fit:cover;">
                            @else
                                <img src="{{ asset('storage/' . $participant->photo_path) }}" alt="Participant Photo" style="object-fit:cover;">
                            @endif
                        @else
                            <div style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; color: #ccc; font-size: 10px;">No Photo</div>
                        @endif
                    </div>
                </div>

                <!-- Information Section -->
                <div style="flex: 1; min-width: 0; padding-left: 10px;">
                    <div class="participant-info">
                        <div class="info-row">
                            <div class="info-label">Full Name:</div>
                            <div class="info-value">{{ $participant->name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">NIM:</div>
                            <div class="info-value">{{ $participant->nim }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Study Program:</div>
                            <div class="info-value">{{ $participant->major }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Faculty:</div>
                            <div class="info-value">{{ $participant->faculty }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Academic Level:</div>
                            <div class="info-value">
                                @if($participant->studyProgram)
                                    {{ $participant->academic_level_display }}
                                @else
                                    <span class="text-muted">Unknown</span>
                                @endif
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Gender:</div>
                            <div class="info-value">{{ $participant->gender == 'male' ? 'Male' : 'Female' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Place & Date of Birth:</div>
                            <div class="info-value">{{ $participant->birth_place }}, {{ $participant->birth_date->format('d F Y') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Phone Number:</div>
                            <div class="info-value">{{ $participant->phone }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="seat-info">
            <div class="seat-label">Your Seat Number</div>
            <div class="seat-number">{{ $participant->seat_number }}</div>
            <div class="seat-label">Category: {{ $participant->test_category }}</div>
        </div>

        <div class="section-header">Test Details</div>
        <div class="participant-info">
            <div class="info-row">
                <div class="info-label">Test Date:</div>
                <div class="info-value">{{ $participant->schedule->date->format('d F Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Room:</div>
                <div class="info-value">{{ $participant->schedule->room }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Capacity:</div>
                <div class="info-value">{{ $participant->schedule->used_capacity }} of {{ $participant->schedule->capacity }}</div>
            </div>
        </div>

        <div class="qr-section">
            <div class="qr-code">
                <p><strong>QR Code for Verification</strong></p>
                @if(class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode') && isset($verificationUrl))
                    {!! QrCode::size(120)->generate($verificationUrl) !!}
                @else
                    <div class="qr-placeholder">
                        QR CODE
                    </div>
                @endif
            </div>
        </div>

        <div class="footer">
            <p>This card must be presented at the test venue</p>
            <p>Valid only for the specified test date and room</p>
            <p>Please arrive 30 minutes before the test begins</p>
        </div>
    </div>
</body>
</html>