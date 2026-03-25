<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'branch_id',
        'submitted_at',
        'completion_time_seconds',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(FormResponseAnswer::class, 'response_id');
    }
}
