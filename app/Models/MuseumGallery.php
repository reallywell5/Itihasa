<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MuseumGallery extends Model
{
    protected $fillable = [
        'museum_id',
        'image_path',
        'caption',
        'order',
    ];

    public function museum()
    {
        return $this->belongsTo(Museum::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return asset('images/default-museum.jpg');
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'storage/')) {
            return asset($this->image_path);
        }

        return asset('storage/' . $this->image_path);
    }
}
