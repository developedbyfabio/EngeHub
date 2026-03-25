<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function sectors(): HasMany
    {
        return $this->hasMany(Sector::class)->orderBy('order')->orderBy('name');
    }

    public function formLinks(): HasMany
    {
        return $this->hasMany(FormLink::class, 'branch_id');
    }

    public function formResponses(): HasMany
    {
        return $this->hasMany(FormResponse::class, 'branch_id');
    }

    public function forms(): BelongsToMany
    {
        return $this->belongsToMany(Form::class, 'form_links')
            ->withPivot(['token', 'is_active'])
            ->withTimestamps();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($branch) {
            if (empty($branch->slug)) {
                $branch->slug = Str::slug($branch->name);
            }
        });
    }
}
