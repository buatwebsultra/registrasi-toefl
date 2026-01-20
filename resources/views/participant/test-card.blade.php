<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Ujian TOEFL</title>
    <style>
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

        /* PAGE 1 - Identity Page */
        .page-identity {
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
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            border: 1px solid #e1e5e9;
            padding: 1mm;
            margin: 0.5mm;
            position: relative;
        }

        /* Page 1 rows */
        .row {
            margin-bottom: 2mm;
            width: 100%;
        }

        /* Row 1 - Logo */
        .logo-row {
            text-align: center;
            padding: 2px 0;
        }

        .title-row {
            text-align: center;
            padding: 8px 0;
            margin-bottom: 5px;
        }

        .logo-img {
            height: 35px;
            width: auto;
            object-fit: contain;
        }

        /* Row 2 - Info and Photo */
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
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }

        .info-title {
            background: linear-gradient(135deg, #0ea5e9, #1e40af);
            color: white;
            padding: 6px;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            margin-bottom: 6px;
            border-radius: 6px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }

        .info-table tr {
            vertical-align: top;
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

        .photo-container-old {
            width: 100px;
            height: 130px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            color: #64748b;
            font-size: 9px;
            text-align: center;
            padding: 5px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        }

        /* Row 3 - Seat and QR */
        .seat-qr-row {
            display: flex;
            gap: 5mm;
        }

        .seat-col, .qr-col {
            flex: 1;
        }

        .seat-section, .qr-section {
            background: white;
            border-radius: 6px;
            padding: 5px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            height: 110px; /* Reduced from 140px */
            box-sizing: border-box;
        }

        .seat-title, .qr-title {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            color: #0c4a6e;
            margin-bottom: 5px;
        }

        .seat-number {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #0284c7;
            margin: 2px 0;
        }

        .seat-category {
            font-size: 12px;
            text-align: center;
            color: #0c4a6e;
            font-weight: bold;
        }

        .qr-content {
            text-align: center;
            padding: 5px;
        }

        .qr-placeholder {
            width: 100px;
            height: 100px;
            background: white;
            border: 2px dashed #93c5fd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border-radius: 8px;
            font-size: 8px;
            color: #3730a3;
        }

        /* PAGE 2 - Regulations */
        .page-regulations {
            position: relative;
        }

        .regulations-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
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
            font-size: 8.5px; /* Reduced from 9.5px */
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
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .test-info-section h4 {
            margin: 0 0 8px 0;
            color: #1e40af;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            padding-bottom: 4px;
            border-bottom: 1px solid #7dd3fc;
        }

        .test-grid {
            display: grid;
            grid-template-columns: 130px 1fr;
            gap: 6px 10px;
            font-size: 11px;
        }

        .test-label {
            font-weight: bold;
            color: #1e293b;
            align-self: start;
        }

        .test-colon {
            align-self: start;
            font-weight: bold;
            color: #8b5cf6;
        }

        .test-value {
            color: #1e293b;
            font-weight: 500;
            align-self: start;
        }

        .status-value {
            color: {{ $participant->status === 'confirmed' ? '#16a34a' : ($participant->status === 'pending' ? '#ca8a04' : '#dc2626') }};
            font-weight: bold;
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

        /* Repeating Watermark */
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

        /* Removed page break */
        .page-1-end {
            display: none;
        }
    </style>
</head>
<body>
    <div class="watermark-container"></div>
    <div class="page">
        <!-- Identity Information Section -->
        <div class="page-header">
            @php
                $isGraduateTitle = \Str::contains(strtolower($participant->academic_level), ['s2', 's3', 'magister', 'doktor', 'master', 'doctor']);
                $cardTitle = $isGraduateTitle ? 'KARTU UJIAN TOEFL-EQUIVALENT' : 'KARTU UJIAN TOEFL-LIKE';
            @endphp
            <h1>{{ $cardTitle }}</h1>
            <p>Universitas Halu Oleo - UPA Bahasa</p>
        </div>

        <div class="card-container">
            <!-- Identity contents... (omitted for brevity in replacement but kept in file) -->
            <!-- Row 1: Logo -->
            <div class="row logo-row">
                @if(file_exists(public_path('logo-uho-dan-diktisaintek-768x143.png')))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('logo-uho-dan-diktisaintek-768x143.png'))) }}" alt="Logo UHO dan Diktisaintek" class="logo-img" style="height: 55px;" />
                @else
                    <div style="height: 40px; text-align: center; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 12px; font-weight: bold;">
                        LOGO UHO
                    </div>
                @endif
                <div style="text-align: center; font-weight: bold; font-size: 15px; margin-top: 2px; color: #1e40af;">KARTU TES</div>
            </div>

            <!-- Row 1.5: Title REMOVED (Merged into table) -->

            <!-- Row 2: Info and Photo -->
            <div class="row info-photo-row">
                <div class="info-col">
                    <div class="info-section">
                        <table class="info-table">
                            <tr>
                                <td class="info-label" colspan="4" style="font-weight: bold; text-align: center; background: linear-gradient(135deg, #0ea5e9, #1e40af); color: white; padding: 8px; border-radius: 6px; letter-spacing: 1px;">INFORMASI PESERTA</td>
                            </tr>
                            <tr>
                                <td class="info-label">Nama Lengkap</td>
                                <td class="info-colon">:</td>
                                <td class="info-value">{{ $participant->name }}</td>
                                <td class="info-photo" rowspan="7" style="vertical-align: top; text-align: center; padding: 0; padding-left: 10px;">
                                    <div class="photo-container" style="margin: 0; width: 100px; height: 130px; border: 1px solid #e2e8f0; border-radius: 4px; overflow: hidden; background: #f8fafc; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
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
                                            <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($photoFullPath)) }}" alt="Photo Peserta" style="width: 100%; height: 100%; object-fit: cover;" />
                                        @else
                                            <div class="photo-placeholder" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; color: #64748b; font-size: 8px; text-align: center; padding: 5px; background: linear-gradient(135deg, #f1f5f9, #e2e8f0);">
                                                PAS PHOTO<br>PESERTA
                                            </div>
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
                                    @if($participant->academic_level_display)
                                        {{ $participant->academic_level_display }} - {{ $participant->major }}
                                    @else
                                        {{ $participant->major }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="info-label">Fakultas</td>
                                <td class="info-colon">:</td>
                                <td class="info-value">{{ $participant->faculty }}</td>
                            </tr>
                            <tr>
                                <td class="info-label">Jenis Kelamin</td>
                                <td class="info-colon">:</td>
                                <td class="info-value">{{ $participant->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <td class="info-label">Tempat & Tanggal Lahir</td>
                                <td class="info-colon">:</td>
                                <td class="info-value">{{ $participant->birth_place }}, {{ $participant->birth_date->format('d F Y') }}</td>
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

            <!-- Row 3: Seat Number and QR Code -->
            <table style="width: 100%; border-collapse: separate; border-spacing: 5mm 0;">
                <tr>
                    <td style="width: 50%; vertical-align: top; padding: 0;">
                        <div class="seat-section">
                            <div style="display: table; width: 100%; height: 100%;">
                                <div style="display: table-cell; vertical-align: middle;">
                                    <div class="seat-title">NOMOR KURSI ANDA</div>
                                    <div class="seat-number">{{ $participant->effective_seat_number }}</div>
                                    <div class="seat-category">Kategori: {{ $isGraduateTitle ? 'TOEFL-EQUIVALENT' : 'TOEFL-LIKE' }}</div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="width: 50%; vertical-align: top; padding: 0;">
                        <div class="qr-section">
                            <div style="display: table; width: 100%; height: 100%;">
                                <div style="display: table-cell; vertical-align: middle;">
                                    <div class="qr-title">KODE VERIFIKASI</div>
                                    <div class="qr-content">
                                        <div style="text-align: center;">
                                            @php
                                                $qrcode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->generate($verificationUrl));
                                            @endphp
                                            <img src="data:image/svg+xml;base64,{{ $qrcode }}" alt="QR Code" style="width: 70px; height: 70px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Regulations Section Merged -->
        <div class="regulations-container">
            <div class="instructions" style="margin-top: 2px;">PESERTA WAJIB MEMBAWA PRINT OUT KARTU TES INI</div>

            <!-- Regulations Section -->
            <div class="regulations-section">
                <h4>TATA TERTIB MENGIKUTI TES TOEFL UPA-BAHASA UNIVERSITA HALU OLEO</h4>
                <ol>
                    <li>Tes TOEFL dilaksanakan tepat waktu Peserta harus sudah berada di depan ruangan 30 menit sebelum Jam/Waktu Tes.</li>
                    <li>Peserta harus membawa kartu identitas diri (KTP/SIM/kartu mahasiswa/kartu perpustakaan). Jika tidak dapat menunjukkan kartu identitas diri, peserta tidak diperkenankan mengikuti tes.</li>
                    <li>Peserta wajib membawa SLIP PEMBAYARAN TES TOEFL (BERWARNA PINK) pada saat tes.</li>
                    <li>Peserta yang terlambat dengan alasan apapun tidak diperkenankan mengikuti tes.</li>
                    <li>Peserta yang tidak hadir pada saat tes harus mendaftar kembali jika ingin mengikuti tes TOEFL.</li>
                    <li>Sebelum tes dimulai peserta harus menonaktifkan semua alat komunikasi (handphone, dsb).</li>
                    <li>Peserta yang mengaktifkan alat komunikasi pada saat tes berlangsung akan dikeluarkan dari ruangan tes.</li>
                    <li>Selama tes berlangsung peserta tidak diperbolehkan berkomunikasi dalam bentuk apapun dengan sesama peserta tes.</li>
                    <li>Selama tes berlangsung peserta tidak boleh meninggalkan ruangan tes tanpa ijin dari pengawas tes.</li>
                    <li>Peserta tes tidak boleh membawa catatan/buku pada saat tes berlangsung.</li>
                    <li>Peserta tes tidak boleh menulis/mengotori atau membawa buku tes keluar dari ruangan tes.</li>
                    <li>Peserta tes harus mengerjakan tes sesuai dengan section yang sedang berlangsung.</li>
                    <li>Setelah tes selesai, lembar jawaban diselipkan di dalam buku tes dan diletakkan di meja/kursi masing-masing peserta.</li>
                    <li>Setelah tes berakhir, peserta tes meninggalkan ruangan tes secara bersamaan dengan tertib.</li>
                    <li>Tidak diperkenankan meninggalkan ruangan tes sebelum waktu tes berakhir.</li>
                </ol>
            </div>

            <!-- Test Information Section -->
            <div class="test-info-section">
                <table class="info-table">
                    <tr>
                        <td class="info-label" colspan="4" style="font-weight: bold; text-align: center; background: linear-gradient(135deg, #0ea5e9, #1e40af); color: white; padding: 8px; border-radius: 6px;">INFORMASI TES</td>
                    </tr>
                    <tr>
                        <td class="info-label">Tanggal Tes</td>
                        <td class="info-colon">:</td>
                        <td class="info-value">{{ $participant->schedule->date->format('d F Y') }}</td>
                        <td class="info-photo" rowspan="5" style="vertical-align: top; text-align: center; padding: 0;">
                            <div style="width: 100px; height: 75px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border: 1px solid #bae6fd; border-radius: 6px; margin: 0;">
                                <div style="font-size: 14px; font-weight: bold; color: #0ea5e9; text-align: center;">PENGAWAS</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Jam/Waktu Tes</td>
                        <td class="info-colon">:</td>
                        <td class="info-value">08:00 - 11:00 WITA</td>
                    </tr>
                    <tr>
                        <td class="info-label">Ruangan</td>
                        <td class="info-colon">:</td>
                        <td class="info-value">{{ $participant->schedule->room }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Kapasitas</td>
                        <td class="info-colon">:</td>
                        <td class="info-value">{{ $participant->schedule->used_capacity }}/{{ $participant->schedule->capacity }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Status Pendaftaran</td>
                        <td class="info-colon">:</td>
                        <td class="info-value">
                            <span class="status-value">
                                {{ $participant->status === 'confirmed' ? 'Terkonfirmasi' : ($participant->status === 'pending' ? 'Tertunda' : 'Dibatalkan') }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
                <p>Universitas Halu Oleo - UPA Bahasa | Kendari, Sulawesi Tenggara</p>
            </div>
        </div>
    </div>
</body>
</html>