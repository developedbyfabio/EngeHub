<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class FormTheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'title',
        'description',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(FormQuestion::class, 'theme_id')->orderBy('order');
    }

    public function getCodeAttribute(): string
    {
        if (preg_match('/\(([A-Z0-9]+)\)\s*$/', $this->title ?? '', $m)) {
            return $m[1];
        }
        return \Str::limit($this->title ?? 'Tema', 8);
    }
}
