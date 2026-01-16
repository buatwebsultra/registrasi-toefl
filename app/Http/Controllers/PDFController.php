<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PDFController extends Controller
{
    public function showTestCardPreview($id)
    {
        // This method shows the card preview in the browser before allowing download
        $participant = Participant::with('schedule')->findOrFail($id);

        // Check authorization: must be participant themselves or admin
        $sessionParticipantId = session('participant_id');
        $isParticipant = !is_null($sessionParticipantId) && (string)$sessionParticipantId === (string)$id;
        $isAdmin = auth()->guard('web')->check() && auth()->user() && auth()->user()->isAdmin();

        if (!$isParticipant && !$isAdmin) {
            abort(403, 'Unauthorized access to participant card');
        }

        // SECURITY: If user is prodi, they can only access card for their own study program
        if (auth()->user() && auth()->user()->isProdi() && $participant->study_program_id !== auth()->user()->study_program_id) {
            abort(403, 'Anda tidak memiliki akses ke kartu peserta dari Program Studi lain.');
        }

        // Restrict access if participant has failed
        if ($participant->test_score !== null && !$participant->passed && !$isAdmin) {
            abort(403, 'Kartu ujian tidak tersedia untuk peserta yang tidak lulus.');
        }

        $verificationUrl = route('participant.card.show', ['id' => $participant->id, 'token' => $participant->verification_token]);

        return view('participant.test-card', compact('participant', 'verificationUrl'));
    }

    public function generateTestCard($id)
    {
        // This method generates the PDF card
        $participant = Participant::with('schedule')->findOrFail($id);

        // Check authorization: must be participant themselves or admin
        $sessionParticipantId = session('participant_id');
        $isParticipant = !is_null($sessionParticipantId) && (string)$sessionParticipantId === (string)$id;
        $isAdmin = auth()->guard('web')->check() && auth()->user() && auth()->user()->isAdmin();

        if (!$isParticipant && !$isAdmin) {
            abort(403, 'Unauthorized access to participant card');
        }

        // SECURITY: If user is prodi, they can only access card for their own study program
        if (auth()->user() && auth()->user()->isProdi() && $participant->study_program_id !== auth()->user()->study_program_id) {
            abort(403, 'Anda tidak memiliki akses ke kartu peserta dari Program Studi lain.');
        }

        // Restrict access if participant has failed
        if ($participant->test_score !== null && !$participant->passed && !$isAdmin) {
            abort(403, 'Kartu ujian tidak tersedia untuk peserta yang tidak lulus.');
        }

        $verificationUrl = route('participant.card.show', ['id' => $participant->id, 'token' => $participant->verification_token]);

        $pdf = Pdf::loadView('participant.test-card', compact('participant', 'verificationUrl'));

        // Set paper size to 13-inch Folio (8.5 x 13 inches)
        // 8.5" = 612pt, 13" = 936pt
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        // Log activity
        ActivityLogger::log('Download Kartu Tes', 'Peserta ' . $participant->name . ' mengunduh kartu tes.');

        return $pdf->download('toefl-test-card-' . $participant->nim . '.pdf');
    }

    public function showTestCard($id, $token)
    {
        // This method is for QR code verification - accessed by scanning the QR code
        $participant = Participant::with('schedule')->findOrFail($id);
        
        // Verify token for privacy
        if ($participant->verification_token !== $token) {
            abort(404, 'Invalid verification link atau token tidak valid.');
        }

        $verificationUrl = route('participant.card.show', ['id' => $participant->id, 'token' => $participant->verification_token]);

        // Return a simplified view that shows verification information
        return view('participant.test-card', compact('participant', 'verificationUrl'));
    }

    public function terbilang($number)
    {
        $number = (int)$number;

        if ($number < 0) {
            return 'minus ' . $this->terbilang(abs($number));
        }

        $huruf = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

        if ($number < 12) {
            return $huruf[$number];
        } elseif ($number < 20) {
            return $huruf[$number - 10] . ' Belas';
        } elseif ($number < 100) {
            $puluhan = (int)($number / 10);
            $sisa = $number % 10;
            if ($sisa == 0) {
                return $huruf[$puluhan] . ' Puluh';
            } else {
                return $huruf[$puluhan] . ' Puluh ' . $this->terbilang($sisa);
            }
        } elseif ($number < 200) {
            return 'Seratus ' . $this->terbilang($number - 100);
        } elseif ($number < 1000) {
            $ratusan = (int)($number / 100);
            $sisa = $number % 100;
            if ($sisa == 0) {
                return $huruf[$ratusan] . ' Ratus';
            } else {
                return $huruf[$ratusan] . ' Ratus ' . $this->terbilang($sisa);
            }
        } elseif ($number < 2000) {
            return 'Seribu ' . $this->terbilang($number - 1000);
        } elseif ($number < 1000000) {
            $ribuan = (int)($number / 1000);
            $sisa = $number % 1000;
            if ($sisa == 0) {
                return $this->terbilang($ribuan) . ' Ribu';
            } else {
                if ($ribuan == 1) {
                    return 'Seribu ' . $this->terbilang($sisa);
                } else {
                    return $this->terbilang($ribuan) . ' Ribu ' . $this->terbilang($sisa);
                }
            }
        } elseif ($number < 1000000000) {
            $jutaan = (int)($number / 1000000);
            $sisa = $number % 1000000;
            if ($sisa == 0) {
                return $this->terbilang($jutaan) . ' Juta';
            } else {
                return $this->terbilang($jutaan) . ' Juta ' . $this->terbilang($sisa);
            }
        } else {
            return 'Angka terlalu besar';
        }
    }

    public function generateCertificate($id)
    {
        // This method generates the certificate PDF for participants who passed
        $participant = Participant::with('schedule', 'studyProgram')->findOrFail($id);

        // Check authorization: must be participant themselves or admin/operator
        $sessionParticipantId = session('participant_id');
        $isParticipant = !is_null($sessionParticipantId) && (string)$sessionParticipantId === (string)$id;
        $isStaff = auth()->guard('web')->check() && auth()->user() && auth()->user()->isOperator();

        if (!$isParticipant && !$isStaff) {
            abort(403, 'Unauthorized access to certificate');
        }

        // SECURITY: If user is prodi (part of isStaff), they can only access certificate for their own study program
        if ($isStaff && auth()->user()->isProdi() && $participant->study_program_id !== auth()->user()->study_program_id) {
            abort(403, 'Anda tidak memiliki akses ke sertifikat peserta dari Program Studi lain.');
        }


        // Check if participant has a test score
        if (!$participant->test_score) {
            abort(403, 'Certificate only available for participants who have been scored.');
        }

        // For participants, they must also pass and the score must be validated. 
        // Staff can generate regardless of pass status or validation status.
        if ($isParticipant && (!$participant->passed || !$participant->is_score_validated)) {
            abort(403, 'Certificate only available for participants who have passed the test and have validated scores');
        }

        // Generate verification URL for the certificate
        $verificationUrl = route('participant.card.show', ['id' => $participant->id, 'token' => $participant->verification_token]);

        $pdf = Pdf::loadView('participant.certificate', compact('participant', 'verificationUrl'));

    // Set paper size and orientation for certificate (216mm x 135mm)
        // 216mm = 612.28 pt, 135mm = 382.68 pt
        $pdf->setPaper([0, 0, 382.68, 612.28], 'landscape');

        // Log activity
        ActivityLogger::log('Download Sertifikat', 'Peserta ' . $participant->name . ' mengunduh sertifikat TOEFL.');

        return $pdf->download('toefl-certificate-' . $participant->nim . '.pdf');
    }

}