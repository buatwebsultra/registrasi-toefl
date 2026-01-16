<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = [
        'title',
        'description',
        'media_type',
        'file_path',
        'is_active',
    ];

    /**
     * Get the URL of the media file.
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
