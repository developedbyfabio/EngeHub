<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DvrFoto extends Model
{
    protected $table = 'dvr_fotos';

    protected $fillable = [
        'dvr_id',
        'disk',
        'path',
        'original_filename',
        'user_id',
    ];

    public function dvr(): BelongsTo
    {
        return $this->belongsTo(Dvr::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}
