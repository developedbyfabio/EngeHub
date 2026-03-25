<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StandardWeightOption extends Model
{
    use HasFactory;

    protected $fillable = ['profile_id', 'option_text', 'weight', 'order'];

    protected $casts = [
        'weight' => 'integer',
        'order' => 'integer',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(StandardWeightProfile::class, 'profile_id');
    }
}
