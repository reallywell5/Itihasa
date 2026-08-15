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
}
