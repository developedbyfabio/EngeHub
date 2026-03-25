<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SeatAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'seat_id',
        'user_id',
        'collaborator_name',
        'computer_name',
        'started_at',
        'ended_at',
        'reason',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Relacionamento: atribuição pertence a uma mesa
     */
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    /**
     * Relacionamento: atribuição pertence a um usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: apenas atribuições ativas (sem ended_at)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('ended_at');
    }

    /**
     * Scope: apenas atribuições finalizadas
     */
    public function scopeEnded($query)
    {
        return $query->whereNotNull('ended_at');
    }

    /**
     * Verifica se a atribuição está ativa
     */
    public function isActive()
    {
        return $this->ended_at === null;
    }

    /**
     * Calcula a duração da atribuição
     */
    public function getDurationAttribute()
    {
        $end = $this->ended_at ?? now();
        return $this->started_at->diff($end);
    }

    /**
     * Duração formatada em texto
     */
    public function getFormattedDurationAttribute()
    {
        $duration = $this->duration;
        
        if ($duration->days > 0) {
            return $duration->days . ' dia(s)';
        } elseif ($duration->h > 0) {
            return $duration->h . ' hora(s)';
        } else {
            return $duration->i . ' minuto(s)';
        }
    }

    /**
     * Período da atribuição formatado
     */
    public function getPeriodAttribute()
    {
        $start = $this->started_at->format('d/m/Y H:i');
        $end = $this->ended_at ? $this->ended_at->format('d/m/Y H:i') : 'Atual';
        
        return "{$start} → {$end}";
    }
}
