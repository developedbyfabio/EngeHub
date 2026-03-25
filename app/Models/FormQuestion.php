<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormQuestion extends Model
{
    use HasFactory;

    protected $table = 'form_questions';

    protected $fillable = [
        'form_id',
        'theme_id',
        'question_text',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(FormTheme::class, 'theme_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(FormQuestionOption::class, 'question_id')->orderBy('order')->orderBy('id');
    }

    public function responseAnswers(): HasMany
    {
        return $this->hasMany(FormResponseAnswer::class, 'question_id');
    }
}
