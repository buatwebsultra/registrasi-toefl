<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GalleryItem;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->isProdi()) {
                return redirect()->route('prodi.dashboard');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $items = GalleryItem::latest()->get();
        return view('admin.gallery.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'media_type' => 'required|in:image,video',
            'file' => 'required|file|max:10240', // Max 10MB
            'is_active' => 'boolean',
        ]);

        if ($request->media_type === 'image') {
            $request->validate(['file' => 'image|mimes:jpeg,png,jpg,gif']);
        } else {
            $request->validate(['file' => 'mimetypes:video/mp4,video/quicktime,video/x-msvideo']);
        }

        $path = $request->file('file')->store('gallery', 'public');

        $item = GalleryItem::create([
            'title' => $request->title,
            'description' => $request->description,
            'media_type' => $request->media_type,
            'file_path' => $path,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLogger::log('Upload Galeri', 'User Upload Galeri Baru: ' . ($item->title ?? 'Untitled'));

        return redirect()->route('admin.gallery.index')->with('success', 'Item galeri berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = GalleryItem::findOrFail($id);
        
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $item->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);
        
        ActivityLogger::log('Update Galeri', 'User Update Galeri: ' . ($item->title ?? 'Untitled'));

        return redirect()->route('admin.gallery.index')->with('success', 'Item galeri berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = GalleryItem::findOrFail($id);

        if (Storage::disk('public')->exists($item->file_path)) {
            Storage::disk('public')->delete($item->file_path);
        }

        $item->delete();
        
        ActivityLogger::log('Hapus Galeri', 'User Hapus Galeri: ' . ($item->title ?? 'Untitled'));

        return redirect()->route('admin.gallery.index')->with('success', 'Item galeri berhasil dihapus.');
    }
}
