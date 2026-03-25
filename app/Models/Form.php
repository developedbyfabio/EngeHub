<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function themes(): HasMany
    {
        return $this->hasMany(FormTheme::class)->orderBy('order');
    }

    public function questions(): HasManyThrough
    {
        return $this->hasManyThrough(
            FormQuestion::class,
            FormTheme::class,
            'form_id',   // Foreign key on form_themes
            'theme_id',  // Foreign key on form_questions (Laravel esperava form_theme_id)
            'id',        // Local key on forms
            'id'         // Local key on form_themes
        )->orderBy('form_questions.order');
    }

    public function links(): HasMany
    {
        return $this->hasMany(FormLink::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(FormResponse::class);
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'form_links')
            ->withPivot(['token', 'is_active'])
            ->withTimestamps();
    }

    public function standardWeightProfiles(): HasMany
    {
        return $this->hasMany(StandardWeightProfile::class)->orderBy('name');
    }
}
