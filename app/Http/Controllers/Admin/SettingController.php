<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && !auth()->user()->isAdmin()) {
                abort(403, 'Akses terbatas untuk Administrator.');
            }
            return $next($request);
        });
    }

    /**
     * Show the certificate settings page.
     */
    public function certificateSettings()
    {
        $signerTitle = Setting::get('certificate_signer_title', 'Head of UPA Bahasa UHO,');
        $signerName = Setting::get('certificate_signer_name', 'Ir. Uniadi Mangidi, S.T., M.T., M.Eng.Sc');
        $signerNip = Setting::get('certificate_signer_nip', '19750614 200212 1 002');
        $signatureUrl = Setting::getSignatureUrl();
        $isCustomSignature = Setting::isCustomSignature();

        return view('admin.settings.certificate', compact(
            'signerTitle',
            'signerName',
            'signerNip',
            'signatureUrl',
            'isCustomSignature'
        ));
    }

    /**
     * Update certificate settings (signer title, name, NIP, and digital signature/stamp image).
     */
    public function updateCertificateSettings(Request $request)
    {
        $request->validate([
            'signer_title' => 'required|string|max:255',
            'signer_name' => 'required|string|max:255',
            'signer_nip' => 'required|string|max:255',
            'signature_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'signer_title.required' => 'Jabatan penandatangan wajib diisi.',
            'signer_name.required' => 'Nama penandatangan wajib diisi.',
            'signer_nip.required' => 'NIP penandatangan wajib diisi.',
            'signature_image.image' => 'File harus berupa file gambar.',
            'signature_image.mimes' => 'Format gambar harus PNG, JPG, atau JPEG (Direkomendasikan PNG transparan).',
            'signature_image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('signature_image')) {
            $oldPath = Setting::get('certificate_signature_path');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('signature_image')->store('signatures', 'public');
            Setting::set('certificate_signature_path', $path, 'certificate');
        }

        Setting::set('certificate_signer_title', trim($request->signer_title), 'certificate');
        Setting::set('certificate_signer_name', trim($request->signer_name), 'certificate');
        Setting::set('certificate_signer_nip', trim($request->signer_nip), 'certificate');

        ActivityLogger::log('Update Pengaturan Sertifikat', 'Admin memperbarui konfigurasi penandatangan dan stempel sertifikat.');

        return redirect()->route('admin.settings.certificate')->with('success', 'Pengaturan sertifikat dan tanda tangan digital berhasil diperbarui.');
    }

    /**
     * Revert signature image back to system default.
     */
    public function deleteCertificateSignature()
    {
        $oldPath = Setting::get('certificate_signature_path');
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        Setting::set('certificate_signature_path', null, 'certificate');

        ActivityLogger::log('Reset Tanda Tangan Sertifikat', 'Admin mengembalikan gambar tanda tangan sertifikat ke default sistem.');

        return redirect()->route('admin.settings.certificate')->with('success', 'Gambar tanda tangan berhasil dikembalikan ke default sistem.');
    }
}
