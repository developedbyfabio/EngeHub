<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Sector extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'slug',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function formLinks(): HasMany
    {
        return $this->hasMany(FormLink::class, 'sector_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($sector) {
            if (empty($sector->slug)) {
                $sector->slug = Str::slug($sector->name);
            }
        });
    }
}
